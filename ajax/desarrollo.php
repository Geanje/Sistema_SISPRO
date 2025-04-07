<?php
require_once "../modelos/desarrollo.php";
 
$soporte=new desarrollo();

$idsoporte=isset($_POST["idsoporte"])? limpiarCadena($_POST["idsoporte"]):"";
$codigo_soporte=isset($_POST["codigo_soporte"])? limpiarCadena($_POST["codigo_soporte"]):"";
$nombre_cliente=isset($_POST["nombre_cliente"])? limpiarCadena($_POST["nombre_cliente"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$tecnico_respon=isset($_POST["tecnico_respon"])? limpiarCadena($_POST["tecnico_respon"]):"";
$fecha_ingreso=isset($_POST["fecha_ingreso"])? limpiarCadena($_POST["fecha_ingreso"]):"";
$fecha_salida=isset($_POST["fecha_salida"])? limpiarCadena($_POST["fecha_salida"]):"";
$marca=isset($_POST["marca"])? limpiarCadena($_POST["marca"]):"";
$problema=isset($_POST["problema"])? limpiarCadena($_POST["problema"]):"";
$solucion=isset($_POST["solucion"])? limpiarCadena($_POST["solucion"]):"";
$tipo_equipo=isset($_POST["tipo_equipo"])? limpiarCadena($_POST["tipo_equipo"]):"";
$estado_servicio=isset($_POST["estado_servicio"])? limpiarCadena($_POST["estado_servicio"]):"";
$estado_pago=isset($_POST["estado_pago"])? limpiarCadena($_POST["estado_pago"]):"";
$total=isset($_POST["total"])? limpiarCadena($_POST["total"]):"";
$cuota=isset($_POST["cuota"])? limpiarCadena($_POST["cuota"]):"";
$saldo=isset($_POST["saldo"])? limpiarCadena($_POST["saldo"]):"";
$estado_entrega=isset($_POST["estado_entrega"])? limpiarCadena($_POST["estado_entrega"]):"";
$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$accesorio=isset($_POST["accesorio"])? limpiarCadena($_POST["accesorio"]):"";
$recomendacion=isset($_POST["recomendacion"])? limpiarCadena($_POST["recomendacion"]):"";
$garantia=isset($_POST["garantia"])? limpiarCadena($_POST["garantia"]):"";
 
switch ($_GET["op"])
{ 
    case 'guardaryeditar':
      if(empty($idsoporte))
      {
        $rspta=$soporte->insertar($codigo_soporte,$nombre_cliente,$telefono,$tecnico_respon,$fecha_ingreso,$fecha_salida,$marca,$problema,$solucion,$tipo_equipo,$estado_servicio,$estado_pago,$total,$cuota,$saldo,$estado_entrega,$direccion,$accesorio,$recomendacion,$garantia);
        echo $rspta ? "Servicio Registrado" : "Servicio no se pudo registrar";
      }
      else {
        {
          $rspta=$soporte->editar(
            $idsoporte,
            $nombre_cliente,
            $telefono,
            $tecnico_respon,
            $fecha_ingreso,
            $fecha_salida,
            $marca,
            $problema,
            $solucion,
            $tipo_equipo,
            $codigo_soporte,
            $estado_servicio,
            $estado_pago,
            $total,
            $cuota,
            $saldo,
            $estado_entrega,
            $direccion,
            $accesorio,
            $recomendacion,
            $garantia
          );

          echo $rspta ? "Servicio actualizada" : "Servicio no se pudo actualizar";
        }
      }
    break;
    case 'eliminar':
      $rspta=$desa->eliminar($codigo_soporte);
      //echo $codigo_soporte;
      echo $rspta ? "Servicio eliminada" : "Servicio no se pudo eliminar";
    break;

    case 'mostrar':
      $rspta=$soporte->mostrar($idsoporte);
      //codificar el resultado usando json
      echo json_encode($rspta);
    break;

    case 'listar':
      $rspta=$soporte->listar();
      //Vamos a declarar un array
      $data=Array();
      while($reg=$rspta->fetch_object())
      {
        $data[]=array(
          "0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idsoporte.')"><i class="fa fa-pencil"></i></button>'.
          ' <button class="btn btn-danger" onclick="eliminar('.$reg->idsoporte.')"><i class="fa fa-trash"></i></button>'.
          '<a target="_blank" href="../reportes/servicios-soporte.php?idsoporte='.$reg->idsoporte.'"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>',
         
          "1"=>$reg->fecha_ingreso,
          "2"=>$reg->nombre_cliente,  
          "3"=>$reg->marca,
          "4"=>$reg->estado_servicio,               
          "5"=>$reg->estado_entrega,
          "6"=>$reg->estado_pago,
          "7"=>$reg->tecnico_respon,
          "8"=>$reg->solucion,
          "9"=>$reg->tipo_equipo,
          "10"=>$reg->telefono,
          "11"=>$reg->problema,
          "12"=>$reg->total,
          "13"=>$reg->cuota,
          "14"=>$reg->saldo,
          "15"=>$reg->fecha_ingreso,
          "16"=>$reg->direccion,
          "17"=>$reg->accesorio,
          "18"=>$reg->recomendacion,
          "19"=>$reg->garantia
        );
      }
      $results= array(
        "sEcho"=>1, //Informacion para el datatable
        "iTotalRecords"=>count($data),//Enviamos el total de registtros en el datatable
        "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
        "aaData"=>$data);
      echo json_encode($results);
    
    
    // $search1 = $company->search( $ruc );
    // $search2 = $company->search( $dni );
    
    
    // if( $search1->success == true )
    // {
    //   echo "Empresa: " . $search1->result->RazonSocial;
    // }
    
    // if( $search2->success == true )
    // {
    //   echo "Persona: " . $search1->result->RazonSocial;
    // }
    
    // echo $search1->json();
    // echo $search1->xml('empresa');

break;
}

 ?>
