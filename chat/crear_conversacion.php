<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    exit();
}

require_once("../config/database.php");

$idUsuario = $_SESSION['usuario_id'];

$titulo = "Nueva conversación";

$sql = "
INSERT INTO conversaciones (
    usuario_id,
    titulo
)
VALUES (
    ?,
    ?
)
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "is",
    $idUsuario,
    $titulo
);

$stmt->execute();

$idConversacion = $conn->insert_id;

header(
    "Location: ../vista/chat.php?conv=" .
    $idConversacion
);

exit();

?>