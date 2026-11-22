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

$stmtUsuario = $conn->prepare($sqlUsuario);

$stmtUsuario->bind_param(
    "i",
    $idUsuario
    
);



$stmtUsuario->execute();

$usuario =
$stmtUsuario
->get_result()
->fetch_assoc();

$nombre =
$usuario['nombre'];


/* CONFIGURACIÓN */

$sqlConfig = "

SELECT *

FROM configuracion_usuario

WHERE usuario_id = ?

";

$stmtConfig =
$conn->prepare($sqlConfig);

$stmtConfig->bind_param(
    "i",
    $idUsuario
);

$stmtConfig->execute();

$config =
$stmtConfig
->get_result()
->fetch_assoc();


if(!$config){

    $sqlNueva = "

    INSERT INTO configuracion_usuario
    (
        usuario_id
    )
    VALUES
    (
        ?
    )

    ";

    $stmtNueva =
    $conn->prepare($sqlNueva);

    $stmtNueva->bind_param(
        "i",
        $idUsuario
    );

    $stmtNueva->execute();

    header("Location: configuracion.php");
    exit();

    $idioma = $config['idioma'];

require_once(
    "../idioma/$idioma.php"
);

}


/* VARIABLES */

$idioma =
$config['idioma'];

$recordatorioActivo =
$config['recordatorio_activo'];

$horaRecordatorio =
$config['hora_recordatorio'];

$textoGrande =
$config['texto_grande'];

$altoContraste =
$config['alto_contraste'];

$reducirMovimiento =
$config['reducir_movimiento'];

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>
MindEdu - Configuración
</title>

<link
rel="stylesheet"
href="../css/configuracion.css"
>

</head>

<body>

<div class="container">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <h2>
            MindEdu
        </h2>

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
                    <a href="relajacion.php">
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
                    <a
                    href="configuracion.php"
                    class="active"
                    >
                        Configuración
                    </a>
                </li>

            </ul>

        </nav>

        <div class="support">

            <p>
                Personaliza tu experiencia
                para que MindEdu se adapte
                mejor a tus necesidades.
            </p>

        </div>

        <div class="user">

            <p>

                <strong>

                    <?php
                    echo htmlspecialchars(
                        $nombre
                    );
                    ?>

                </strong>

            </p>

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

        <div class="topbar">

            <div>

                Mi espacio /
                Configuración

            </div>

            <div>

                <?php
                echo date("d/m/Y");
                ?>

            </div>

        </div>

        <s<section class="hero">

    <span
        class="section-label"
        data-i18n="heroLabel"
    >
        PERSONALIZA TU EXPERIENCIA
    </span>

    <h1 data-i18n="titulo">
        Una experiencia a tu medida
    </h1>

    <p data-i18n="descripcion">
        Configura idioma, accesibilidad y recordatorios.
    </p>

</section>
        </section>

        <form
        action="../config/guardar_configuracion.php"
        method="POST"
        >

            <div class="settings-layout">

                <!-- IZQUIERDA -->

                <div class="settings-column">

                    <section class="settings-card">

    <h2 data-i18n="idiomaTitulo">
        Idioma
    </h2>

    <p data-i18n="idiomaDesc">
        El idioma se aplicará a toda la plataforma.
    </p>

    <select
        id="idioma"
        name="idioma"
    >

        <option value="es" <?php echo $idioma=='es' ? 'selected' : ''; ?>>
            Español
        </option>

        <option value="en" <?php echo $idioma=='en' ? 'selected' : ''; ?>>
            English
        </option>

        <option value="fr" <?php echo $idioma=='fr' ? 'selected' : ''; ?>>
            Français
        </option>

    </select>

</section>
                    </section>

                   <section class="settings-card">

    <h2 data-i18n="recordatoriosTitulo">
        Recordatorios
    </h2>

    <p data-i18n="recordatoriosDesc">
        Registra cómo te sientes cada día.
    </p>

    <label>

        <input
            type="checkbox"
            id="activarRecordatorio"
            name="recordatorio_activo"
            <?php if($recordatorioActivo) echo "checked"; ?>
        >

        <span data-i18n="activarRecordatorio">
            Activar recordatorio
        </span>

    </label>

    <br><br>

    <label data-i18n="horaRecordatorio">
        Hora del recordatorio
    </label>

    <input

        type="time"

        id="horaRegistro"

        name="hora_recordatorio"

        value="<?php echo $horaRecordatorio; ?>"

        <?php
        if(!$recordatorioActivo){
            echo "disabled";
        }
        ?>

    >

</section>
                </div>

                <!-- DERECHA -->

                <div class="settings-column">

                  <section class="settings-card accessibility-card">

    <h2 data-i18n="accesibilidadTitulo">
        Accesibilidad
    </h2>

    <p
        class="card-desc"
        data-i18n="accesibilidadDesc"
    >
        Ajusta la plataforma para una experiencia
        más cómoda y adaptada a tus necesidades.
    </p>

    <div class="option-row">

        <div>

            <strong data-i18n="textoGrande">
                Texto más grande
            </strong>

            <p data-i18n="textoGrandeDesc">
                Amplía el texto de la interfaz.
            </p>

        </div>

        <input
            type="checkbox"
            id="bigText"
            name="texto_grande"
            <?php if($textoGrande) echo "checked"; ?>
        >

    </div>

    <div class="option-row">

        <div>

            <strong data-i18n="altoContraste">
                Alto contraste
            </strong>

            <p data-i18n="altoContrasteDesc">
                Refuerza texto, bordes y controles.
            </p>

        </div>

        <input
            type="checkbox"
            id="highContrast"
            name="alto_contraste"
            <?php if($altoContraste) echo "checked"; ?>
        >

    </div>

    <div class="option-row">

        <div>

            <strong data-i18n="reducirMovimiento">
                Reducir movimiento
            </strong>

            <p data-i18n="reducirMovimientoDesc">
                Evita animaciones no esenciales.
            </p>

        </div>

        <input
            type="checkbox"
            id="reduceMotion"
            name="reducir_movimiento"
            <?php if($reducirMovimiento) echo "checked"; ?>
        >

    </div>

    <div class="preview-box">

        <small data-i18n="vistaLectura">

            VISTA DE LECTURA ACTUAL

        </small>

        <p data-i18n="vistaTexto">

            Toma una pausa.
            Este espacio es para ti.

        </p>

    </div>

</section>

                    <section class="settings-card">

    <h2 data-i18n="historialTitulo">
        Historial emocional
    </h2>

    <p data-i18n="historialDesc">

        Consulta tus registros realizados.

    </p>

    <a
        href="seguimiento.php"
        data-i18n="verHistorial"
    >
        Ver historial →
    </a>

</section>

                   <section class="settings-card privacy-card">

    <h2 data-i18n="privacidadTitulo">

        Privacidad con claridad

    </h2>

    <p data-i18n="privacidadDesc">

        Conoce las finalidades del tratamiento,
        las medidas previstas y tus derechos.

    </p>

    <a
        href="#"
        data-i18n="politicaPrivacidad"
    >
        Política de privacidad ↗
    </a>

    <br><br>

    <a
        href="#"
        data-i18n="consentimiento"
    >
        Consultar consentimiento informado ↗
    </a>

</section>



                </div>

            </div>

           <button
    type="submit"
    class="save-btn"
    data-i18n="guardar"
>

    Guardar configuración

</button>

            <?php if(isset($_GET['guardado'])): ?>

<div class="success-message">

    Configuración guardada correctamente.

</div>

<?php endif; ?>

        </form>

    </main>

</div>

<script>

/* RECORDATORIOS */

const activar =
document.getElementById(
    "activarRecordatorio"
);

const hora =
document.getElementById(
    "horaRegistro"
);

if (activar) {

    activar.addEventListener(
        "change",
        function(){

            hora.disabled =
            !this.checked;

        }
    );

}

/* ACCESIBILIDAD */

const bigText =
document.getElementById(
    "bigText"
);

const highContrast =
document.getElementById(
    "highContrast"
);

const reduceMotion =
document.getElementById(
    "reduceMotion"
);

function aplicarAccesibilidad(){

    if(bigText){

        document.body.classList.toggle(
            "big-text",
            bigText.checked
        );

    }

    if(highContrast){

        document.body.classList.toggle(
            "high-contrast",
            highContrast.checked
        );

    }

    if(reduceMotion){

        document.body.classList.toggle(
            "reduce-motion",
            reduceMotion.checked
        );

    }

}

if(bigText){

    bigText.addEventListener(
        "change",
        aplicarAccesibilidad
    );

}

if(highContrast){

    highContrast.addEventListener(
        "change",
        aplicarAccesibilidad
    );

}

if(reduceMotion){

    reduceMotion.addEventListener(
        "change",
        aplicarAccesibilidad
    );

}

/* APLICAR AL CARGAR */

aplicarAccesibilidad();


</script>
<script>

/* ==========================
   TRADUCCIONES
========================== */

const traducciones = {

    es: {

        heroLabel:
        "PERSONALIZA TU EXPERIENCIA",

        titulo:
        "Una experiencia a tu medida",

        descripcion:
        "Configura idioma, accesibilidad y recordatorios.",

        idiomaTitulo:
        "Idioma",

        idiomaDesc:
        "El idioma se aplicará a toda la plataforma.",

        recordatoriosTitulo:
        "Recordatorios",

        recordatoriosDesc:
        "Registra cómo te sientes cada día.",

        activarRecordatorio:
        "Activar recordatorio",

        horaRecordatorio:
        "Hora del recordatorio",

        accesibilidadTitulo:
        "Accesibilidad",

        accesibilidadDesc:
        "Ajusta la plataforma para una experiencia más cómoda y adaptada a tus necesidades.",

        textoGrande:
        "Texto más grande",

        textoGrandeDesc:
        "Amplía el texto de la interfaz.",

        altoContraste:
        "Alto contraste",

        altoContrasteDesc:
        "Refuerza texto, bordes y controles.",

        reducirMovimiento:
        "Reducir movimiento",

        reducirMovimientoDesc:
        "Evita animaciones no esenciales.",

        vistaLectura:
        "VISTA DE LECTURA ACTUAL",

        vistaTexto:
        "Toma una pausa. Este espacio es para ti.",

        historialTitulo:
        "Historial emocional",

        historialDesc:
        "Consulta tus registros realizados.",

        verHistorial:
        "Ver historial →",

        privacidadTitulo:
        "Privacidad con claridad",

        privacidadDesc:
        "Conoce las finalidades del tratamiento, las medidas previstas y tus derechos.",

        politicaPrivacidad:
        "Política de privacidad ↗",

        consentimiento:
        "Consultar consentimiento informado ↗",

        guardar:
        "Guardar configuración"

    },

    en: {

        heroLabel:
        "CUSTOMIZE YOUR EXPERIENCE",

        titulo:
        "A personalized experience",

        descripcion:
        "Configure language, accessibility and reminders.",

        idiomaTitulo:
        "Language",

        idiomaDesc:
        "The selected language will apply throughout the platform.",

        recordatoriosTitulo:
        "Reminders",

        recordatoriosDesc:
        "Register how you feel every day.",

        activarRecordatorio:
        "Enable reminder",

        horaRecordatorio:
        "Reminder time",

        accesibilidadTitulo:
        "Accessibility",

        accesibilidadDesc:
        "Adjust the platform for a more comfortable experience.",

        textoGrande:
        "Large text",

        textoGrandeDesc:
        "Increase interface text size.",

        altoContraste:
        "High contrast",

        altoContrasteDesc:
        "Improve text and control visibility.",

        reducirMovimiento:
        "Reduce motion",

        reducirMovimientoDesc:
        "Avoid non-essential animations.",

        vistaLectura:
        "CURRENT READING VIEW",

        vistaTexto:
        "Take a pause. This space is for you.",

        historialTitulo:
        "Emotional history",

        historialDesc:
        "Review your previous records.",

        verHistorial:
        "View history →",

        privacidadTitulo:
        "Privacy made clear",

        privacidadDesc:
        "Learn about data processing purposes, measures and your rights.",

        politicaPrivacidad:
        "Privacy policy ↗",

        consentimiento:
        "View informed consent ↗",

        guardar:
        "Save settings"

    },

    fr: {

        heroLabel:
        "PERSONNALISEZ VOTRE EXPÉRIENCE",

        titulo:
        "Une expérience personnalisée",

        descripcion:
        "Configurez la langue, l'accessibilité et les rappels.",

        idiomaTitulo:
        "Langue",

        idiomaDesc:
        "La langue choisie sera appliquée à toute la plateforme.",

        recordatoriosTitulo:
        "Rappels",

        recordatoriosDesc:
        "Enregistrez comment vous vous sentez chaque jour.",

        activarRecordatorio:
        "Activer le rappel",

        horaRecordatorio:
        "Heure du rappel",

        accesibilidadTitulo:
        "Accessibilité",

        accesibilidadDesc:
        "Ajustez la plateforme pour une expérience plus confortable.",

        textoGrande:
        "Texte agrandi",

        textoGrandeDesc:
        "Augmente la taille du texte.",

        altoContraste:
        "Contraste élevé",

        altoContrasteDesc:
        "Améliore la lisibilité des éléments.",

        reducirMovimiento:
        "Réduire les mouvements",

        reducirMovimientoDesc:
        "Évite les animations non essentielles.",

        vistaLectura:
        "APERÇU DE LECTURE",

        vistaTexto:
        "Prenez une pause. Cet espace est pour vous.",

        historialTitulo:
        "Historique émotionnel",

        historialDesc:
        "Consultez vos enregistrements.",

        verHistorial:
        "Voir l'historique →",

        privacidadTitulo:
        "Confidentialité en toute transparence",

        privacidadDesc:
        "Découvrez les objectifs du traitement et vos droits.",

        politicaPrivacidad:
        "Politique de confidentialité ↗",

        consentimiento:
        "Consulter le consentement éclairé ↗",

        guardar:
        "Enregistrer les paramètres"

    }

};

/* ==========================
   CAMBIO DE IDIOMA
========================== */

function aplicarIdioma(idioma){

    const textos =
    document.querySelectorAll(
        "[data-i18n]"
    );

    textos.forEach(elemento => {

        const clave =
        elemento.dataset.i18n;

        if(
            traducciones[idioma] &&
            traducciones[idioma][clave]
        ){

            elemento.textContent =
            traducciones[idioma][clave];

        }

    });

}

const selectorIdioma =
document.getElementById(
    "idioma"
);

if(selectorIdioma){

    aplicarIdioma(
        selectorIdioma.value
    );

    selectorIdioma.addEventListener(
        "change",
        function(){

            aplicarIdioma(
                this.value
            );

        }
    );

}

</script>


</body>
</html>