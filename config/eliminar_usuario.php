<?php

session_start();

if(!isset($_SESSION['admin_id'])){
    header("Location: login_admin.php");
    exit();
}

require_once("../config/database.php");

$idUsuario =
intval($_GET['id'] ?? 0);

if($idUsuario <= 0){

    header(
        "Location: usuarios.php"
    );

    exit();
}

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

$stmt =
$conn->prepare("

DELETE FROM usuarios

WHERE id = ?

");

$stmt->bind_param(
    "i",
    $idUsuario
);

$stmt->execute();

/* REDIRECCIÓN */

header(
    "Location: usuarios.php?eliminado=1"
);

exit();

?>