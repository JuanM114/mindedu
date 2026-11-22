<?php

session_start();

require_once("database.php");

$usuario_id = $_SESSION['usuario_id'];

$nivel = intval($_POST['nivel']);

$nota = trim($_POST['nota']);

$estados = [

    1 => 'Muy bajo',
    2 => 'Bajo',
    3 => 'En calma',
    4 => 'Bien',
    5 => 'Muy bien'

];

$estado = $estados[$nivel];

$sql = "

INSERT INTO seguimiento_animo
(

usuario_id,
nivel,
estado,
nota

)

VALUES
(

?,
?,
?,
?

)

";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "iiss",
    $usuario_id,
    $nivel,
    $estado,
    $nota
);

$stmt->execute();

header(
    "Location: ../vista/seguimiento.php"
);

exit();

?>