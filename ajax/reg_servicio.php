<?php
if (strlen(session_id()) < 1)
session_start();
require_once "../modelos/Reg_servicio.php";

$regser = new RegServicio(); 

$idservicio = isset($_POST['idservicio'])? limpiarCadena($_POST['idservicio']):"";
$nombre = isset($_POST['nombre'])? limpiarCadena($_POST['nombre']):"";
$costo = isset($_POST['costo'])? limpiarCadena($_POST['costo']):"";
$costo_dia = isset($_POST['costo_dia'])? limpiarCadena($_POST['costo_dia']):"";
$costo_dia_31 = isset($_POST['costo_dia_31'])? limpiarCadena($_POST['costo_dia_31']):"";
$costo_dia_28 = isset($_POST['costo_dia_28'])? limpiarCadena($_POST['costo_dia_28']):"";
$costo_dia_29 = isset($_POST['costo_dia_29'])? limpiarCadena($_POST['costo_dia_29']):"";



switch ($_GET['op']) {
    case 'guardaryeditar':
        if (empty($idservicio)) {
            $rspta = $regser -> insertar($nombre, $costo, $costo_dia, $costo_dia_31, $costo_dia_28, $costo_dia_29);
            echo $rspta ? "Servicio registrado con éxito" : "Algo salio mal";
        } else {
            $rspta = $regser -> editar($idservicio,$nombre,$costo, $costo_dia, $costo_dia_31, $costo_dia_28, $costo_dia_29);
            echo $rspta ? "Servicio editado" : "No se pudo editar el servicio";
        }
        break;
    
    case 'mostrar':
        $rspta = $regser -> mostrar($idservicio);
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $regser -> listar();

        $data=Array();
        while($reg=$rspta->fetch_object ()) {
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idservicio.')"><i class="fa fa-pencil"></i></button>',
                // ' <button class="btn btn-danger" onclick="desactivar('.$reg->idservicio.')"><i class="fa fa-times"></i></button>'.
                // ' <button class="btn btn-success" onclick="activar('.$reg->idservicio.')"><i class="fa fa-check"></i></button>',           
                "1"=>$reg->nombre,
                "2"=>$reg->costo,
                "3"=>$reg->costo_dia

              );
            }
            $results= array(
              "sEcho"=>1, //Informacion para el datatable
              "iTotalRecords"=>count($data),//Enviamos el total de registtros en el datatable
              "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
              "aaData"=>$data);
            echo json_encode($results);

        break;
}

?>