<?php

require_once("database.php");

$titulo = $_POST['titulo'];

$descripcion = $_POST['descripcion'];

$categoria = $_POST['categoria'];

$duracion = intval($_POST['duracion']) * 60;

$instrucciones = $_POST['instrucciones'];


/* IMAGEN */

$nombreImagen = "";

if(!empty($_FILES['imagen']['name'])){

    $nombreImagen =
        time() .
        "_" .
        basename($_FILES['imagen']['name']);

    move_uploaded_file(

        $_FILES['imagen']['tmp_name'],

        "../img/ejercicios/" .
        $nombreImagen

    );

}


/* AUDIO */

$nombreAudio = "";

if(!empty($_FILES['audio']['name'])){

    $nombreAudio =
        time() .
        "_" .
        basename($_FILES['audio']['name']);

    move_uploaded_file(

        $_FILES['audio']['tmp_name'],

        "../audios/ejercicios/" .
        $nombreAudio

    );

}


/* SQL */

$sql = "

INSERT INTO ejercicios(

titulo,
descripcion,
categoria,
duracion,
imagen,
audio,
instrucciones

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

"sssisss",

$titulo,
$descripcion,
$categoria,
$duracion,
$nombreImagen,
$nombreAudio,
$instrucciones

);

$stmt->execute();

header(
    "Location: ../admin/subir_ejercicio.php"
);

exit();

?>