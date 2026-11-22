<?php

session_start();

if(!isset($_SESSION['usuario_id'])){
    header("Location: index.php");
    exit();
}

require_once("../config/database.php");

$idUsuario = $_SESSION['usuario_id'];

$sql = "SELECT nombre
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

$buscar = $_GET['buscar'] ?? '';
$formato = $_GET['formato'] ?? '';
$categoria = $_GET['categoria'] ?? '';

$sql = "SELECT *
        FROM recursos
        WHERE 1=1";

$parametros = [];
$tipos = "";

if(!empty($buscar)){

    $sql .= " AND (
                titulo LIKE ?
                OR descripcion LIKE ?
                OR categoria LIKE ?
             )";

    $texto = "%$buscar%";

    $parametros[] = $texto;
    $parametros[] = $texto;
    $parametros[] = $texto;

    $tipos .= "sss";
}

if(!empty($formato)){

    $sql .= " AND formato = ?";

    $parametros[] = $formato;

    $tipos .= "s";
}
if(!empty($categoria)){

    $sql .= " AND categoria = ?";

    $parametros[] = $categoria;

    $tipos .= "s";
}

$stmt = $conn->prepare($sql);

if(!empty($parametros)){

    $stmt->bind_param(
        $tipos,
        ...$parametros
    );
}

$stmt->execute();

$recursos = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>MindEdu - Recursos</title>

    <link rel="stylesheet" href="../css/recursos.css">

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
                    <a href="recursos.php" class="active">
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
                    <a href="configuracion.php">
                        Configuración
                    </a>
                </li>

            </ul>

        </nav>

        <div class="support">

            <p>
                No tienes que hacerlo a solas.
            </p>

            <p>
                Busca apoyo en una persona de confianza o profesional.
            </p>

            <a href="#">
                Conocer opciones →
            </a>

        </div>

        <div class="user">

            <p>

                <strong>
                    <?php echo htmlspecialchars($nombre); ?>
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

    <!-- TOPBAR -->

    <header class="top-bar">

        <div class="breadcrumb">
            Mi espacio / Recursos
        </div>

        <div class="top-actions">

            <span class="date">
                    <?php echo date('d/m/Y'); ?>
                </span>

            <button class="personal-space">
                Espacio personal
            </button>

        </div>

    </header>

    <!-- TÍTULO -->

    <section class="hero">

        <span class="section-label">
            HERRAMIENTAS PARA TU DÍA A DÍA
        </span>

        <h1>
            Encuentra algo que te haga bien
        </h1>

        <p>
            Artículos, videos y guías para explorar tus emociones
            y cultivar el bienestar.
        </p>

    </section>

    <!-- BUSCADOR -->

  <section class="search-area">

    <form method="GET" class="search-form">

    <input
        type="text"
        name="buscar"
        placeholder="Buscar por título o palabra clave"
        class="search-input"
        value="<?php echo htmlspecialchars($_GET['buscar'] ?? ''); ?>"
    >

    <select
        name="formato"
        class="format-filter"
    >
        <option value="">Todos los formatos</option>
        <option value="Guía">Guías</option>
        <option value="Video">Videos</option>
        <option value="Artículo">Artículos</option>
    </select>

    <button type="submit" class="search-btn">
        Buscar
    </button>

    <a href="recursos.php" class="clear-btn">
        Limpiar
    </a>

</form>

</section>

    <!-- CATEGORÍAS -->

    <section class="categories">

    <a
        href="recursos.php"
        class="category <?php echo empty($_GET['categoria']) ? 'active' : ''; ?>"
    >
        Todos los temas
    </a>

    <a
        href="recursos.php?categoria=Manejo del estrés"
        class="category <?php echo (($_GET['categoria'] ?? '') == 'Manejo del estrés') ? 'active' : ''; ?>"
    >
        Manejo del estrés
    </a>

    <a
        href="recursos.php?categoria=Respiración"
        class="category <?php echo (($_GET['categoria'] ?? '') == 'Respiración') ? 'active' : ''; ?>"
    >
        Respiración
    </a>

    <a
        href="recursos.php?categoria=Sueño"
        class="category <?php echo (($_GET['categoria'] ?? '') == 'Sueño') ? 'active' : ''; ?>"
    >
        Sueño
    </a>

    <a
        href="recursos.php?categoria=Autoestima"
        class="category <?php echo (($_GET['categoria'] ?? '') == 'Autoestima') ? 'active' : ''; ?>"
    >
        Autoestima
    </a>

    <a
        href="recursos.php?categoria=Emociones"
        class="category <?php echo (($_GET['categoria'] ?? '') == 'Emociones') ? 'active' : ''; ?>"
    >
        Emociones
    </a>

</section>

    <!-- RECOMENDACIÓN IA -->

    <section class="featured-resource">

        <div>

            <h3>
                Un buen lugar para empezar:
                Respira consciente
            </h3>

            <p>
                Sugerencia de IA basada en tu interés
                por la respiración.
            </p>

        </div>

        <button>
            Abrir guía
        </button>

    </section>

    <!-- ENCABEZADO LISTA -->

    <div class="resources-header">

        <p>
            6 recursos para explorar
        </p>

        <span>
            Ordenar por: Relevancia
        </span>

    </div>

    <!-- RECURSOS -->

   <section class="resources-grid">

<?php while($recurso = $recursos->fetch_assoc()): ?>

    <article class="resource-card">

        <img
            src="../img/recursos/<?php echo htmlspecialchars($recurso['imagen']); ?>"
            alt="<?php echo htmlspecialchars($recurso['titulo']); ?>"
        >

        <span class="resource-type">
            <?php echo htmlspecialchars($recurso['formato']); ?>
        </span>

        <p class="resource-meta">
            <?php echo htmlspecialchars($recurso['categoria']); ?>
            •
            <?php echo htmlspecialchars($recurso['duracion']); ?>
        </p>

        <h3>
            <?php echo htmlspecialchars($recurso['titulo']); ?>
        </h3>

        <p>
            <?php echo htmlspecialchars($recurso['descripcion']); ?>
        </p>

        <div class="resource-actions">

            <?php if(strtolower(trim($recurso['formato'])) === 'video'): ?>
                <button
                    type="button"
                    class="view-btn"
                    data-titulo="<?php echo htmlspecialchars($recurso['titulo'], ENT_QUOTES); ?>"
                    data-descripcion="<?php echo htmlspecialchars($recurso['descripcion'], ENT_QUOTES); ?>"
                    data-url="<?php echo htmlspecialchars($recurso['archivo'], ENT_QUOTES); ?>"
                    onclick="abrirModalVideo(this)"
                >
                    Ver video
                </button>
            <?php else: ?>
                <a
                    href="../archivos/recursos/<?php echo htmlspecialchars($recurso['archivo']); ?>"
                    target="_blank"
                    class="view-btn"
                >
                    Ver online
                </a>

                <a
                    href="../archivos/recursos/<?php echo htmlspecialchars($recurso['archivo']); ?>"
                    download
                >
                    Descargar
                </a>
            <?php endif; ?>

        </div>

    </article>

<?php endwhile; ?>

</section>

    <footer class="footer-note">

        <small>
            Contenido ilustrativo de bienestar.
            No sustituye la orientación de un profesional.
        </small>

    </footer>

</main>

</div>

<button class="chat-btn">
    ✨ Tu espacio de escucha · IA
</button>

<!-- VENTANA MODAL VIDEO ///////////////////////////////////////////////////////////////////////////// -->
<div id="modalVideo" class="modal-legal-overlay" onclick="cerrarModalVideoFondo(event)">
  <div class="modal-legal-caja">
    <div class="modal-legal-header">
      <h3 id="modalVideoTitulo">Video educativo</h3>
      <button type="button" class="modal-legal-cerrar" onclick="cerrarModalVideo()" aria-label="Cerrar">&times;</button>
    </div>
    <div id="modalVideoCuerpo" class="modal-legal-cuerpo"></div>
    <div class="modal-legal-footer">
      <button type="button" class="modal-legal-btn" onclick="cerrarModalVideo()">Cerrar</button>
    </div>
  </div>
</div>

<script>
  function obtenerEmbedYoutube(url) {
    if (!url) return "";
    const matchId = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/);
    if (!matchId) return "";

    let src = "https://www.youtube.com/embed/" + matchId[1] + "?autoplay=1";
    const matchTiempo = url.match(/[?&](?:t|start)=([0-9]+)s?/i);
    if (matchTiempo) {
      src += "&start=" + matchTiempo[1];
    }
    return src;
  }

  function abrirModalVideo(boton) {
    const titulo = boton.dataset.titulo || "Video educativo";
    const descripcion = boton.dataset.descripcion || "";
    const url = boton.dataset.url || "";
    const embedSrc = obtenerEmbedYoutube(url);

    document.getElementById("modalVideoTitulo").textContent = titulo;
    document.getElementById("modalVideoCuerpo").innerHTML = `
      <p>${descripcion}</p>
      <div style="position:relative;width:100%;padding-bottom:56.25%;margin-top:12px;border-radius:10px;overflow:hidden;background:#000;">
        <iframe
          src="${embedSrc}"
          style="position:absolute;inset:0;width:100%;height:100%;border:0;"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen>
        </iframe>
      </div>
    `;
    document.getElementById("modalVideo").classList.add("activo");
  }

  function cerrarModalVideo() {
    document.getElementById("modalVideo").classList.remove("activo");
    document.getElementById("modalVideoCuerpo").innerHTML = "";
  }

  function cerrarModalVideoFondo(event) {
    if (event.target.id === "modalVideo") {
      cerrarModalVideo();
    }
  }

  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
      cerrarModalVideo();
    }
  });
</script>

</body>
</html>