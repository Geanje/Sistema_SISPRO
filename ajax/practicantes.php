<?php
require_once "../modelos/practicantes.php";

$practicantes = new Practicantes();

$idpracticante = isset($_POST["idpracticante"]) ? limpiarCadena($_POST["idpracticante"]) : "";
$nombres_apellidos = isset($_POST["nombres_apellidos"]) ? limpiarCadena($_POST["nombres_apellidos"]) : "";
$institucion = isset($_POST["institucion"]) ? limpiarCadena($_POST["institucion"]) : "";
$dni = isset($_POST["dni"]) ? limpiarCadena($_POST["dni"]) : "";
$sede = isset($_POST["sede"]) ? limpiarCadena($_POST["sede"]) : "";
$especialidad = isset($_POST["especialidad"]) ? limpiarCadena($_POST["especialidad"]) : "";
$modalidad = isset($_POST["modalidad"]) ? limpiarCadena($_POST["modalidad"]) : "";
$correo = isset($_POST["correo"]) ? limpiarCadena($_POST["correo"]) : "";
$numero = isset($_POST["numero"]) ? limpiarCadena($_POST["numero"]) : "";
$fecha_inicio = isset($_POST["fecha_inicio"]) ? limpiarCadena($_POST["fecha_inicio"]) : "";
$fecha_termino = isset($_POST["fecha_termino"]) ? limpiarCadena($_POST["fecha_termino"]) : "";
$estado = isset($_POST["estado"]) ? limpiarCadena($_POST["estado"]) : "";
$grupo = isset($_POST["grupo"]) ? limpiarCadena($_POST["grupo"]) : "";
$tarea =isset($_POST["tarea"]) ? limpiarCadena($_POST["tarea"]) : "";

switch ($_GET["op"]) {
  case 'guardaryeditar':
    if (empty($idpracticante)) {
      $rspta = $practicantes->insertar($nombres_apellidos, $institucion, $dni, $sede, $especialidad, $modalidad, $correo, $numero, $fecha_inicio, $fecha_termino, $estado, $grupo, $tarea);
      echo $rspta ? "El Practicante a sido registrado" : "No se puedieron registrar todos los datos del practicante";
    } else {

      $rspta = $practicantes->editar($idpracticante, $nombres_apellidos, $institucion, $dni, $sede, $especialidad, $modalidad, $correo, $numero, $fecha_inicio, $fecha_termino, $estado, $grupo, $tarea);
      echo $rspta ? "los Datos del Practicante se han actualizado" : "Los datos del Practicante no se pudieron actualizar";
    }

    break;


  case 'eliminar':
    $rspta = $practicantes->eliminar($idpracticante);
    echo $rspta ? "Practicante eliminado" : "El Practicante no se pudo eliminar";
    break;



  case 'mostrar':
    $rspta = $practicantes->mostrar($idpracticante);
    //codificar el resultado usando json
    echo json_encode($rspta);
    break;

  case 'listar':
    $rspta = $practicantes->listar();
    //Vamos a declarar un array
    $data = array();
    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => '<button class="btn btn-warning" onclick="mostrar(' . $reg->idpracticante . ')"><i class="fa fa-pencil"></i></button>' .
          ' <button class="btn btn-danger" onclick="eliminar(' . $reg->idpracticante . ')"><i class="fa fa-trash"></i></button>',
        "1" => $reg->nombres_apellidos,
        "2" => $reg->institucion,
        "3" => $reg->dni,
        "4" => $reg->sede,
        "5" => $reg->especialidad,
        "6" => $reg->modalidad,
        "7" => $reg->correo,
        "8" => $reg->numero,
        "9" => $reg->fecha_inicio,
        "10" => $reg->fecha_termino,
        "11" => $reg->estado,
        "12" => $reg->grupo,
        "13" => $reg->tarea
      );
    }
    $results = array(
      "sEcho" => 1, //Informacion para el datatable
      "iTotalRecords" => count($data), //Enviamos el total de registtros en el datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);
    break;
}
