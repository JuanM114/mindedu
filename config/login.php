<?php

session_start();

require_once "database.php";

$email = trim($_POST['email']);
$password = $_POST['password'];

$sql = "SELECT * FROM usuarios WHERE email = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0){

    $usuario = $result->fetch_assoc();

    if(password_verify($password, $usuario['password'])){

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];

        header("Location: ../vista/dashboard.php");
        exit();

    } else {

        echo "Contraseña incorrecta";

    }
    
    $updateVisitas = $conn->prepare(
    "UPDATE usuarios
    SET visitas = visitas + 1
    WHERE id = ?"
);

$updateVisitas->bind_param(
    "i",
    $usuario['id']
);

$updateVisitas->execute();

} else {

    echo "Usuario no encontrado";

}