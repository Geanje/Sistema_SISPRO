<?php
if (strlen(session_id()) < 1)
  session_start();

require_once "../modelos/Guia.php";

$guia=new Guia();

$idguia=isset($_POST["idguia"])? limpiarCadena($_POST["idguia"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idtransportista=isset($_POST["idtransportista"])? limpiarCadena($_POST["idtransportista"]):"";
$idusuario=$_SESSION["idusuario"];
$serie=isset($_POST["serie"])? limpiarCadena($_POST["serie"]):"";
$correlativo=isset($_POST["correlativo"])? limpiarCadena($_POST["correlativo"]):"";
$codigo_traslado=isset($_POST["codigo_traslado"])? limpiarCadena($_POST["codigo_traslado"]):"";
$codigotipo_comprobante=isset($_POST["codigotipo_comprobante"])? limpiarCadena($_POST["codigotipo_comprobante"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$moneda=isset($_POST["moneda"])? limpiarCadena($_POST["moneda"]):"";


$total_venta_gravado=isset($_POST["total_venta_gravado"])? limpiarCadena($_POST["total_venta_gravado"]):"";
$total_venta_exonerado=isset($_POST["total_venta_exonerado"])? limpiarCadena($_POST["total_venta_exonerado"]):"";
$total_venta_inafectas=isset($_POST["total_venta_inafectas"])? limpiarCadena($_POST["total_venta_inafectas"]):"";
$total_venta_gratuitas=isset($_POST["total_venta_gratuitas"])? limpiarCadena($_POST["total_venta_gratuitas"]):"";
$total_descuentos=isset($_POST["total_descuentos"])? limpiarCadena($_POST["total_descuentos"]):"";
$isc=isset($_POST["isc"])? limpiarCadena($_POST["isc"]):"";
$total_igv=isset($_POST["total_igv"])? limpiarCadena($_POST["total_igv"]):"";
$total_importe=isset($_POST["total_importe"])? limpiarCadena($_POST["total_importe"]):"";

//Funcion Boton Guardar 

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idguia)){
			// $rspta=$guia->insertar($idcliente,$idtransportista,$idusuario,$codigo_traslado,$codigotipo_comprobante,$fecha_hora,$impuesto,$total_venta_gravado,$total_venta_inafectas,$total_venta_exonerado,$total_venta_gratuitas,$isc,$total_descuentos,$total_igv,$total_importe,$moneda,$_POST["idarticulo"],$_POST["u_medida"],$_POST["cantidad"]);
			$rspta=$guia->insertar($idcliente,
								   $idtransportista,
								   $idusuario,
								   $codigo_traslado,
								   $codigotipo_comprobante,
								   $fecha_hora,
								   $impuesto,
								   $total_venta_gravado,
								   $total_venta_inafectas,
								   $total_venta_exonerado,
								   $total_venta_gratuitas,
								   $isc,
								   $total_descuentos,
								   $total_igv,
								   $total_importe,
								   $moneda,
								   $_POST["idarticulo"],
								   $_POST["u_medida"],
								   $_POST["cantidad"]);
			echo $rspta ? "Venta registrada" : "No se pudieron registrar todos los datos de la venta";
		}
		else {
		}
	break;

	

	case 'anular':
		$rspta=$guia->anular($idguia);
 		echo $rspta ? "Venta anulada" : "Venta no se puede anular";
	break;

	case 'mostrar':
		$rspta=$guia->mostrar($idguia);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'mostrarDatoCliente':
		require_once "../modelos/Persona.php";
		$cliente = new Persona();
		$rspta=$cliente->mostrar($idcliente);
 		echo json_encode($rspta);

	break;

	case 'mostrarDatoTransportista':
		$rspta=$guia->mostrarDatoTransportista($idtransportista);
 		echo json_encode($rspta);
	break;

	case 'selectCliente':
		require_once "../modelos/Persona.php";
		$persona = new Persona();

		$rspta = $persona->listarC();

		while ($reg = $rspta->fetch_object())
				{
				echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
				}
	break;

	case 'selectTransportista':
		require_once "../modelos/Persona.php";
		$persona = new Persona();

		$rspta = $persona->listart();

		while ($reg = $rspta->fetch_object())
				{
				echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
				}
	break;

	case 'selectUsuario':
		require_once "../modelos/Usuario.php";
		$usuario = new Usuario();
		$rspta=$usuario->listar();
		$html = '';
		$html .= '<option value="all">Todos</option>';
		while ($reg=$rspta->fetch_object()) {
			$html .= '<option value='.$reg->idusuario.'>'.$reg->nombre.'</option>';
		}
		echo $html;
	break;

	case 'selectTipoComprobante':
		$rspta = $guia->selectTipoComprobante();
		while ($reg = $rspta->fetch_object()) {
			echo '<option value='.$reg->codigotipo_comprobante.'>'.$reg->descripcion_tipo_comprobante.'</option>';
		}
	break;

	case 'selectMotivoTraslado':
			$rspta = $guia->selectMotivoTraslado();
			while ($reg = $rspta->fetch_object()) {
				echo '<option value='.$reg->$codigo_traslado.'>'.$reg->descripcion_traslado.'</option>';
			}
	break;

	case 'selectPuntoPartida':
				$rspta = $guia->selectPuntoPartida();
				while ($reg = $rspta->fetch_object()) {
					echo '<option value='.$reg->$idperfil.'>'.$reg->direccion.'</option>';
				}
	break;

	case 'selectMoneda':
			$rspta = $guia->selectMoneda();
			while ($reg=$rspta->fetch_object()) {
				echo '<option value='.$reg->idmoneda.'>'.$reg->descripcion.'</option>';
			}
	break;	

	
	 
	case 'get_exchange_rate':
        // --
        $year = date('Y');
        $tipo_cambio = json_decode( file_get_contents('https://api.sunat.cloud/cambio/' . $year), true );
        $guia = 0.00;
        // --
        foreach ($tipo_cambio as $item) {
            // --
            $guia = $item['venta'];
            break;
        }
        // --
        $comision = 0.02;
        $tipo_cambio = round($guia + $comision, 2);
        // --
		$json = array(
            'status' => 'OK',
            'data' => $tipo_cambio
        );
        // --
        echo json_encode($json);
	break;
  
	//FUNCION PRINCIPAL DE GUIA REMISION

	case 'listarguiaprincipal':
		$rspta=$guia->listarguiaprincipal();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){

			$url_ticket = '../reportes/Reporte_PDF_Guia-tiket.php?id=';
		   	$url_pdf = '../reportes/Reporte_PDF_Guia-A4.php?id=';


			// $data[]=array(
			// 	"0"=>
			// 	'<a target="_blank" href="'.$url_ticket.$reg->idventa.'"> <button class="btn btn-info"><i class="fa-file-text-o"></i></button></a>'.
			// 	'<a target="_blank" href="'.$url_pdf.$reg->idventa.'"> <button class="btn btn-info"><i class="fa fa-file-pdf-o"></i></button></a>',
 			// 	"1"=>$reg->fecha,
 			// 	"2"=>$reg->cliente,
 			// 	"3"=>$reg->usuario,
 			// 	"4"=>$reg->descripcion_tipo_comprobante,
 			// 	"5"=>$reg->serie.'-'.$reg->correlativo,
 			// 	"6"=>$reg->total_venta,
 			// 	"7"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
 			// 	'<span class="label bg-red">Anulado</span>'
 			// 	);

			$data[]=array(
				"0"=>
				'<a target="_blank" href="'.$url_ticket.$reg->id.'"> <button class="btn btn-info"><i class="fa-file-text-o"></i></button></a>'.
				'<a target="_blank" href="'.$url_pdf.$reg->id.'"> <button class="btn btn-info"><i class="fa fa-file-pdf-o"></i></button></a>',
				"1"=>$reg->fecha,
				"2"=>$reg->cliente,
				// "3"=>$reg->usuario,
				"3"=>$reg->moti_translado,
				"4"=>$reg->serie_correlativo,
				"5"=>$reg->serie_correlativo_guia,
				// "6"=>$reg->total_venta,
				// "7"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
				// '<span class="label bg-red">Anulado</span>'
				);
 		}
 		$results = array(
 			"sEcho"=>1, //Informaci��n para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 			
 			header('Content-Type: application/json');
 		echo json_encode($results);

	break;

	//Funcion para al presionar boton "Seleccionar Comprobante"

	case 'listarComprobantes':
		$rspta=$guia->listarComprobantes();
		// -- Obtener el ultimo correlativo estatico--
 		//Vamos a declarar un array
 		$data= Array();
 		while ($reg=$rspta->fetch_object()){
			
 			$data[]=array(
 				"0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idventa.',\''.$reg->cliente.'\');" ><span class="fa fa-plus"></span></button>',
 				"1"=>$reg->fecha,
 				"2"=>$reg->cliente,
 				"3"=>$reg->usuario,
 				"4"=>$reg->descripcion_tipo_comprobante,
 				"5"=>$reg->serie.'-'.$reg->correlativo,
			 	"6"=>$reg->total_venta,
				"7"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
 				'<span class="label bg-red">Anulado</span>'
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);
	break;

	case 'listarDetalleComprobantes':
		$idguia=$_GET['id'];
		$cont=0;
		$rspta = $guia->listardetallecomprobantes($idguia);

		echo '<thead style="background-color:#A9D0F5">
                                   
		<th>Opciones</th>
		<th>Artículo</th>
		<th>U. Medida</th>
		<th>Cantidad</th>
		</thead>';



		while ($reg = $rspta->fetch_object())
		{


		echo '<tr class="filas" id="fila'.$cont.'">'.
		    	'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('.$cont.')">X</button></td>'.
		    	'<td><input type="hidden" name="idarticulo[]" value="'.$reg->idarticulo.'">'.$reg->nombre.'&nbsp;&nbsp;<input type="hidden" name="seriearticulo[]" value="'.$reg->serie.'">'.$reg->serie.'</td>'.
		    	'<td><input type="hidden" name="u_medida[]" value="'.$reg->unidad_medida.'">'.$reg->unidad_medida.'</td>'.
		    	'<td ><input type="number" name="cantidad[]"  id="cantidad'.$cont.'" style="text-align: center;width:50px;" value="'.$reg->cantidad.'" ></td>'.
		    	'</tr>';	
				$cont++;

		}



			

break;
}
?>
