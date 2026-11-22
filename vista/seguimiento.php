<?php

session_start();

if(!isset($_SESSION['usuario_id'])){
    header("Location: index.php");
    exit();
}

require_once("../config/database.php");

$idUsuario = $_SESSION['usuario_id'];

/* USUARIO */

$sqlUsuario = "
SELECT nombre
FROM usuarios
WHERE id = ?
";

$stmt = $conn->prepare($sqlUsuario);
$stmt->bind_param("i",$idUsuario);
$stmt->execute();

$usuario =
$stmt->get_result()
->fetch_assoc();

$nombre =
$usuario['nombre'];

/* RESUMEN */

$sqlPromedio = "

SELECT

AVG(nivel) AS promedio,
COUNT(*) AS total

FROM seguimiento_animo

WHERE usuario_id = ?

";

$stmtPromedio =
$conn->prepare($sqlPromedio);

$stmtPromedio->bind_param(
    "i",
    $idUsuario
);

$stmtPromedio->execute();

$resumen =
$stmtPromedio
->get_result()
->fetch_assoc();

$promedio =
round(
    $resumen['promedio'] ?? 0,
    1
);

$dias =
$resumen['total'] ?? 0;

/* HISTORIAL */

$sqlHistorial = "

SELECT *

FROM seguimiento_animo

WHERE usuario_id = ?

ORDER BY fecha_registro ASC

";

$stmtHistorial =
$conn->prepare($sqlHistorial);

$stmtHistorial->bind_param(
    "i",
    $idUsuario
);

$stmtHistorial->execute();

$historial =
$stmtHistorial->get_result();

$labelsGrafica = [];
$datosGrafica = [];

mysqli_data_seek($historial, 0);

while($fila = $historial->fetch_assoc()){

    $labelsGrafica[] = date(
        "d/m",
        strtotime($fila['fecha_registro'])
    );

    $datosGrafica[] = intval(
        $fila['nivel']
    );

}

mysqli_data_seek($historial, 0);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>MindEdu - Seguimiento</title>
  <link rel="stylesheet" href="../css/seguimiento.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <h2>MindEdu</h2>
      <nav>
        <ul>
          <li><a href="dashboard.php">Inicio</a></li>
          <li><a href="recursos.php">Recursos</a></li>
          <li><a href="relajacion.php">Relajación</a></li>
           <li><a href="seguimiento.php" class="active">Seguimiento</a></li>
          <li><a href="chat.php">Chat IA</a></li>
          <li><a href="perfil.php">Perfil</a></li>
          <li><a href="configuracion.php">Configuración</a></li>
        </ul>
      </nav>
      <div class="support">
        <p>No tienes que hacerlo a solas. Busca apoyo en una persona de confianza o profesional.</p>
        <a href="#">Conocer opciones</a>
      </div>
      <div class="user">

            <strong><?php echo htmlspecialchars($nombre); ?></strong>

            <br>

            <small>Sesión iniciada</small>

            <p>
                <a href="../config/logout.php">
                    Cerrar sesión
                </a>
        </div>        
    </aside>

    <!-- Main content -->
   <main class="main">

    <!-- TOPBAR -->

    <div class="topbar">

        <div class="breadcrumb">
            Mi espacio / Seguimiento
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

    <!-- CABECERA -->

    <section class="hero">

        <span class="section-label">
            ESCUCHARTE TAMBIÉN ES CUIDARTE
        </span>

        <h1>
            Conoce el ritmo de tus emociones
        </h1>

        <p>
            Un registro sencillo para mirar tu evolución
            con curiosidad, no con juicio.
        </p>

    </section>

    <!-- FILA SUPERIOR -->

    <section class="tracking-top">

        <!-- REGISTRO DIARIO -->

        <section class="daily-card">

            <div class="daily-header">

                <h2>
                    Hoy,
                    <?php echo date('d \d\e F'); ?>
                </h2>

                <span class="draft-badge">

                    Sin guardar

                </span>

            </div>

            <p>
                ¿Cómo te sientes en este momento?
            </p>

            <form
                action="../config/guardar_estado.php"
                method="POST"
            >

                <div class="mood-options">

                    <label class="mood-item">

                        <input
                            type="radio"
                            name="nivel"
                            value="1"
                            required
                        >

                        😞

                        <span>
                            Muy bajo
                        </span>

                    </label>

                    <label class="mood-item">

                        <input
                            type="radio"
                            name="nivel"
                            value="2"
                        >

                        ☹️

                        <span>
                            Bajo
                        </span>

                    </label>

                    <label class="mood-item">

                        <input
                            type="radio"
                            name="nivel"
                            value="3"
                        >

                        😐

                        <span>
                            En calma
                        </span>

                    </label>

                    <label class="mood-item">

                        <input
                            type="radio"
                            name="nivel"
                            value="4"
                        >

                        🙂

                        <span>
                            Bien
                        </span>

                    </label>

                    <label class="mood-item">

                        <input
                            type="radio"
                            name="nivel"
                            value="5"
                        >

                        😄

                        <span>
                            Muy bien
                        </span>

                    </label>

                </div>

                <div id="selectedMood">

                    Selecciona una opción

                </div>

                <p>

                    ¿Quieres contar algo más?
                    (opcional)

                </p>

                <textarea

                    name="nota"

                    placeholder="Escribe qué ha influido en tu día..."

                ></textarea>

                <button
                    type="submit"
                    class="save-btn"
                >
                    Guardar registro de hoy
                </button>

            </form>

            <small>

                Solo un registro por día.
                Después de guardarlo podrás actualizarlo.

            </small>

        </section>

        <!-- RESUMEN -->

        <section class="weekly-card">

            <div class="week-header">

                <h2>
                    Tu última semana
                </h2>

                <span class="days-badge">

                    <?php echo $dias; ?>

                    días

                </span>

            </div>

            <div class="stats">

                <div>

                    <span class="big-number">

                        <?php echo $promedio; ?>

                    </span>

                    <p>
                        Promedio emocional
                    </p>

                </div>

                <div>

                    <span class="big-number">

                        <?php echo $dias; ?>

                    </span>

                    <p>
                        Días registrados
                    </p>

                </div>

            </div>

            <div class="chart">

    <canvas id="moodChart"></canvas>

</div>

        </section>

    </section>

    <!-- FILA INFERIOR -->

    <section class="tracking-bottom">

        <!-- HISTORIAL -->

        <section class="history-card">

            <div class="history-header">

                <h2>
                    Tu historial reciente
                </h2>

                <span>

                    <?php echo $dias; ?>

                    registros

                </span>

            </div>

            <table>

                <thead>

                    <tr>

                        <th>
                            FECHA
                        </th>

                        <th>
                            ÁNIMO
                        </th>

                        <th>
                            NIVEL
                        </th>

                        <th>
                            TU NOTA
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    mysqli_data_seek(
                        $historial,
                        0
                    );

                    while(
                        $fila =
                        $historial->fetch_assoc()
                    ):

                    ?>

                    <tr>

                        <td>

                            <?php

                            echo date(
                                "d M.",
                                strtotime(
                                    $fila['fecha_registro']
                                )
                            );

                            ?>

                        </td>

                        <td>

                            <?php
                            echo htmlspecialchars(
                                $fila['estado']
                            );
                            ?>

                        </td>

                        <td>

                            <?php
                            echo $fila['nivel'];
                            ?>/5

                        </td>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $fila['nota']
                            );

                            ?>

                        </td>

                    </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </section>

        <!-- MENSAJE LATERAL -->

        <aside class="insight-card">

            <div class="heart-icon">

                ♡

            </div>

            <h2>

                Tus emociones no son
                una calificación

            </h2>

            <p>

                Cada día puede sentirse distinto.
                Este historial te ayuda a reconocer
                cambios sin hacer diagnósticos.

            </p>

            <p>

                Las alertas preventivas aparecerán
                solo ante patrones de riesgo.

            </p>

            <a href="recursos.php">

                Explorar recursos de apoyo →

            </a>

        </aside>

    </section>

</main>

    <!-- Floating chat -->
    <button class="chat-btn">Tu espacio de escucha • IA</button>
  </div>
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const etiquetas =
<?php echo json_encode($labelsGrafica); ?>;

const niveles =
<?php echo json_encode($datosGrafica); ?>;

const ctx =
document.getElementById("moodChart");

new Chart(ctx, {

    type: "line",

    data: {

        labels: etiquetas,

        datasets: [{

            label: "Estado de ánimo",

            data: niveles,

            borderColor: "#295c4a",

            backgroundColor: "#295c4a",

            pointBackgroundColor: "#295c4a",

            pointBorderColor: "#295c4a",

            pointRadius: 6,

            pointHoverRadius: 8,

            borderWidth: 3,

            tension: 0.4,

            fill: false

        }]

    },

    options: {

        responsive: true,

        maintainAspectRatio: false,

        plugins: {

            legend: {
                display: false
            }

        },

        scales: {

            x: {

                title: {

                    display: true,

                    text: "Fecha de registro"

                },

                ticks: {

                    maxRotation: 45,
                    minRotation: 45

                }

            },

            y: {

                min: 1,

                max: 5,

                ticks: {

                    stepSize: 1,

                    callback: function(value){

                        const estados = {
                            1: "Muy bajo",
                            2: "Bajo",
                            3: "En calma",
                            4: "Bien",
                            5: "Muy bien"
                        };

                        return estados[value];

                    }

                }

            }

        }

    }

});

</script>
</body>
</html>
