<?php

require_once "database.php";

$nombre = trim($_POST['nombre']);
$email = trim($_POST['email']);
$edad = intval($_POST['edad']);

$password = $_POST['password'];
$confirmar = $_POST['confirmar'];

if($password !== $confirmar){

    die("Las contraseñas no coinciden");

}

$sqlVerificar = "SELECT id FROM usuarios WHERE email = ?";

$stmtVerificar = $conn->prepare($sqlVerificar);

$stmtVerificar->bind_param("s", $email);

$stmtVerificar->execute();

$resultado = $stmtVerificar->get_result();

if($resultado->num_rows > 0){

    die("Este correo ya está registrado");

}

$passwordHash = password_hash(
    $password,
    PASSWORD_DEFAULT
);

$sql = "INSERT INTO usuarios
(nombre,email,edad,password)
VALUES
(?,?,?,?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssis",
    $nombre,
    $email,
    $edad,
    $passwordHash
);

if($stmt->execute()){

    header("Location: ../vista/index.php");
    exit();

}else{

    echo "Error al registrar usuario";

}

?>