<?php
if (isset($_POST['ejecutar'])) {
  // Ejecutar el script de Python
  $resultado = shell_exec('cpanel.py');
  
  // Procesar el resultado o mostrarlo en pantalla
  echo "Resultado: " . $resultado;
}
?>