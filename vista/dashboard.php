<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../vista/index.php");
    exit();
}

require_once("../config/database.php");

$idUsuario = $_SESSION['usuario_id'];

$sql = "SELECT visitas
        FROM usuarios
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();

$resultado = $stmt->get_result();
$datos = $resultado->fetch_assoc();

$visitas = $datos['visitas'] ?? 0;
$nombre = $_SESSION['nombre'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MindEdu - Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<div class="container">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <h2>MindEdu</h2>

        <nav>
            <ul>
                <li><a href="dashboard.php">Inicio</a></li>
                <li><a href="recursos.php">Recursos</a></li>
                <li><a href="relajacion.php">Relajación</a></li>
                <li><a href="seguimiento.php">Seguimiento</a></li>
                <li><a href="chat.php">Chat IA</a></li>
                <li><a href="perfil.php">Perfil</a></li>
                <li><a href="configuracion.php">Configuración</a></li>
            </ul>
        </nav>

        <div class="support">
            <p>
                No tienes que hacerlo solo.
            </p>

            <p>
                Busca apoyo en una persona de confianza o profesional.
            </p>
        </div>

        <div class="user">

            <strong><?php echo htmlspecialchars($nombre); ?></strong>

            <br>

            <small>Sesión iniciada</small>

            <p>
                <a href="../config/logout.php">
                    Cerrar sesión
                </a>
            </p>

        </div>

    </aside>

    <!-- CONTENIDO -->
    <main class="main">

        <!-- BARRA SUPERIOR -->

        <div class="topbar">

            <div class="breadcrumb">
                Mi espacio / Inicio
            </div>

            <div class="topbar-right">

                <span class="date">
                    <?php echo date('d/m/Y'); ?>
                </span>

                <button class="space-btn">
                    Espacio personal
                </button>

            </div>

        </div>

        <!-- BIENVENIDA -->

        <div class="welcome">

            <span class="section-label">
                TU BIENESTAR, A TU RITMO
            </span>

            <h1>
                Hola, <?php echo htmlspecialchars($nombre); ?>.
                Este momento es para ti.
            </h1>

            <p>
                No necesitas tener todo resuelto.
                Empieza por escucharte un poco.
            </p>

        </div>

        <!-- ÁNIMO + RESPIRACIÓN -->

        <div class="hero-section">

            <div class="mood-card">

                <div class="mood-header">
                    <h2>¿Cómo te sientes hoy?</h2>
                    
                </div>

                <p>No hay datos registrados hoy.</p>

                <div class="mood-options">

                    <button>😞<br>Muy bajo</button>
                    <button>☹️<br>Bajo</button>
                    <button>😐<br>En calma</button>
                    <button>🙂<br>Bien</button>
                    <button>😄<br>Muy bien</button>

                </div>

                <a class="register-mood" href="seguimiento.php">
                    Registrar mi ánimo
</a>

            </div>

            <div class="breathing-card">

                <img
                    src="../img/respiracion.png"
                    alt="Respiración"
                >

                <h2>Vuelve a tu respiración</h2>

                <p>
                    Cinco minutos pueden ser un buen comienzo
                    para bajar el ritmo.
                </p>

                <a href="relajacion.php">
                    Hacer una pausa de 5 min →
                </a>

            </div>

        </div>

        <!-- ACCESOS RÁPIDOS -->

        <section class="cards">

            <a href="perfil.php" class="dashboard-card">
                <div class="card-content">
                    <h3>Mi perfil</h3>
                    <p>Un espacio a tu medida</p>
                </div>
            </a>

            <a href="recursos.php" class="dashboard-card">
                <div class="card-content">
                    <h3>Recursos</h3>
                    <p>Ideas para cuidarte</p>
                </div>
            </a>

            <a href="relajacion.php" class="dashboard-card">
                <div class="card-content">
                    <h3>Relajación</h3>
                    <p>Respira y reconecta</p>
                </div>
            </a>

            <a href="seguimiento.php" class="dashboard-card">
                <div class="card-content">
                    <h3>Seguimiento</h3>
                    <p>Conoce tu evolución</p>
                </div>
            </a>

            <a href="configuracion.php" class="dashboard-card">
                <div class="card-content">
                    <h3>Configuración</h3>
                    <p>Ajusta tu experiencia</p>
                </div>
            </a>

        </section>

        <!-- ESTADÍSTICAS Y RECOMENDACIONES -->

        <div class="dashboard-bottom">

            <section class="stats-card">

                <h2>Cada registro cuenta</h2>

                <p class="week-text">
                    Bienvenido nuevamente a MindEdu
                </p>

                <div class="stat-values">

                    <div>
                        <span class="big-number">
                            <?php echo $visitas; ?>
                        </span>

                        <p>ingresos realizados</p>
                    </div>

                    <div>

                        <span class="big-number">
                            3,3
                        </span>

                        <span>/5</span>

                        <p>ánimo promedio</p>

                    </div>

                </div>

                <p class="stat-description">
                    Una mirada a tus emociones,
                    no una evaluación de quién eres.
                </p>

                <a
                    href="seguimiento.php"
                    class="dashboard-link"
                >
                    Ver mi seguimiento →
                </a>

            </section>

            <section class="recommendations-card">

                <div class="recommendations-header">

                    <h2>Elegido para ti</h2>

                    <span class="ia-badge">
                        ✨ Sugerencias IA
                    </span>

                </div>

                <p>
                    Según tus intereses:
                    manejo del estrés y respiración.
                </p>

                <div class="resource-item">

                    <div>
                        <h3>Respira consciente</h3>
                        <p>Guía · Respiración · 5 min</p>
                    </div>

                    <span>↗</span>

                </div>

                <div class="resource-item">

                    <div>
                        <h3>Una pausa entre tareas</h3>
                        <p>Video · Manejo del estrés · 8 min</p>
                    </div>

                    <span>↗</span>

                </div>

                <a
                    href="recursos.php"
                    class="dashboard-link"
                >
                    Explorar toda la biblioteca →
                </a>

            </section>

        </div>

    </main>

</div>

<button class="chat-btn">
    ✨ Tu espacio de escucha • IA
</button>

</body>
</html>