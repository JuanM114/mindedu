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

        MindEdu Admin - Recursos

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

                    <a
                    href="subir_recurso.php"
                    class="active"
                    >

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

                Agregar recurso

            </h1>

            <p>

                Publica nuevos recursos
                educativos para los usuarios
                de MindEdu.

            </p>

        </div>

        <div class="admin-form">

            <form

                action="../config/guardar_recurso.php"

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

                    Categoría

                </label>

                <select name="categoria">

                    <option>

                        Respiración

                    </option>

                    <option>

                        Manejo del estrés

                    </option>

                    <option>

                        Sueño

                    </option>

                    <option>

                        Autoestima

                    </option>

                    <option>

                        Emociones

                    </option>

                </select>

                <label>

                    Descripción

                </label>

                <textarea

                    name="descripcion"

                    rows="5"

                ></textarea>

                <label>

                    Formato

                </label>

                <select name="formato">

                    <option>

                        Guía

                    </option>

                    <option>

                        Video

                    </option>

                    <option>

                        Artículo

                    </option>

                </select>

                <label>

                    Duración

                </label>

                <input

                    type="text"

                    name="duracion"

                    placeholder="5 min"

                >

                <label>

                    Imagen

                </label>

                <input

                    type="file"

                    name="imagen"

                    accept="image/*"

                    required

                >

                <label>

                    Archivo

                </label>

                <input

                    type="file"

                    name="archivo"

                    required

                >

                <button type="submit">

                    Guardar recurso

                </button>

            </form>

        </div>

    </main>

</div>

</body>

</html>