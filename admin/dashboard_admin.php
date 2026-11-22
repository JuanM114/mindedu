<?php

session_start();

if(!isset($_SESSION['admin_id'])){
    header("Location: login_admin.php");
    exit();
}

require_once("../config/database.php");

/* ESTADÍSTICAS */

$totalUsuarios =
$conn->query("
SELECT COUNT(*) total
FROM usuarios
")->fetch_assoc()['total'];

$totalRecursos =
$conn->query("
SELECT COUNT(*) total
FROM recursos
")->fetch_assoc()['total'];

$totalEjercicios =
$conn->query("
SELECT COUNT(*) total
FROM ejercicios
")->fetch_assoc()['total'];

$totalSeguimientos =
$conn->query("
SELECT COUNT(*) total
FROM seguimiento_animo
")->fetch_assoc()['total'];

/* ÚLTIMOS USUARIOS */

$ultimosUsuarios =
$conn->query("
SELECT *
FROM usuarios
ORDER BY id DESC
LIMIT 5
");

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>

<title>

Dashboard Admin

</title>

<link
rel="stylesheet"
href="../css/admin.css"
>

</head>

<body>

<div class="container">

    <aside class="sidebar">

        <h2>

            MindEdu Admin

        </h2>

        <nav>

            <ul>

                <li>

                    <a
                        href="dashboard_admin.php"
                        class="active"
                    >

                        Dashboard

                    </a>

                </li>

                <li>

                    <a href="usuarios.php">

                        Usuarios

                    </a>

                </li>

                <li>

                    <a href="subir_recurso.php">

                        Recursos

                    </a>

                </li>

                <li>

                    <a href="subir_ejercicio.php">

                        Ejercicios

                    </a>

                </li>

            </ul>

        </nav>

        <div class="support">

            <p>

                Panel administrativo para la gestión
                de usuarios, recursos y ejercicios.

            </p>

        </div>

        <div class="user">

            <p>

                <strong>

                    <?php
                    echo $_SESSION['admin_nombre'];
                    ?>

                </strong>

            </p>

            <small>

                Administrador

            </small>

            <p>

                <a href="../config/logout_admin.php">

                    Cerrar sesión

                </a>

            </p>

        </div>

    </aside>

    <main class="admin-main">

        <div class="page-header">

            <h1>
                Panel Administrativo
            </h1>

            <p>
                Bienvenido,
                <?php echo $_SESSION['admin_nombre']; ?>
            </p>

        </div>

        <!-- TARJETAS -->

        <div class="stats-grid">

            <div class="stat-card">

                <h3>
                    Usuarios
                </h3>

                <span>
                    <?php echo $totalUsuarios; ?>
                </span>

            </div>

            <div class="stat-card">

                <h3>
                    Recursos
                </h3>

                <span>
                    <?php echo $totalRecursos; ?>
                </span>

            </div>

            <div class="stat-card">

                <h3>
                    Ejercicios
                </h3>

                <span>
                    <?php echo $totalEjercicios; ?>
                </span>

            </div>

            <div class="stat-card">

                <h3>
                    Registros emocionales
                </h3>

                <span>
                    <?php echo $totalSeguimientos; ?>
                </span>

            </div>

        </div>

        <!-- ACCIONES -->

        <div class="quick-actions">

            <a
                href="usuarios.php"
                class="action-card"
            >
                Gestionar usuarios
            </a>

            <a
                href="subir_recurso.php"
                class="action-card"
            >
                Administrar recursos
            </a>

            <a
                href="subir_ejercicio.php"
                class="action-card"
            >
                Administrar ejercicios
            </a>

        </div>

        <!-- ÚLTIMOS USUARIOS -->

        <div class="recent-users">

            <h2>
                Últimos usuarios registrados
            </h2>

            <table>

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Edad</th>

                    </tr>

                </thead>

                <tbody>

                <?php while($usuario = $ultimosUsuarios->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo $usuario['id']; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($usuario['nombre']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($usuario['email']); ?>
                        </td>

                        <td>
                            <?php echo $usuario['edad']; ?>
                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </main>

</div>

</body>
</html>