<?php

session_start();

if(!isset($_SESSION['admin_id'])){
    header("Location: login_admin.php");
    exit();
}

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

        MindEdu Admin - Ejercicios

    </title>

    <link
    rel="stylesheet"
    href="../css/admin.css"
    >

</head>

<body>

<div class="container">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <h2>

            MindEdu Admin

        </h2>

        <nav>

            <ul>

                <li>

                    <a href="dashboard_admin.php">

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

                    <a
                    href="subir_ejercicio.php"
                    class="active"
                    >

                        Ejercicios

                    </a>

                </li>

            </ul>

        </nav>

        <div class="support">

            <p>

                Gestión administrativa
                de usuarios, recursos
                y ejercicios de MindEdu.

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

    <!-- CONTENIDO -->

    <main class="admin-main">

        <div class="page-header">

            <h1>

                Agregar ejercicio

            </h1>

            <p>

                Crea nuevos ejercicios guiados
                para los usuarios de MindEdu.

            </p>

        </div>

        <div class="admin-form">

            <form

                action="../config/guardar_ejercicio.php"

                method="POST"

                enctype="multipart/form-data"

            >

                <label>

                    Título

                </label>

                <input

                    type="text"

                    name="titulo"

                    required

                >

                <label>

                    Descripción

                </label>

                <textarea

                    name="descripcion"

                    rows="4"

                    required

                ></textarea>

                <label>

                    Categoría

                </label>

                <select name="categoria">

                    <option>

                        Respiración

                    </option>

                    <option>

                        Meditación

                    </option>

                    <option>

                        Calma

                    </option>

                    <option>

                        Relajación

                    </option>

                    <option>

                        Atención plena

                    </option>

                </select>

                <label>

                    Duración (minutos)

                </label>

                <input

                    type="number"

                    name="duracion"

                    min="1"

                    required

                >

                <label>

                    Imagen

                </label>

                <input

                    type="file"

                    name="imagen"

                    accept="image/*"

                >

                <label>

                    Audio guiado

                </label>

                <input

                    type="file"

                    name="audio"

                    accept="audio/*"

                >

                <label>

                    Instrucciones

                </label>

                <textarea

                    name="instrucciones"

                    rows="8"

                ></textarea>

                <button type="submit">

                    Guardar ejercicio

                </button>

            </form>

        </div>

    </main>

</div>

</body>

</html>