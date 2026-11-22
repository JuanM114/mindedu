<?php

session_start();

if(!isset($_SESSION['usuario_id'])){
    header("Location: index.php");
    exit();
}

require_once("../config/database.php");

$idUsuario = $_SESSION['usuario_id'];

$sql = "SELECT *
        FROM usuarios
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $idUsuario
);

$stmt->execute();

$resultado = $stmt->get_result();

$usuario = $resultado->fetch_assoc();

$nombre = $usuario['nombre'];
$email = $usuario['email'];
$edad = $usuario['edad'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>MindEdu - Perfil</title>
  <link rel="stylesheet" href="../css/perfil.css">
</head>
<body>
  <div class="container">

    <aside class="sidebar">
      <h2>MindEdu</h2>
      <nav>
          <ul>
          <li><a href="dashboard.php">Inicio</a></li>
          <li><a href="recursos.php">Recursos</a></li>
          <li><a href="relajacion.php">Relajación</a></li>
          <li><a href="seguimiento.php">Seguimiento</a></li>
          <li><a href="chat.php">Chat IA</a></li>
          <li><a href="perfil.php" class="active">Perfil</a></li>
          <li><a href="configuracion.php">Configuración</a></li>
        </ul>
      </nav>
      <div class="support">
        <p>Si necesitas apoyo, recuerda que siempre puedes buscar ayuda profesional.</p>
      </div>
      <div class="user">
        <p>
    <strong>
        <?php echo htmlspecialchars($nombre); ?>
    </strong>
</p>
        <small>Sesión iniciada</small>
        <p>
                <a href="../config/logout.php">
                    Cerrar sesión
                </a>
            </p>
      </div>
    </aside>


    <main class="main">
      <header class="top-bar">
       <span class="date">
                    <?php echo date('d/m/Y'); ?>
                </span>
       <button class="space-btn">
                    Espacio personal
                </button>
      </header>

      <h1>Un perfil tan único como tú</h1>
      <p>Actualiza tus datos y preferencias.</p>

      <form action="../config/actualizar_perfil.php" method="POST">

<div class="profile-layout">

    <!-- COLUMNA IZQUIERDA -->

    <div class="left-column">

        <div class="profile-card">

            <div class="profile-header">

                <div class="profile-info">

                    <div class="avatar">
                        <?php echo strtoupper(substr($nombre,0,1)); ?>
                    </div>

                    <div class="account-info">
                        <h2><?php echo htmlspecialchars($nombre); ?></h2>
                        <p>Tu cuenta personal · Sesión iniciada</p>
                    </div>

                </div>

                <span class="profile-badge">
                    Mi perfil
                </span>

            </div>

            <h2>Datos personales</h2>

            <form action="../config/actualizar_perfil.php" method="POST">

                <label for="nombre">Nombre completo *</label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="<?php echo htmlspecialchars($nombre); ?>"
                    required
                >

                <div class="form-row">

                    <div>

                        <label for="email">
                            Correo electrónico *
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?php echo htmlspecialchars($email); ?>"
                            required
                        >

                    </div>

                    <div>

                        <label for="edad">
                            Edad *
                        </label>

                        <input
                            type="number"
                            id="edad"
                            name="edad"
                            value="<?php echo $edad; ?>"
                            required
                        >

                    </div>

                </div>

                <small>
                    * Campos obligatorios.
                </small>

        </div>

        <!-- INTERESES -->

        <div class="interests-card">

            <h2>Lo que quieres cuidar</h2>

            <p>
                Tus intereses ayudan a orientar los recursos sugeridos.
                Puedes elegir varios.
            </p>

            <div class="checkbox-group">

                <label>
                    <input
                        type="checkbox"
                        name="cuidado[]"
                        value="estrés"
                    >
                    Manejo del estrés
                </label>

                <label>
                    <input
                        type="checkbox"
                        name="cuidado[]"
                        value="respiración"
                    >
                    Respiración
                </label>

                <label>
                    <input
                        type="checkbox"
                        name="cuidado[]"
                        value="sueño"
                    >
                    Sueño
                </label>

                <label>
                    <input
                        type="checkbox"
                        name="cuidado[]"
                        value="autoestima"
                    >
                    Autoestima
                </label>

                <label>
                    <input
                        type="checkbox"
                        name="cuidado[]"
                        value="emociones"
                    >
                    Emociones
                </label>

            </div>

            <div class="buttons">

                <button
                    type="submit"
                    class="save-btn"
                >
                    Guardar cambios
                </button>

                <button
                    type="reset"
                    class="cancel-btn"
                >
                    Cancelar
                </button>

            </div>

            </form>

        </div>

    </div>

    <!-- COLUMNA DERECHA -->

    <div class="right-column">

        <section class="accessibility">

            <h2>A tu manera</h2>

            <div class="option-row">
                <span>Texto más grande</span>
                <input type="checkbox" id="bigText">
            </div>

            <div class="option-row">
                <span>Alto contraste</span>
                <input
                    type="checkbox"
                    id="highContrast"
                >
            </div>

            <div class="option-row">
                <span>Reducir movimiento</span>
                <input
                    type="checkbox"
                    id="reduceMotion"
                    checked
                >
            </div>

        </section>

        <!-- PRIVACIDAD -->

        <section class="privacy">

            <h2>
                Tú decides sobre tus datos
            </h2>

            <p>
                Consulta tu consentimiento y tus
                derechos de acceso, corrección y
                eliminación.
            </p>

            <a href="#">
                Ver privacidad y consentimiento
            </a>

        </section>

        <!-- ELIMINAR CUENTA -->

        <section class="delete-account">

            <h2>Eliminar mi cuenta</h2>

            <p>
                Antes de continuar deberás confirmar
                esta acción.
            </p>

           <button
    type="button"
    class="delete-btn"
    id="deleteAccountBtn"
>
    Solicitar eliminación
</button>

        </section>

    </div>

</div>


  
</body>

<button class="chat-btn">

    ✨ Tu espacio de escucha • IA

</button>
<script>

document.getElementById("bigText").addEventListener("change", function() {
    document.body.classList.toggle(
        "big-text",
        this.checked
    );
});

document.getElementById("highContrast").addEventListener("change", function() {
    document.body.classList.toggle(
        "high-contrast",
        this.checked
    );
});

document.getElementById("reduceMotion").addEventListener("change", function() {
    document.body.classList.toggle(
        "reduce-motion",
        this.checked
    );
});

window.addEventListener("load", () => {

    const bigText =
    document.getElementById("bigText");

    const highContrast =
    document.getElementById("highContrast");

    const reduceMotion =
    document.getElementById("reduceMotion");

    document.body.classList.toggle(
        "big-text",
        bigText.checked
    );

    document.body.classList.toggle(
        "high-contrast",
        highContrast.checked
    );

    document.body.classList.toggle(
        "reduce-motion",
        reduceMotion.checked
    );

});

</script>
<script>

document
.getElementById("deleteAccountBtn")
.addEventListener("click", function(){

    const confirmar = confirm(
        "¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer."
    );

    if(confirmar){

        window.location.href =
        "../config/eliminar_cuenta.php";

    }

});

</script>

</html>
