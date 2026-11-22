<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>

        MindEdu Admin

    </title>

    <link
        rel="stylesheet"
        href="../css/login_admin.css"
    >

</head>

<body>

<div class="container">

    <div class="left-panel">

        <h1>

            MindEdu Admin

        </h1>

        <h2>

            PANEL DE ADMINISTRACIÓN

        </h2>

        <p>

            Centro de control
            de la plataforma.

        </p>

        <p>

            Gestiona usuarios,
            recursos educativos,
            ejercicios guiados
            y supervisa la actividad
            general de MindEdu.

        </p>

    </div>

    <div class="right-panel">

        <h2>

            ADMIN

        </h2>

        <?php if(isset($_GET['error'])): ?>

            <div class="error-message">

                Correo o contraseña incorrectos.

            </div>

        <?php endif; ?>

        <form
            action="../config/login_admin.php"
            method="POST"
        >

            <label>

                Correo electrónico

            </label>

            <input

                type="email"

                name="email"

                placeholder="admin@admin.com"

                required

            >

            <label>

                Contraseña

            </label>

            <input

                type="password"

                name="password"

                placeholder="••••••••"

                required

            >

            <button
                type="submit"
            >

                Ingresar al panel

            </button>

        </form>

        <p>

            Volver al acceso principal

            <a href="../index.php">

                Iniciar sesión

            </a>

        </p>

    </div>

</div>

</body>
</html>