<?php
require_once "../config/Conexion.php";
require_once "../modelos/practicantes.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['idpracticante'];
    $nombresApellidos = $_POST['nombres_apellidos'];
    $institucion = $_POST['institucion'];
    $dni = $_POST['dni'];
    $sede = $_POST['sede'];
    $especialidad = $_POST['especialidad'];
    $modalidad = $_POST['modalidad'];
    $correo = $_POST['correo'];
    $numero = $_POST['celular'];
    $fechaInicio = $_POST['fecha_inicio'];
    $fechaTermino = $_POST['fecha_termino'];
    $estado = $_POST['estado'];

    $practicantes = new Practicantes();

    $resultado = $practicantes->editar($id, $nombresApellidos,  $institucion,$dni, $sede, $especialidad, $modalidad, $correo, $numero, $fechaInicio, $fechaTermino, $estado);
    //Redireccionar al momento de editar practicante
    if ($resultado) {
        $dniIngresado = $_POST['dni']; // Suponiendo que el campo del formulario se llama 'dni'
        $url = "mostrar.php?dni=" . urlencode($dniIngresado);
        header("Location: $url");
        exit();
    } else {
        header("Location: mostrar.php?mensaje=Error al editar el practicante");
        exit();
    }
} else {
    header("Location: mostrar.php");
    exit();
}
