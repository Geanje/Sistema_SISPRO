<?php

if (strlen(session_id()) < 1)
session_start();

require_once "../modelos/Registro_servicios.php";

$pagos = new Registro();

$id_r_servicio = isset($_POST['id_r_servicio'])? limpiarCadena($_POST['id_r_servicio']):"";
$idcliente = isset($_POST['idcliente'])? limpiarCadena($_POST['idcliente']):"";
// $monto_pago = isset($_POST['monto_pago'])? limpiarCadena($_POST['monto_pago']):"";
$concepto = isset($_POST['concepto'])? limpiarCadena($_POST['concepto']):"";
$fecha_inicio = isset($_POST['fecha_inicio'])? limpiarCadena($_POST['fecha_inicio']):"";
$fecha_termino = isset($_POST['fecha_termino'])? limpiarCadena($_POST['fecha_termino']):"";
//$estado = isset($_POST['estado'])? limpiarCadena($_POST['estado']):"";
$idservicio = isset($_POST['idservicio'])? limpiarCadena($_POST['idservicio']):"";
$monto_pago = isset($_POST['monto_pago'])? limpiarCadena($_POST['monto_pago']):"";


$idusuario=$_SESSION["idusuario"];



switch ($_GET['op']) {
    case 'guardaryeditar':
        if(empty($id_r_servicio)) {
            $rspta = $pagos -> insertar($idusuario, $idcliente, $idservicio, $fecha_inicio,$fecha_termino,$monto_pago);
            echo $rspta ? "El servicio ha sido registrado con éxito" : "Algo salió mal";
        } else {
            $rspta = $pagos ->editar($id_r_servicio,$idusuario, $idcliente, $idservicio, $fecha_inicio,$fecha_termino,$monto_pago);
            echo $rspta ? "El servico ha sido actualizado" : "El servicio no se pudo actualizar";
        }
        break;
    
    case 'mostrar':
        $rspta = $pagos -> mostrar($id_r_servicio);
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $pagos -> listar();

        $data=Array();
        while($reg=$rspta->fetch_object()) {
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->id_r_servicio.')"><i class="fa fa-pencil"></i></button>'.
                ' <button class="btn btn-danger" onclick="desactivar('.$reg->id_r_servicio.')"><i class="fa fa-times"></i></button>'.
                ' <button class="btn btn-success" onclick="activar('.$reg->id_r_servicio.')"><i class="fa fa-check"></i></button>',
                // ' <button class="btn btn-warning" onclick="mostrarVentana()"><i class="fa fa-shopping-cart"></i></button>',
                // '<a target="_blank" href="../reportes/servicios-soporte.php?idsoporte='.$reg->id_p_servicio.'"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>',
               
                "1"=>$reg->cliente,
                "2"=>$reg->direccion,  
                "3"=>$reg->concepto,
                "4"=>$reg->fecha_inicio,
                "5"=>$reg->fecha_termino,
                "6"=>$reg->costo,               
                "7"=>$reg->serie."-".$reg->correlativo,               
                "8"=>($reg->estado=='1')?'<span class="label bg-green">Activo</span>':
                '<span class="label bg-red">Inactivo</span>'
              );
            }
            $results= array(
              "sEcho"=>1, //Informacion para el datatable
              "iTotalRecords"=>count($data),//Enviamos el total de registtros en el datatable
              "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
              "aaData"=>$data);
            echo json_encode($results);

        break;
    
    case 'selectCliente':
        require_once "../modelos/Persona.php";
        $persona = new Persona();
          
        $rspta = $persona->listarcs();
          
        while ($reg = $rspta->fetch_object())
            {
            echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
            }
        break;

    case 'listarServicio':         
        $rspta = $pagos->listarServicio();
        while ($reg = $rspta->fetch_object())
            {
            echo '<option value=' . $reg->idservicio . '>' . $reg->nombre . '</option>';
            }
        break;
    case 'mostrarDatoServicio':
        $rspta=$pagos->mostrarServicio($idservicio);
            echo json_encode($rspta);
        break;


    case 'mostrarDatoCliente':
        require_once "../modelos/Persona.php";
        $cliente = new Persona();
        $rspta=$cliente->mostrar($idcliente);
            echo json_encode($rspta);
    
        break;

    case 'desactivar':
        $rspta = $pagos -> desactivar($id_r_servicio);
        //var_dump($rspta);
        echo $rspta ? "Servicio Desactivado" : "El servicio no se pudo Desactivar";
        break;

    case 'activar':
        $rspta = $pagos -> activar($id_r_servicio);
        //var_dump($rspta);
        echo $rspta ? "Servicio Activado" : "El servicio no se pudo Activar";
        break;
}


?>