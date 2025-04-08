<?php
if(strlen(session_id()) < 1)
session_start();

require_once "../modelos/ListaCuotas.php";

$listaCuotas = new ListaCuotas();
$idingreso=isset($_POST["idingreso"])? limpiarCadena($_POST["idingreso"]):"";
$idpago=isset($_POST["idpago"])? limpiarCadena($_POST["idpago"]):"";

switch($_GET["op"]){
	case 'listar':
	$rspta = $listaCuotas->listar();

	$data = Array();
	$num=0;
	while ($reg = $rspta->fetch_object()) {
		$num++;
		$data[] =  array(
			"0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idpago.')"><i class="fa fa-eye"></i></button>'.
          ' <button class="btn btn-danger" onclick="cancelar('.$reg->idpago.')"><i class="fa fa-shopping-cart"></i></button>',
	        "1"=>$num,
	        "2"=>$reg->serie_comprobante."-".$reg->num_comprobante,
	        "3"=>$reg->valor_cuota,
	        "4"=>$reg->fecha_pago,
	        "5"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado<span>':'<span class="label bg-red">Pendiente<span>'

		);
	}
		 $results= array(
	        "sEcho"=>1, //Informacion para el datatable
	        "iTotalRecords"=>count($data),//Enviamos el total de registtros en el datatable
	        "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
	        "aaData"=>$data);
	      echo json_encode($results);

	break;
	case 'cancelarLetra':
      $rspta=$ingreso->cancelarLetra($idingreso);
      echo $rspta ? "Ingreso anulado" : "Ingreso no se puede anular";
    break;

}

