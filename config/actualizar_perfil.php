<?php

session_start();

require_once("database.php");

$idUsuario = $_SESSION['usuario_id'];

$nombre = trim($_POST['nombre']);
$email = trim($_POST['email']);
$edad = intval($_POST['edad']);

$sql = "UPDATE usuarios
        SET nombre = ?,
            email = ?,
            edad = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssii",
    $nombre,
    $email,
    $edad,
    $idUsuario
);

$stmt->execute();

$_SESSION['nombre'] = $nombre;

header("Location: ../vista/perfil.php");
exit();

?>