<?php

session_start();

if(!isset($_SESSION['usuario_id'])){
    header("Location: ../vista/index.php");
    exit();
}

require_once("database.php");

$idUsuario = $_SESSION['usuario_id'];

/* MENSAJES */

$conn->query("
DELETE FROM mensajes
WHERE conversacion_id IN (
    SELECT id
    FROM conversaciones
    WHERE usuario_id = $idUsuario
)
");

/* CONVERSACIONES */

$conn->query("
DELETE FROM conversaciones
WHERE usuario_id = $idUsuario
");

/* HISTORIAL CHAT */

$conn->query("
DELETE FROM historial_chat
WHERE usuario_id = $idUsuario
");

/* SEGUIMIENTO */

$conn->query("
DELETE FROM seguimiento_animo
WHERE usuario_id = $idUsuario
");

/* INTERESES */

$conn->query("
DELETE FROM intereses_usuario
WHERE usuario_id = $idUsuario
");

/* CONFIGURACIÓN */

$conn->query("
DELETE FROM configuracion_usuario
WHERE usuario_id = $idUsuario
");

/* USUARIO */

$sql = "
DELETE FROM usuarios
WHERE id = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $idUsuario
);


$stmt->execute();

/* CERRAR SESIÓN */

session_destroy();

header("Location: ../vista/index.php");

exit();

?>