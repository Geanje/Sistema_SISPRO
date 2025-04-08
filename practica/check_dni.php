<?php
require_once "../config/Conexion.php";

if (isset($_GET['dni'])) {
  $dni = $_GET['dni'];
  $query = "SELECT * FROM practicantes WHERE dni = '$dni'";
  $resultado = ejecutarConsultaSimpleFila($query);
  $exists = ($resultado !== null);

  echo json_encode(['exists' => $exists]);
}
?>
