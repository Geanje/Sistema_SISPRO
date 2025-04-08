<?php
require_once "../config/Conexion.php";
require_once "../modelos/practicantes.php";

if (isset($_GET['id'])) {
    $idPracticante = $_GET['id'];
    $practicantes = new Practicantes();
    $resultado = $practicantes->eliminar($idPracticante);

    header("Location: mostrar.php");
    exit();
}
?>
