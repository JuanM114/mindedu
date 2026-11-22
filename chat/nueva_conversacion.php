<?php

session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {

    echo json_encode([
        "success" => false
    ]);

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

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "conversacion_id" => $conn->insert_id
    ]);

    exit();
}

echo json_encode([
    "success" => false
]);

exit();

?>