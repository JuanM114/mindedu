<?php

session_start();

require_once("database.php");

$email =
$_POST['email'] ?? '';

$password =
$_POST['password'] ?? '';

$sql = "

SELECT *

FROM administradores

WHERE email = ?

LIMIT 1

";

$stmt =
$conn->prepare($sql);

$stmt->bind_param(
    "s",
    $email
);

$stmt->execute();

$resultado =
$stmt->get_result();

if($resultado->num_rows === 1){

    $admin =
    $resultado->fetch_assoc();

    if(
        $password === "123"
    ){

        $_SESSION['admin_id'] =
        $admin['id'];

        $_SESSION['admin_nombre'] =
        $admin['nombre'];

        header(
            "Location: ../admin/dashboard_admin.php"
        );

        exit();

    }

}

header(
    "Location: ../admin/login_admin.php?error=1"
);

exit();

?>