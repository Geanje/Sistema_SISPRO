<?php

require_once "../modelos/Registro_tecnico.php";

$tecnico = new Tecnico();

$idtecnico=isset($_POST["idtecnico"])? limpiarCadena($_POST["idtecnico"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$dni=isset($_POST["dni"])? limpiarCadena($_POST["dni"]):"";
$area=isset($_POST["area"])? limpiarCadena($_POST["area"]):"";
$cargo=isset($_POST["cargo"])? limpiarCadena($_POST["cargo"]):"";
$tipo_tecnico=isset($_POST["tipo_tecnico"])? limpiarCadena($_POST["tipo_tecnico"]):"";

switch ($_GET["op"]) {
    case 'guardar':
        if(empty($idtecnico)){
            $rspta=$tecnico->insertar($nombre,$telefono,$dni,$area,$cargo,$tipo_tecnico);
            echo $rspta ? "Tecnico registrado" : "No se puedieron registrar todos los datos del tecnico";
        }else{
            $rspta=$tecnico->editar($idtecnico,$nombre,$telefono,$dni,$area,$cargo,$tipo_tecnico);
        }
        break;
    
        case 'mostrar':
            $rspta=$tecnico->mostrar($idtecnico);
            //codificar el resultado usando json
            echo json_encode($rspta);
          break;

    case 'listarTecnico':
        $rspta=$tecnico->listarTecnico();

        //Declaramos un array
        $data=Array();

        while ($reg=$rspta->fetch_object()) {
            $data[] = array(
                "0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idtecnico.')"><i class="fa fa-pencil"></i></button>'.
          ' <button class="btn btn-danger" onclick="eliminar('.$reg->idtecnico.')"><i class="fa fa-trash"></i></button>',
          "1"=>$reg->nombre,
          "2"=>$reg->telefono,
          "3"=>$reg->dni,
          "4"=>$reg->area,
          "5"=>$reg->cargo,
          "6"=>$reg->tipo_tecnico
            );
        }
        $results= array(
            "sEcho"=>1, //Informacion para el datatable
            "iTotalRecords"=>count($data),//Enviamos el total de registtros en el datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
            "aaData"=>$data);
          echo json_encode($results);

        break;

        case 'eliminar':
            $rspta=$tecnico->eliminar($idtecnico);
            echo $rspta ? "Tecnico eliminado" : "Tecnico no se pudo eliminar";
          break;
}

?>