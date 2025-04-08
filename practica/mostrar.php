<?php
require_once "../config/Conexion.php";
require_once "../modelos/practicantes.php";

// Creamos una instancia de la clase Practicantes
$practicantes = new Practicantes();

$dni_ingresado = isset($_GET['dni']) ? $_GET['dni'] : '';

// Traer todos los registros de la tabla 
// $respuesta = $practicantes->listar(); //

// Mostrar solo el dni ingresado y no mostrar nada en el archivo mostrar.php
 $respuesta = $practicantes->listarByDNI($dni_ingresado);

// Mostrar solo el dni ingresado y mostrar todo el registro en el archivo mostrar.php
// if (!empty($dni_ingresado)) {
//   $respuesta = $practicantes->listarByDNI($dni_ingresado);
// } else {
//   $respuesta = $practicantes->listar();
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="./styles/mostrar.css">
    <link rel="stylesheet" href="css/formulario.css" type="text/css" />
    <script type="text/javascript" src="js/reporte-incidencias.js"></script>
    <title>Mostrar Practicantes</title>
</head>
<body>
<div class="color-fondo"> 
    <div class="marco_m">
      <div id="logo1" class="clearfix">
        <a  class="retina-logo" data-dark-logo="images/logo-dark@2x.png">
            <img src="img/logo.png" alt="Canvas Logo" style="float: left; margin-top: 10px;">
        </a> <a href="index.php" id="exit"  style="float: right; margin-top: 16px;">Salir</a><br><br><br><br>    
      </div> 
      
        <h1>Lista de Practicantes</h1>
        <div class="container_m">
          <table style="width:100%; " border="1" >
                    <thead>
                      <th style="width:5%; ">Opciones</th>
                      <th style="width:20%; ">Nombres y Apellidos</th>
                      <th>Institucion</th>
                      <th>DNI</th>
                      <th>Sede</th>
                      <th>Especialidad</th>
                      <th>Modalidad</th>
                      <th>Numero</th>
                      <th>Correo</th>
                      <th>Fecha de inicio</th>
                      <th>Fecha de termino</th>
                      <th>Estado</th>
                    </thead>
                    <tbody>
                    <?php
          foreach ($respuesta as $fila) {
            $dni_practicante = $fila['dni'];
            $es_mi_practicante = ($dni_practicante === $dni_ingresado);
          ?>
          <tr class="mostrar">
              <td>
              <?php if ($es_mi_practicante) { ?>
                <span class="opciones">
                  <a href="editar_practicante.php?id=<?= $fila['idpracticante'] ?>" class="editar" title="Editar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M384 224v184a40 40 0 01-40 40H104a40 40 0 01-40-40V168a40 40 0 0140-40h167.48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M459.94 53.25a16.06 16.06 0 00-23.22-.56L424.35 65a8 8 0 000 11.31l11.34 11.32a8 8 0 0011.34 0l12.06-12c6.1-6.09 6.67-16.01.85-22.38zM399.34 90L218.82 270.2a9 9 0 00-2.31 3.93L208.16 299a3.91 3.91 0 004.86 4.86l24.85-8.35a9 9 0 003.93-2.31L422 112.66a9 9 0 000-12.66l-9.95-10a9 9 0 00-12.71 0z"/></svg>
                        </a>
                        <a href="eliminar_practicante.php?id=<?= $fila['idpracticante']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este practicante?')" class="eliminar" title="eliminar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M112 112l20 320c.95 18.49 14.4 32 32 32h184c17.67 0 30.87-13.51 32-32l20-320" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M80 112h352"/><path d="M192 112V72h0a23.93 23.93 0 0124-24h80a23.93 23.93 0 0124 24h0v40M256 176v224M184 176l8 224M328 176l-8 224" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>
                        </a>
                </span>
                      
                      <?php } ?>
              </td>
              <td><?= $fila['nombres_apellidos']; ?></td>
              <td><?= $fila['institucion']; ?></td>
              <td><?= $fila['dni']; ?></td>
              <td><?= $fila['sede']; ?></td>
              <td><?= $fila['especialidad']; ?></td>
              <td><?= $fila['modalidad']; ?></td>
              <td><?= $fila['numero']; ?></td>
              <td><?= $fila['correo']; ?></td>
              <td><?= $fila['fecha_inicio']; ?></td>
              <td><?= $fila['fecha_termino']; ?></td>
              <td><?= $fila['estado']; ?></td>
            </tr>
          <?php

          }
          ?>
                    </tbody>
                    <tfoot>
                      <th>Opciones</th>
                      <th>Nombres y Apellidos</th>
                      <th>Institucion</th>
                      <th>DNI</th>
                      <th>Sede</th>
                      <th>Especialidad</th>
                      <th>Modalidad</th>
                      <th>Numero</th>
                      <th>Correo</th>
                      <th>Fecha de inicio</th>
                      <th>Fecha de termino</th>
                      <th>Estado</th>
                    </tfoot>
                  </table>
        </div>
      </div>
    
  </div>
</body>
</html>
