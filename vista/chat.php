<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

require_once("../config/database.php");

$idUsuario = $_SESSION['usuario_id'];

$sql = "
SELECT nombre
FROM usuarios
WHERE id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();

$usuario = $stmt->get_result()->fetch_assoc();

$nombre = $usuario['nombre'];

// Conversación seleccionada

$conversacionActual = isset($_GET['conv'])
    ? (int)$_GET['conv']
    : 0;

// Historial de conversaciones

$sqlConversaciones = "
SELECT *
FROM conversaciones
WHERE usuario_id = ?
ORDER BY fecha_creacion DESC
";

$stmtConversaciones = $conn->prepare($sqlConversaciones);

$stmtConversaciones->bind_param(
    "i",
    $idUsuario
);

$stmtConversaciones->execute();

$conversaciones =
$stmtConversaciones->get_result();

// Mensajes de la conversación abierta

$mensajes = [];

if ($conversacionActual > 0) {

    $sqlMensajes = "
    SELECT *
    FROM mensajes
    WHERE conversacion_id = ?
    ORDER BY fecha ASC
    ";

    $stmtMensajes = $conn->prepare(
        $sqlMensajes
    );

    $stmtMensajes->bind_param(
        "i",
        $conversacionActual
    );

    $stmtMensajes->execute();

    $resultadoMensajes =
    $stmtMensajes->get_result();

    while (
        $fila =
        $resultadoMensajes->fetch_assoc()
    ) {

        $mensajes[] = $fila;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MindEdu - Chat IA</title>

    <link rel="stylesheet" href="../css/chat.css">

</head>

<body>

<div class="container">

    <!-- SIDEBAR PRINCIPAL -->
    <aside class="sidebar">

        <h2>MindEdu</h2>

        <nav>
            <ul>

                <li>
                    <a href="dashboard.php">
                        Inicio
                    </a>
                </li>

                <li>
                    <a href="recursos.php">
                        Recursos
                    </a>
                </li>

                <li>
                    <a href="relajacion.php">
                        Relajación
                    </a>
                </li>

                <li>
                    <a href="seguimiento.php">
                        Seguimiento
                    </a>
                </li>

                <li>
                    <a href="chat.php" class="active">
                        Chat IA
                    </a>
                </li>

                <li>
                    <a href="perfil.php">
                        Perfil
                    </a>
                </li>

                <li>
                    <a href="configuracion.php">
                        Configuración
                    </a>
                </li>

            </ul>
        </nav>

        <div class="support">

            <h3>No tienes que hacerlo solo.</h3>

            <p>
                Este espacio está diseñado para escucharte,
                acompañarte y ayudarte a encontrar recursos.
            </p>

        </div>

        <div class="user">

            <strong>
                <?php echo htmlspecialchars($nombre); ?>
            </strong>

            <small>
                Sesión iniciada
            </small>

            <p>
                <a href="../config/logout.php">
                    Cerrar sesión
                </a>
            </p>

        </div>

    </aside>

    <!-- CONTENIDO CHAT -->
    <main class="chat-main">

        <!-- CABECERA -->
        <div class="chat-header">

            <div>

                <span class="section-label">
                    ESCUCHA Y ORIENTACIÓN
                </span>

                <h1>
                    Hablemos de lo que necesitas hoy
                </h1>

                <p>
                    Explora ideas de bienestar y encuentra
                    recursos que puedan acompañarte.
                </p>

            </div>

        </div>

        <!-- ÁREA CHAT -->
        <div class="chat-container">

            <!-- HISTORIAL -->
            <aside class="conversaciones">

                <a
    href="../chat/crear_conversacion.php"
    class="btn-nueva-conversacion">

    + Nueva conversación

</a>

                <div
    id="historialConversaciones"
    class="historial-conversaciones">

    <?php if($conversaciones->num_rows > 0): ?>

        <?php while($conv = $conversaciones->fetch_assoc()): ?>

            <div class="chat-item">

                <a
                    class="chat-link"
                    href="chat.php?conv=<?php echo $conv['id']; ?>">

                    <h4>

                        <?php
                        echo htmlspecialchars(
                            $conv['titulo']
                        );
                        ?>

                    </h4>

                    <span>

                        <?php
                        echo date(
                            "d/m/Y H:i",
                            strtotime(
                                $conv['fecha_creacion']
                            )
                        );
                        ?>

                    </span>

                </a>

                <button
                    class="btn-eliminar"
                    onclick="eliminarConversacion(
                        <?php echo $conv['id']; ?>
                    )">

                    🗑

                </button>

            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <div
            id="sinConversaciones"
            class="sin-conversaciones">

            <p>
                No hay conversaciones todavía
            </p>

            <small>
                Inicia una nueva consulta
                para comenzar.
            </small>

        </div>

    <?php endif; ?>

</div>


            </aside>

            <!-- PANEL CHAT -->
            <section class="panel-chat">

                <!-- ESTADO VACÍO -->
                <div
id="estadoVacio"
class="estado-vacio"
<?php
if($conversacionActual > 0){
    echo 'style="display:none;"';
}
?>>

                    <div class="logo-chat">
                        🤖
                    </div>

                    <h2>
                        ¿En qué puedo acompañarte hoy <strong>
                <?php echo htmlspecialchars($nombre); ?>
            </strong>?
                    </h2>

                    <p>
                        Puedes hablar sobre estrés académico,
                        ansiedad, emociones, hábitos saludables,
                        autoestima y bienestar general.
                    </p>

                    <div class="sugerencias">

                        <button class="sugerencia-btn">
                            🧘 Necesito relajarme
                        </button>

                        <button class="sugerencia-btn">
                            😟 Me siento ansioso
                        </button>

                        <button class="sugerencia-btn">
                            😴 Quiero dormir mejor
                        </button>

                        <button class="sugerencia-btn">
                            📚 Estrés académico
                        </button>

                    </div>

                </div>

                <!-- MENSAJES -->
                <div
id="chatMessages"
class="chat-messages"
<?php
if($conversacionActual == 0){
    echo 'style="display:none;"';
}
?>>

    <?php foreach($mensajes as $mensaje): ?>

        <div class="
            message
            <?php
            echo $mensaje['remitente']
            === 'usuario'
            ? 'user-message'
            : 'ai-message';
            ?>
        ">

            <?php if(
                $mensaje['remitente']
                === 'ia'
            ): ?>

                <div class="avatar-ai">
                    IA
                </div>

            <?php endif; ?>

            <div class="bubble">

                <?php
                echo nl2br(
                    htmlspecialchars(
                        $mensaje['mensaje']
                    )
                );
                ?>

            </div>

        </div>

    <?php endforeach; ?>

</div>

                <!-- INPUT -->
                <form
                    id="chatForm"
                    class="chat-form">
                    <input
type="hidden"
id="conversacion_id"
name="conversacion_id"
value="<?php echo $conversacionActual; ?>">

                    <textarea
                        id="mensaje"
                        name="mensaje"
                        placeholder="Escribe tu mensaje..."
                        required
                    ></textarea>

                    <button type="submit">
                        Enviar
                    </button>

                </form>

                <p class="aviso-chat">
                    La IA puede cometer errores y no sustituye
                    la orientación profesional.
                </p>

            </section>

        </div>

    </main>

</div>


<script>

const form = document.getElementById("chatForm");
const input = document.getElementById("mensaje");
const chatMessages = document.getElementById("chatMessages");
const conversacionId = document.getElementById("conversacion_id");
const estadoVacio = document.getElementById("estadoVacio");

form.addEventListener(
    "submit",
    async function(e){

        e.preventDefault();

        const texto = input.value.trim();

        if(texto === ""){
            return;
        }

        /*
        ======================================
        CREAR CONVERSACIÓN AUTOMÁTICAMENTE
        ======================================
        */

        if(conversacionId.value === "0"){

            try{

                const nuevaConv = await fetch(
                    "../chat/nueva_conversacion.php"
                );

                const datosConversacion =
                await nuevaConv.json();

                if(datosConversacion.success){

                    conversacionId.value =
                    datosConversacion.conversacion_id;

                    window.history.replaceState(
                        {},
                        "",
                        "chat.php?conv=" +
                        datosConversacion.conversacion_id
                    );

                }else{

                    alert(
                        "No se pudo crear la conversación."
                    );

                    return;
                }

            }catch(error){

                console.error(error);

                alert(
                    "Error al crear la conversación."
                );

                return;
            }
        }

        /*
        ======================================
        PREPARAR INTERFAZ
        ======================================
        */

        estadoVacio.style.display = "none";
        chatMessages.style.display = "flex";

        /*
        ======================================
        MOSTRAR MENSAJE USUARIO
        ======================================
        */

        chatMessages.innerHTML += `
            <div class="message user-message">

                <div class="bubble">
                    ${texto}
                </div>

            </div>
        `;

        chatMessages.scrollTop =
        chatMessages.scrollHeight;

        input.value = "";

        /*
        ======================================
        MENSAJE TEMPORAL
        ======================================
        */

        const thinkingId =
        "ia_" + Date.now();

        chatMessages.innerHTML += `
            <div
                class="message ai-message"
                id="${thinkingId}"
            >

                <div class="avatar-ai">
                    IA
                </div>

                <div class="bubble">
                    Pensando...
                </div>

            </div>
        `;

        chatMessages.scrollTop =
        chatMessages.scrollHeight;

        /*
        ======================================
        ENVIAR AL SERVIDOR
        ======================================
        */

        try{

            const datos =
            new FormData();

            datos.append(
                "conversacion_id",
                conversacionId.value
            );

            datos.append(
                "mensaje",
                texto
            );

            const respuesta =
            await fetch(
                "../chat/guardar_mensaje.php",
                {
                    method:"POST",
                    body:datos
                }
            );

            const textoRespuesta =
await respuesta.text();

console.log(textoRespuesta);

const resultado =
JSON.parse(textoRespuesta);


            if(resultado.success){

                document.getElementById(
                    thinkingId
                ).remove();

                chatMessages.innerHTML += `
                    <div class="message ai-message">

                        <div class="avatar-ai">
                            IA
                        </div>

                        <div class="bubble">
                            ${resultado.respuesta_ia}
                        </div>

                    </div>
                `;

            }else{

                document.getElementById(
                    thinkingId
                ).remove();

                chatMessages.innerHTML += `
                    <div class="message ai-message">

                        <div class="avatar-ai">
                            IA
                        </div>

                        <div class="bubble">
                            Error:
                            ${resultado.mensaje}
                        </div>

                    </div>
                `;
            }

            chatMessages.scrollTop =
            chatMessages.scrollHeight;

        }catch(error){

            console.error(error);

            const elemento =
            document.getElementById(
                thinkingId
            );

            if(elemento){

                elemento.remove();

            }

            chatMessages.innerHTML += `
                <div class="message ai-message">

                    <div class="avatar-ai">
                        IA
                    </div>

                    <div class="bubble">
                        Error al conectar con el servidor.
                    </div>

                </div>
            `;
        }

    }
);

/*
======================================
ELIMINAR CONVERSACIÓN
======================================
*/

async function eliminarConversacion(id){

    if(
        !confirm(
            "¿Eliminar esta conversación?"
        )
    ){
        return;
    }

    try{

        const datos =
        new FormData();

        datos.append(
            "conversacion_id",
            id
        );

        const respuesta =
        await fetch(
            "../chat/eliminar_conversacion.php",
            {
                method:"POST",
                body:datos
            }
        );

        const textoRespuesta =
await respuesta.text();

console.log(textoRespuesta);

const resultado =
JSON.parse(textoRespuesta);
        if(resultado.success){

            location.href =
            "chat.php";

        }

    }catch(error){

        console.error(error);

        alert(
            "No se pudo eliminar la conversación."
        );

    }

}

/*
======================================
SUGERENCIAS
======================================
*/

document
.querySelectorAll(".sugerencia-btn")
.forEach(btn => {

    btn.addEventListener(
        "click",
        function(){

            input.value =
            this.textContent.trim();

            input.focus();

        }
    );

});

</script>

</body>
</html>

</body>
</html>