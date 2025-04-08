<?php 

if (strlen(session_id()) < 1)
  	session_start();
	require_once "../modelos/Confidencial.php";

	$confidencial = new Confidencial();

	$idconfidencial = isset($_POST["idconfidencial"]) ? limpiarCadena($_POST["idconfidencial"]) : "";
	$idpersona = isset($_POST["idpersona"]) ? limpiarCadena($_POST["idpersona"]) : "";
	$idusuario = $_SESSION["idusuario"];
	// $serie = isset($_POST["serie"]) ? limpiarCadena($_POST["serie"]) : "";
	// $correlativo = isset($_POST["correlativo"]) ? limpiarCadena($_POST["correlativo"]) : "";
	$fecha = isset($_POST["fecha"]) ? limpiarCadena($_POST["fecha"]) : "";
	$gastos_totales = isset($_POST["gastos_totales"]) ? limpiarCadena($_POST["gastos_totales"]) : "";
	$compras = isset($_POST["compras"]) ? limpiarCadena($_POST["compras"]) : "";

	switch($_GET["op"]){
		case 'guardaryeditar':
		if(empty($idconfidencial)){
			$rspta = $confidencial->insertar($idpersona,$idusuario,$fecha,$gastos_totales,$compras);
			echo $rspta ? "La carga fue exitosa." : "La carga no fue exitosa.";
			// echo $idpersona."-".$idusuario."-".$fecha."-".$gastos_totales."-".$compras;
		}else{
			$rspta = $confidencial->editar($idconfidencial,$idpersona,$idusuario,$fecha,$gastos_totales,$compras);
			echo $rspta ? "La carga fue editada." : "No se pudo editar la carga.";
		}


		break;
		case 'mostrarp':
			require_once "../modelos/Persona.php";
			$personap = Persona::listarp();

			while($reg = $personap->fetch_object()){
				echo '<option value="'.$reg->idpersona.'">'.$reg->nombre.'</option>';
			} 

		break;
		case 'listar':
			$rspta = $confidencial->listar();
			$data = Array();
			while($reg = $rspta->fetch_object()){

				$total = $reg->gastos_totales + $reg->compras;
				if($reg->compras != 0){
					$porcentaje = $total / $reg->compras;
				}else{
					$porcentaje =0;
				}
 				// $url='../reportes/Confidencial.php?id=';

				$data[] = array(
					// "0" => '<a target="_blank" href="'.$url.$reg->idconfidencial.'"> <button class="btn btn-info"><i class="fa fa-file"></i></button>',
					"0" => $reg->serie." - ".$reg->correlativo,
					"1" => $reg->fecha,
					"2" => $reg->proveedor,
					"3" => $reg->usuario,
					"4" => number_format($reg->gastos_totales,2,'.',','),
					"5" => number_format($reg->compras,2,'.',','),
					"6" => number_format($porcentaje,2,'.',',')."%"

				);
			}
			$results = array(
				"sEcho" => 1,
				"iTotalRecords" => count($data),
				"itotalDisplayRecords" => count($data),
				"aaData" => $data
			);
			echo json_encode($results);
			
		break;
}