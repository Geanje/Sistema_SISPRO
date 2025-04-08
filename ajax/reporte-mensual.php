<?php
require_once "../modelos/ReporteMensual.php";

$reporte = new ReporteMensual();


// $mes=isset($_POST["mes"])? limpiarCadena($_POST["mes"]):"";

switch ($_GET["op"]) {
	case 'listarProductos':
		$mes = $_REQUEST["mes"];
		$anno = $_REQUEST["anno"];

		$rspta = $reporte->listar($mes, $anno);
		$data = array();
		while ($reg = $rspta->fetch_object()) {
			$data[] = array(
				#"0"=>"<img class='img-thumbnail' src='../files/articulos/".$reg->imagen."' height='90px' width='100px' >",
				"0" => $reg->nombre,
				"1" => $reg->codigo,
				"2" => $reg->summaArticulos

			);
		}
		$results = array(
			"sEcho" => 1, //Informacion para el datatable
			"iTotalRecords" => count($data), //Enviamos el total de registtros en el datatable
			"iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
			"aaData" => $data
		);
		echo json_encode($results);
		$encoded_data = json_encode($data, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
		file_put_contents('../reportes/temp_rptjson/ListarProductos.json', $encoded_data);

		break;
}
