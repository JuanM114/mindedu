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

$conversacionId = isset($_POST['conversacion_id'])
    ? (int) $_POST['conversacion_id']
    : 0;

if ($conversacionId <= 0) {

    echo json_encode([
        "success" => false,
        "mensaje" => "ID de conversación inválido"
    ]);

    exit();
}

/*
|--------------------------------------------------------------------------
| Verificar que la conversación pertenece al usuario
|--------------------------------------------------------------------------
*/

$sqlVerificar = "
SELECT id
FROM conversaciones
WHERE id = ?
AND usuario_id = ?
";

$stmtVerificar = $conn->prepare($sqlVerificar);

$stmtVerificar->bind_param(
    "ii",
    $conversacionId,
    $idUsuario
);

$stmtVerificar->execute();

$resultado = $stmtVerificar->get_result();

if ($resultado->num_rows === 0) {

    echo json_encode([
        "success" => false,
        "mensaje" => "No tienes permiso para eliminar esta conversación"
    ]);

    exit();
}

/*
|--------------------------------------------------------------------------
| Eliminar conversación
|--------------------------------------------------------------------------
*/

$sqlEliminar = "
DELETE FROM conversaciones
WHERE id = ?
";

$stmtEliminar = $conn->prepare($sqlEliminar);

$stmtEliminar->bind_param(
    "i",
    $conversacionId
);

if ($stmtEliminar->execute()) {

    echo json_encode([
        "success" => true,
        "mensaje" => "Conversación eliminada correctamente"
    ]);

} else {

    echo json_encode([
        "success" => false,
        "mensaje" => "Error al eliminar la conversación"
    ]);
}

?>