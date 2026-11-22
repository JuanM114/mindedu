<?php

session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {

    echo json_encode([
        "success" => false,
        "mensaje" => "Sesión no válida"
    ]);

    exit();
}

require_once("../config/database.php");

$idUsuario = $_SESSION['usuario_id'];

$conversacionId = isset($_GET['conversacion_id'])
    ? (int) $_GET['conversacion_id']
    : 0;

if ($conversacionId <= 0) {

    echo json_encode([
        "success" => false,
        "mensaje" => "Conversación inválida"
    ]);

    exit();
}

/*
|--------------------------------------------------------------------------
| Verificar que la conversación pertenece al usuario
|--------------------------------------------------------------------------
*/

$sqlConversacion = "
SELECT id
FROM conversaciones
WHERE id = ?
AND usuario_id = ?
";

$stmtConversacion = $conn->prepare($sqlConversacion);

$stmtConversacion->bind_param(
    "ii",
    $conversacionId,
    $idUsuario
);

$stmtConversacion->execute();

$resultadoConversacion =
$stmtConversacion->get_result();

if ($resultadoConversacion->num_rows === 0) {

    echo json_encode([
        "success" => false,
        "mensaje" => "No tienes acceso a esta conversación"
    ]);

    exit();
}

/*
|--------------------------------------------------------------------------
| Obtener mensajes
|--------------------------------------------------------------------------
*/

$sqlMensajes = "
SELECT
    id,
    remitente,
    mensaje,
    fecha
FROM mensajes
WHERE conversacion_id = ?
ORDER BY fecha ASC
";

$stmtMensajes = $conn->prepare($sqlMensajes);

$stmtMensajes->bind_param(
    "i",
    $conversacionId
);

$stmtMensajes->execute();

$resultadoMensajes =
$stmtMensajes->get_result();

$mensajes = [];

while ($fila = $resultadoMensajes->fetch_assoc()) {

    $mensajes[] = [
        "id" => $fila["id"],
        "remitente" => $fila["remitente"],
        "mensaje" => $fila["mensaje"],
        "fecha" => $fila["fecha"]
    ];
}

echo json_encode([
    "success" => true,
    "mensajes" => $mensajes
]);

?>