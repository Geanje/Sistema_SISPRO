<?php

if (strlen(session_id()) < 1)
session_start();

require_once "../modelos/Servicio.php";

$servicio = new Servicio();

$idservicio = isset($_POST['idservicio'])? limpiarCadena($_POST['idservicio']):"";
$nombre = isset($_POST['nombre'])? limpiarCadena($_POST['nombre']):"";
$costo = isset($_POST['costo'])? limpiarCadena($_POST['costo']):"";

switch ($_GET['op']) {
    case 'guardaryeditar':
        if (empty($idservicio)) {
            $rspta = $servicio ->insertar($nombre,$costo);
            echo $rspta ? "Servicio registrado" : "Algo salio mal";
        } else {
            $rspta = $servicio -> editar($idservicio,$nombre,$costo);
            echo $rspta ? "Servicio editado" : "No pudo ser editado";
        }
        break;
    
    case 'listar':
        $rspta = $servicio -> listar();

        $data = Array();
        while ($reg = $rspta -> fetch_object()) {
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->id_p_servicio.')"><i class="fa fa-pencil"></i></button>',
                // ' <button class="btn btn-danger" onclick="desactivar('.$reg->id_p_servicio.')"><i class="fa fa-times"></i></button>'.
                // ' <button class="btn btn-success" onclick="activar('.$reg->id_p_servicio.')"><i class="fa fa-check"></i></button>',
                // ' <button class="btn btn-warning" onclick="mostrarVentana()"><i class="fa fa-shopping-cart"></i></button>',
                // '<a target="_blank" href="../reportes/servicios-soporte.php?idsoporte='.$reg->id_p_servicio.'"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>',
               
                "1"=>$reg->nombre,
                "2"=>$reg->costo  
                //"3"=>$reg->concepto,
                //"4"=>$reg->monto_pago,               
                //"5"=>($reg->estado=='Pagado')?'<span class="label bg-green">Pagado</span>':
                //'<span class="label bg-red">Pendiente</span>'
              );
        }
        $results= array(
            "sEcho"=>1,
            "iTotalRecords"=>count($data),
            "iTotalDisplayRecords"=>count($data),
            "aaData"=>$data);
          echo json_encode($results);
        break;

    case 'mostrar':
        $rspta = $servicio -> mostrar($idservicio);
        echo json_encode($rspta);
        break;
}

?>