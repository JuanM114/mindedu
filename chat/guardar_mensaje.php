<?php

session_start();


const MODO_DESARROLLO = true;


const IA_TIMEOUT_SEG = 20;

set_time_limit(110);

ini_set('display_errors', 0);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

set_error_handler(function ($nivel, $msg, $archivo, $linea) {
    if (!(error_reporting() & $nivel)) {
        return false;
    }
    throw new ErrorException($msg, 0, $nivel, $archivo, $linea);
});

set_exception_handler(function (Throwable $e) {
    error_log($e->getMessage() . ' en ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        "success" => false,
        "mensaje" => MODO_DESARROLLO
            ? "Error interno: " . $e->getMessage()
            : "Ahora mismo no puedo responder. Inténtalo de nuevo en unos minutos."
    ]);
    exit();
});

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['usuario_id'])) {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "mensaje" => "Sesión no válida"
    ]);

    exit();
}

require_once("../config/database.php");

require_once("../config/openai.php");

$idUsuario = $_SESSION['usuario_id'];

$conversacionId = isset($_POST['conversacion_id'])
    ? (int) $_POST['conversacion_id']
    : 0;

$mensaje = isset($_POST['mensaje'])
    ? trim($_POST['mensaje'])
    : '';

if ($conversacionId <= 0 || $mensaje === '') {

    echo json_encode([
        "success" => false,
        "mensaje" => "Datos incompletos"
    ]);

    exit();
}

/*
|--------------------------------------------------------------------------
| Verificar conversación
|--------------------------------------------------------------------------
*/

$sqlValidar = "
SELECT id, titulo
FROM conversaciones
WHERE id = ?
AND usuario_id = ?
";

$stmtValidar = $conn->prepare($sqlValidar);

$stmtValidar->bind_param(
    "ii",
    $conversacionId,
    $idUsuario
);

$stmtValidar->execute();

$resultado = $stmtValidar->get_result();

if ($resultado->num_rows === 0) {

    echo json_encode([
        "success" => false,
        "mensaje" => "Conversación no encontrada"
    ]);

    exit();
}

$conversacion = $resultado->fetch_assoc();

/*
|--------------------------------------------------------------------------
| Guardar mensaje usuario
|--------------------------------------------------------------------------
*/

$sqlMensaje = "
INSERT INTO mensajes (
    conversacion_id,
    remitente,
    mensaje
)
VALUES (
    ?,
    'usuario',
    ?
)
";

$stmtMensaje = $conn->prepare($sqlMensaje);

$stmtMensaje->bind_param(
    "is",
    $conversacionId,
    $mensaje
);

$stmtMensaje->execute();

/*
|--------------------------------------------------------------------------
| Actualizar título
|--------------------------------------------------------------------------
*/

if ($conversacion['titulo'] === 'Nueva conversación') {

    $titulo = mb_substr(
        $mensaje,
        0,
        50,
        'UTF-8'
    );

    if (
        mb_strlen(
            $mensaje,
            'UTF-8'
        ) > 50
    ) {
        $titulo .= "...";
    }

    $sqlTitulo = "
    UPDATE conversaciones
    SET titulo = ?
    WHERE id = ?
    ";

    $stmtTitulo = $conn->prepare($sqlTitulo);

    $stmtTitulo->bind_param(
        "si",
        $titulo,
        $conversacionId
    );

    $stmtTitulo->execute();
}

/*
|--------------------------------------------------------------------------
| Una sola llamada a UN modelo (no lanza excepciones: devuelve un resultado)
|--------------------------------------------------------------------------

*/

function llamarModelo($modelo, $mensajeUsuario, $contextoMindEdu)
{
    $payload = [

        "model" => $modelo,

        "messages" => [

            [
                "role" => "system",
                "content" => "

Eres MindEdu.

Eres un asistente de bienestar emocional
para estudiantes universitarios.

Ayudas con:

- Estrés académico
- Ansiedad
- Autoestima
- Organización del tiempo
- Regulación emocional
- Hábitos saludables

Si existen recursos o ejercicios de MindEdu
relacionados con la consulta del usuario,
recomiéndalos de forma natural al final
de tu respuesta.

Información disponible:

$contextoMindEdu

Responde siempre en español.
Sé empático, respetuoso y cercano.

No reemplazas atención psicológica profesional.

Si existe riesgo para la integridad del usuario,
recomienda ayuda profesional inmediata.

Si existen recursos o ejercicios disponibles
en la información proporcionada, selecciona
los más adecuados y recomiéndalos al final
de tu respuesta.

Añade una sección llamada:

Recomendaciones de MindEdu

Menciona únicamente recursos y ejercicios
realmente disponibles en la lista entregada.

No inventes recursos ni ejercicios.
"
            ],

            [
                "role" => "user",
                "content" => $mensajeUsuario
            ]

        ],

        "temperature" => 0.7,

        "max_tokens" => 1000

    ];

    $curl = curl_init(IA_URL);

    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => IA_TIMEOUT_SEG,
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer " . IA_API_KEY
        ],
        CURLOPT_POSTFIELDS => json_encode($payload)
    ]);

    $response = curl_exec($curl);

    if ($response === false) {
        $errno = curl_errno($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return [
            'ok' => false,
            'http' => 0,
            'transitorio' => true,
            'error' => "cURL ($errno): " . $err
        ];
    }

    $http = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $resultado = json_decode($response, true);

    // Error devuelto por la API
    if ($http >= 400) {
        $detalle = $resultado['error']['message']
            ?? $resultado[0]['error']['message']
            ?? "HTTP $http";
        return [
            'ok' => false,
            'http' => $http,

            'transitorio' => in_array($http, [429, 500, 502, 503, 504], true),
            'error' => "IA ($http, $modelo): " . $detalle
        ];
    }

    $texto = $resultado['choices'][0]['message']['content'] ?? '';

    if (trim($texto) === '') {
        return [
            'ok' => false,
            'http' => $http,
            'transitorio' => false,
            'error' => "IA ($modelo): respuesta vacía"
        ];
    }

    return ['ok' => true, 'texto' => $texto];
}



function generarRespuestaIA(
    $mensajeUsuario,
    $contextoMindEdu
)
{
    $modelos = [IA_MODEL];

    if (
        defined('IA_MODEL_RESPALDO')
        && IA_MODEL_RESPALDO !== ''
        && IA_MODEL_RESPALDO !== IA_MODEL
    ) {
        $modelos[] = IA_MODEL_RESPALDO;
    }

    $maxIntentos = 2;
    $ultimoError = 'Sin respuesta de la IA';

    foreach ($modelos as $modelo) {

        for ($intento = 1; $intento <= $maxIntentos; $intento++) {

            $r = llamarModelo($modelo, $mensajeUsuario, $contextoMindEdu);

            if ($r['ok']) {
                return $r['texto'];
            }

            $ultimoError = $r['error'];
            error_log("IA: falló $modelo (intento $intento): " . $r['error']);

            if (in_array($r['http'], [401, 403], true)) {
                throw new RuntimeException($r['error']);
            }

            if (!$r['transitorio']) {
                break;
            }

            if ($intento < $maxIntentos) {
                sleep(2);
            }
        }
    }

    throw new RuntimeException($ultimoError);
}
$textoBusqueda =
mb_strtolower($mensaje);

$contextoMindEdu = "";
if(

    strpos($textoBusqueda, 'estres') !== false ||
    strpos($textoBusqueda, 'estrés') !== false ||
    strpos($textoBusqueda, 'agotado') !== false

){

    $contextoMindEdu .= "

    Recursos de MindEdu relacionados:

    - Manejo del estrés
    - Organización del tiempo

    Ejercicios recomendados:

    - Respiración consciente
    - Relajación guiada

    ";

}
$contextoMindEdu = "";

/*
|--------------------------------------------------------------------------
| ANSIEDAD
|--------------------------------------------------------------------------
*/

if(

    strpos($textoBusqueda, 'ansiedad') !== false ||
    strpos($textoBusqueda, 'ansioso') !== false ||
    strpos($textoBusqueda, 'nervioso') !== false

){

    $contextoMindEdu .= "\nCONTENIDO RECOMENDADO DE MINDEDU:\n";

    /* RECURSOS */

    $sqlRecursos = "

    SELECT titulo

    FROM recursos

    LIMIT 3

    ";

    $resultadoRecursos =
    $conn->query($sqlRecursos);

    $contextoMindEdu .=
    "\nRecursos disponibles:\n";

    while(

        $recurso =
        $resultadoRecursos->fetch_assoc()

    ){

        $contextoMindEdu .=
        "- " .
        $recurso['titulo'] .
        "\n";

    }

    /* EJERCICIOS */

    $sqlEjercicios = "

    SELECT titulo

    FROM ejercicios

    LIMIT 3

    ";

    $resultadoEjercicios =
    $conn->query($sqlEjercicios);

    $contextoMindEdu .=
    "\nEjercicios disponibles:\n";

    while(

        $ejercicio =
        $resultadoEjercicios->fetch_assoc()

    ){

        $contextoMindEdu .=
        "- " .
        $ejercicio['titulo'] .
        "\n";

    }

}

/*
|--------------------------------------------------------------------------
| SUEÑO
|--------------------------------------------------------------------------
*/

if(

    strpos($textoBusqueda, 'sueño') !== false ||
    strpos($textoBusqueda, 'insomnio') !== false ||
    strpos($textoBusqueda, 'dormir') !== false

){

    $contextoMindEdu .= "\nCONTENIDO RECOMENDADO DE MINDEDU:\n";

    /* RECURSOS */

    $sqlRecursos = "

    SELECT titulo

    FROM recursos

    LIMIT 3

    ";

    $resultadoRecursos =
    $conn->query($sqlRecursos);

    $contextoMindEdu .=
    "\nRecursos disponibles:\n";

    while(

        $recurso =
        $resultadoRecursos->fetch_assoc()

    ){

        $contextoMindEdu .=
        "- " .
        $recurso['titulo'] .
        "\n";

    }

    /* EJERCICIOS */

    $sqlEjercicios = "

    SELECT titulo

    FROM ejercicios

    LIMIT 3

    ";

    $resultadoEjercicios =
    $conn->query($sqlEjercicios);

    $contextoMindEdu .=
    "\nEjercicios disponibles:\n";

    while(

        $ejercicio =
        $resultadoEjercicios->fetch_assoc()

    ){

        $contextoMindEdu .=
        "- " .
        $ejercicio['titulo'] .
        "\n";

    }

}

$respuestaIA = generarRespuestaIA(
    $mensaje,
    $contextoMindEdu

    
);

/*
|--------------------------------------------------------------------------
| Guardar respuesta IA
|--------------------------------------------------------------------------
*/

$sqlIA = "
INSERT INTO mensajes (
    conversacion_id,
    remitente,
    mensaje
)
VALUES (
    ?,
    'ia',
    ?
)
";

$stmtIA = $conn->prepare($sqlIA);

$stmtIA->bind_param(
    "is",
    $conversacionId,
    $respuestaIA
);

$stmtIA->execute();

/*
|--------------------------------------------------------------------------
| Respuesta JSON
|--------------------------------------------------------------------------
*/

echo json_encode([

    "success" => true,

    "conversacion_id" => $conversacionId,

    "respuesta_ia" => $respuestaIA,


]);
exit();