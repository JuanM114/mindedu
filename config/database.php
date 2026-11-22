<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "mindedu";

$conn = new mysqli(
    $host,
    $user,
    $password,
    $database
);

if($conn->connect_error){
    die("Error de conexión");
}

?>