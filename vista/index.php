<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MindEdu - Login</title>

    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

<div class="container">

    <div class="left-panel">
        <h1>MindEdu</h1>

        <h2>UN MOMENTO PARA TI</h2>

        <p>
            Tu bienestar empieza con una pausa.
            Conoce tus emociones, encuentra calma
            y construye hábitos a tu ritmo.
        </p>
    </div>

    <div class="right-panel">

        <h2>QUÉ BUENO VERTE</h2>

        <form action="../config/login.php" method="POST">

            <label>Correo electrónico</label>

            <input
                type="email"
                name="email"
                required
            >

            <label>Contraseña</label>

            <input
                type="password"
                name="password"
                required
            >

            <button type="submit">
                Iniciar sesión
            </button>

        </form>

        <p>
            ¿No tienes cuenta?
            <a href="registro.php">Regístrate</a>
        </p>

    </div>

</div>

</body>
</html>