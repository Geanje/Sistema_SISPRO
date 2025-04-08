<?php
ob_start(); 
 
require_once "../config/Conexion.php";
require_once "../modelos/practicantes.php";

$practicantes = new Practicantes();

$nombres_apellidos = $_POST['nombres'];
$institucion = $_POST['institucion'];
$dni = $_POST['dni'];
$sede = $_POST['sede'];
$especialidad = $_POST['especialidad'];
$modalidad = $_POST['modalidad'];
$correo = $_POST['correo'];
$numero = $_POST['celular'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_termino = $_POST['fecha_termino'];
$estado = 'Activo';
$grupo = $_POST['grupo'];
$tarea = $_POST['tarea'];


$resultado = $practicantes->insertar($nombres_apellidos,  $institucion,$dni, $sede, $especialidad, $modalidad, $correo, $numero, $fecha_inicio, $fecha_termino, $estado, $grupo, $tarea);


if ($resultado === 2) {
    ob_end_clean(); 
    header("Location: mostrar.php?dni=".$dni);
    exit(); 
} elseif ($resultado) {
    ob_end_clean(); 
    header("Location: mostrar.php?dni=".$dni);
    exit(); 
} else {
    echo "Error al insertar datos.";
}

ob_end_flush(); 
?>

