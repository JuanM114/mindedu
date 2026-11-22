<?php

session_start();

if(!isset($_SESSION['usuario_id'])){
    header("Location: ../vista/index.php");
    exit();
}

require_once("database.php");

$usuario_id = $_SESSION['usuario_id'];

/* IDIOMA */

$idioma = $_POST['idioma'];

/* RECORDATORIOS */

$recordatorio_activo =
isset($_POST['recordatorio_activo'])
? 1
: 0;

$hora_recordatorio = null;

if($recordatorio_activo){

    $hora_recordatorio =
    $_POST['hora_recordatorio'];

}

/* ACCESIBILIDAD */

$texto_grande =
isset($_POST['texto_grande'])
? 1
: 0;

$alto_contraste =
isset($_POST['alto_contraste'])
? 1
: 0;

$reducir_movimiento =
isset($_POST['reducir_movimiento'])
? 1
: 0;


/* ACTUALIZAR CONFIGURACIÓN */

$sql = "

UPDATE configuracion_usuario

SET

idioma = ?,

recordatorio_activo = ?,

hora_recordatorio = ?,

texto_grande = ?,

alto_contraste = ?,

reducir_movimiento = ?

WHERE usuario_id = ?

";

$stmt =
$conn->prepare($sql);

$stmt->bind_param(

"sisiiii",

$idioma,

$recordatorio_activo,

$hora_recordatorio,

$texto_grande,

$alto_contraste,

$reducir_movimiento,

$usuario_id

);

$stmt->execute();


/* REDIRECCIONAR */

header(
"Location: ../vista/configuracion.php?guardado=ok"
);

exit();

?>