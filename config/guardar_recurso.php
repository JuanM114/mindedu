<?php

require_once("database.php");

$titulo = $_POST['titulo'];
$categoria = $_POST['categoria'];
$descripcion = $_POST['descripcion'];
$formato = $_POST['formato'];
$duracion = $_POST['duracion'];


/* IMAGEN */

$nombreImagen =
    time() .
    "_" .
    basename($_FILES['imagen']['name']);

$rutaImagen =
    "../img/recursos/" .
    $nombreImagen;

move_uploaded_file(
    $_FILES['imagen']['tmp_name'],
    $rutaImagen
);


/* ARCHIVO */

$nombreArchivo =
    time() .
    "_" .
    basename($_FILES['archivo']['name']);

$rutaArchivo =
    "../archivos/recursos/" .
    $nombreArchivo;

move_uploaded_file(
    $_FILES['archivo']['tmp_name'],
    $rutaArchivo
);


/* GUARDAR */

$sql = "
INSERT INTO recursos(

titulo,
categoria,
descripcion,
formato,
duracion,
imagen,
archivo

)

VALUES(

?,
?,
?,
?,
?,
?,
?

)
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(

"sssssss",

$titulo,
$categoria,
$descripcion,
$formato,
$duracion,
$nombreImagen,
$nombreArchivo

);

$stmt->execute();

header(
    "Location: ../vista/recursos.php"
);

exit();

?>