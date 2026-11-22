<?php

session_start();

if(!isset($_SESSION['usuario_id'])){
    header("Location: index.php");
    exit();
}

require_once("../config/database.php");

$idUsuario = $_SESSION['usuario_id'];

$sqlUsuario = "
SELECT nombre
FROM usuarios
WHERE id = ?
";

$stmt = $conn->prepare($sqlUsuario);
$stmt->bind_param("i",$idUsuario);
$stmt->execute();

$usuario = $stmt->get_result()->fetch_assoc();

$nombre = $usuario['nombre'];


/* EJERCICIOS */

$sqlEjercicios = "
SELECT *
FROM ejercicios
ORDER BY id ASC
";

$ejercicios = $conn->query($sqlEjercicios);

?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">

<title>MindEdu - Relajación</title>

<link rel="stylesheet" href="../css/relajacion.css">

</head>

<body>

<div class="container">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <h2>MindEdu</h2>

        <nav>

            <ul>

                <li>
                    <a href="dashboard.php">
                        Inicio
                    </a>
                </li>

                <li>
                    <a href="recursos.php">
                        Recursos
                    </a>
                </li>

                <li>
                    <a href="relajacion.php" class="active">
                        Relajación
                    </a>
                </li>

                <li>
                    <a href="seguimiento.php">
                        Seguimiento
                    </a>
                </li>

                <li>
                    <a href="chat.php">
                        Chat IA
                    </a>
                </li>

                <li>
                    <a href="perfil.php">
                        Perfil
                    </a>
                </li>

                <li>
                    <a href="configuracion.php">
                        Configuración
                    </a>
                </li>

            </ul>

        </nav>

        <div class="support">

            <p>
                Regálate unos minutos para respirar
                antes de continuar el día.
            </p>

        </div>

        <div class="user">

            <strong>
                <?php echo htmlspecialchars($nombre); ?>
            </strong>

            <br>

            <small>
                Sesión iniciada
            </small>

            <p>

                <a href="../config/logout.php">
                    Cerrar sesión
                </a>

            </p>

        </div>

    </aside>

    <!-- CONTENIDO -->

   <main class="main">

    <!-- TOPBAR -->

    <div class="topbar">

        <div class="breadcrumb">
            Mi espacio / Relajación
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

    <!-- HERO -->

    <section class="hero">

        <span class="section-label">
            HAZ ESPACIO PARA LA CALMA
        </span>

        <h1>
            Una pausa, una respiración a la vez
        </h1>

        <p>
            Encuentra una postura cómoda.
            No hay prisa ni una forma perfecta de hacerlo.
        </p>

    </section>

    <!-- LAYOUT PRINCIPAL -->

    <section class="relax-layout">

        <!-- PANEL IZQUIERDO -->

        <aside class="exercise-panel">

            <div class="exercise-header">

                <h2>
                    Elige tu pausa
                </h2>

                <span>
                    <?php echo $ejercicios->num_rows; ?>
                    ejercicios
                </span>

            </div>

            <ul class="exercise-list">

                <?php

                $primero = true;

                $ejercicios->data_seek(0);

                while($ejercicio = $ejercicios->fetch_assoc()):

                ?>

                <li

                class="exercise-card <?php echo $primero ? 'active' : ''; ?>"

                data-id="<?php echo $ejercicio['id']; ?>"

                data-name="<?php echo htmlspecialchars($ejercicio['titulo']); ?>"

                data-duration="<?php echo $ejercicio['duracion']; ?>"

                >

                    <h3>

                        <?php
                        echo htmlspecialchars(
                            $ejercicio['titulo']
                        );
                        ?>

                    </h3>

                    <p>

                        <?php
                        echo htmlspecialchars(
                            $ejercicio['categoria']
                        );
                        ?>

                        ·

                        <?php
                        echo floor(
                            $ejercicio['duracion']/60
                        );
                        ?>

                        min

                    </p>

                </li>

                <?php

                $primero = false;

                endwhile;

                ?>

            </ul>

            <div class="exercise-warning">

                Finaliza el ejercicio en curso
                antes de comenzar otro.

            </div>

        </aside>

        <!-- PANEL DERECHO -->

        <section class="session-card">

            <div class="session-top">

                <span class="exercise-status">
                    ● Ejercicio en curso
                </span>

                <span>
                    Guía de voz activada
                </span>

            </div>

            <h2 id="exerciseTitle">

                Selecciona un ejercicio

            </h2>

            <p id="instructionText">

                Inhala 4 segundos · Exhala 6 segundos

            </p>

            <div class="circle-wrapper">

                <div
                    class="breathing-circle"
                    id="breathingCircle"
                >

                    <span id="stateText">

                        Exhala

                    </span>

                </div>

            </div>

            <div
                class="timer"
                id="timer"
            >
                00:00
            </div>

            <p class="timer-detail">

                restantes de

                <span id="totalTime">
                    00:00
                </span>

            </p>

            <div class="controls">

                <button
                    id="pauseBtn"
                    class="pause-btn"
                >
                    Pausar
                </button>

                <button
                    id="finishBtn"
                    class="finish-btn"
                >
                    Finalizar
                </button>

            </div>

            <small>

                Respira sin forzarte.
                Si sientes incomodidad o mareo,
                detén la práctica.

            </small>

        </section>

    </section>

  
    

</main>

</div>

<button class="chat-btn">

    ✨ Tu espacio de escucha • IA

</button>

<script>

let tiempoRestante = 0;
let tiempoTotal = 0;

let temporizador = null;
let pausado = false;

const timerElement =
document.getElementById("timer");

const totalTimeElement =
document.getElementById("totalTime");

const titleElement =
document.getElementById("exerciseTitle");

const pauseBtn =
document.getElementById("pauseBtn");

const finishBtn =
document.getElementById("finishBtn");

const ejercicios =
document.querySelectorAll(".exercise-card");

function formatearTiempo(segundos){

    let minutos =
    Math.floor(segundos / 60);

    let segundosRestantes =
    segundos % 60;

    return (
        String(minutos).padStart(2,"0")
        + ":"
        + String(segundosRestantes).padStart(2,"0")
    );

}

function actualizarPantalla(){

    timerElement.textContent =
    formatearTiempo(tiempoRestante);

    totalTimeElement.textContent =
    formatearTiempo(tiempoTotal);

}

function iniciarTemporizador(){

    clearInterval(temporizador);

    temporizador = setInterval(() => {

        if(pausado){
            return;
        }

        if(tiempoRestante <= 0){

            clearInterval(temporizador);

            avanzarEjercicio();

            return;

        }

        tiempoRestante--;

        actualizarPantalla();

    },1000);

}

function cargarEjercicio(card){

    document
    .querySelectorAll(".exercise-card")
    .forEach(item => {

        item.classList.remove("active");

    });

    card.classList.add("active");

    titleElement.textContent =
    card.dataset.name;

    tiempoRestante =
    parseInt(card.dataset.duration);

    tiempoTotal =
    tiempoRestante;

    pausado = false;

    pauseBtn.textContent =
    "Pausar";

    actualizarPantalla();

    iniciarTemporizador();

}

function avanzarEjercicio(){

    const actual =
    document.querySelector(
        ".exercise-card.active"
    );

    if(!actual){
        return;
    }

    const siguiente =
    actual.nextElementSibling;

    if(siguiente){

        cargarEjercicio(
            siguiente
        );

    }else{

        clearInterval(
            temporizador
        );

        titleElement.textContent =
        "¡Has completado todos los ejercicios!";

        tiempoRestante = 0;
        tiempoTotal = 0;

        actualizarPantalla();

        alert(
            "Excelente trabajo. Todos los ejercicios han finalizado."
        );

    }

}

pauseBtn.addEventListener(
"click",
function(){

    if(tiempoTotal === 0){
        return;
    }

    pausado = !pausado;

    this.textContent =
    pausado
    ? "Continuar"
    : "Pausar";

});

finishBtn.addEventListener(
"click",
function(){

    if(tiempoTotal === 0){
        return;
    }

    clearInterval(
        temporizador
    );

    avanzarEjercicio();

});

ejercicios.forEach(card => {

    card.addEventListener(
    "click",
    function(){

        cargarEjercicio(this);

    });

});

actualizarPantalla();

</script>
</body>
</html>