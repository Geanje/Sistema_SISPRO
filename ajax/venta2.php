<?php
if (strlen(session_id()) < 1)
	session_start();

require_once "../modelos/Venta2.php";

$venta = new Venta2();

$idventa = isset($_POST["idventa"]) ? limpiarCadena($_POST["idventa"]) : "";
$idsoporte = isset($_POST["idsoporte"]) ? limpiarCadena($_POST["idsoporte"]) : "";
$idcliente = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
$idusuario = $_SESSION["idusuario"];
$codigotipo_comprobante = isset($_POST["codigotipo_comprobante"]) ? limpiarCadena($_POST["codigotipo_comprobante"]) : "";
$codigotipo_pago = isset($_POST["codigotipo_pago"]) ? limpiarCadena($_POST["codigotipo_pago"]) : "";
// $serie=isset($_POST["serie"])? limpiarCadena($_POST["serie"]):"";
// $correlativo=isset($_POST["correlativo"])? limpiarCadena($_POST["correlativo"]):"";
$fecha_hora = isset($_POST["fecha_hora"]) ? limpiarCadena($_POST["fecha_hora"]) : "";
$fecha_ven = isset($_POST["fecha_ven"]) ? limpiarCadena($_POST["fecha_ven"]) : "";
$impuesto = isset($_POST["impuesto"]) ? limpiarCadena($_POST["impuesto"]) : "";
$moneda = isset($_POST["moneda"]) ? limpiarCadena($_POST["moneda"]) : "";

$total_venta_gravado = isset($_POST["total_venta_gravado"]) ? limpiarCadena($_POST["total_venta_gravado"]) : "";
$total_venta_exonerado = isset($_POST["total_venta_exonerado"]) ? limpiarCadena($_POST["total_venta_exonerado"]) : "";
$total_venta_inafectas = isset($_POST["total_venta_inafectas"]) ? limpiarCadena($_POST["total_venta_inafectas"]) : "";
$total_venta_gratuitas = isset($_POST["total_venta_gratuitas"]) ? limpiarCadena($_POST["total_venta_gratuitas"]) : "";
$total_descuentos = isset($_POST["total_descuentos"]) ? limpiarCadena($_POST["total_descuentos"]) : "";
$isc = isset($_POST["isc"]) ? limpiarCadena($_POST["isc"]) : "";
$total_igv = isset($_POST["total_igv"]) ? limpiarCadena($_POST["total_igv"]) : "";
$total_importe = isset($_POST["total_importe"]) ? limpiarCadena($_POST["total_importe"]) : "";
$igv_asig = isset($_POST["igv_asig"]) ? limpiarCadena($_POST["igv_asig"]) : "";
$rpta_sunat_codigo = isset($_POST["rpta_sunat_codigo"]) ? limpiarCadena($_POST["rpta_sunat_codigo"]) : "";



switch ($_GET["op"]) {
	case 'guardaryeditar':
		if (empty($idventa)) {
			require_once "../reportes/numeroALetras.php";
			$letras = NumeroALetras::convertir($total_importe);
			list($num, $cen) = explode('.', $total_importe);
			$leyenda = $letras . 'Y ' . $cen . '/100 SOLES';

			$rspta = $venta->insertar($idcliente, $idusuario, $idsoporte, $codigotipo_comprobante, $codigotipo_pago, $fecha_hora, $fecha_ven, $impuesto, $total_venta_gravado, $total_venta_inafectas, $total_venta_exonerado, $total_venta_gratuitas, $isc, $total_descuentos, $total_igv, $total_importe, $leyenda, $moneda, $_POST["idarticulo"], $_POST["cantidad"], $_POST["precio_venta"], $_POST["descuento"], $_POST["serieArticulo"], $igv_asig);
			echo $rspta ? "Venta registrada" : "No se pudieron registrar todos los datos de la venta";
		} else {
		}
		break;

	case 'anular':
		$rspta = $venta->anular($idventa);
		echo $rspta ? "Venta anulada" : "Venta no se puede anular";
		break;

	case 'mostrar':
		$rspta = $venta->mostrar($idventa);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
		break;

	case 'mostrarDatoCliente':
		require_once "../modelos/Persona.php";
		$cliente = new Persona();
		$rspta = $cliente->mostrar($idcliente);
		echo json_encode($rspta);

		break;

	case 'get_exchange_rate':
		// --
		$year = date('Y');
		$tipo_cambio = json_decode(file_get_contents('https://api.sunat.cloud/cambio/' . $year), true);
		$venta = 0.00;
		// --
		foreach ($tipo_cambio as $item) {
			// --
			$venta = $item['venta'];
			break;
		}
		// --
		$comision = 0.02;
		$tipo_cambio = round($venta + $comision, 2);
		// --
		$json = array(
			'status' => 'OK',
			'data' => $tipo_cambio
		);
		// --
		echo json_encode($json);
		break;


	case 'listarDetalle':
		// --

		$year = date('Y');
		$tipo_cambio_dolar = json_decode(file_get_contents('https://api.sunat.cloud/cambio/' . $year), true);
		$valor_venta = 0.00;
		// --
		foreach ($tipo_cambio_dolar as $item) {
			// --
			$valor_venta = $item['venta'];
			break;
		}
		// --
		$comision = 0.3;
		$tipo_cambio_dolar = round($valor_venta + $comision, 2);
		//Recibimos el idingreso
		$id = $_GET['id'];


		$rspta = $venta->listarDetalle($id);
		$total = 0.00;
		$impuesto = 0;
		$sumigv = 0;
		$tipo_cambio = 0;
		$sumdes = 0;
		$grava = 0;



		/*<th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Precio Venta</th>
                                    <th>Descuento</th>
                                    <th>Subtotal</th>*/
		echo '<thead style="background-color:#A9D0F5">
                                   
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Incentivos</th>
                                    <th>U. Medida</th>
                                    <th>Cantidad</th>
                                    <th>Val. Vta. U.</th>
                                    <th>Impuestos</th>
                                    <th>Precio Venta</th>
                                    <th>Val. Vta. Total</th>
                                    <th>Importe</th>
                                </thead>';

		while ($reg = $rspta->fetch_object()) {

			if ($reg->afectacion == 'Exonerado') {
				$valorVentaU = $reg->precio_venta;
				$valorVentaU = round($valorVentaU, 2);
				$valorVentaT = $reg->precio_venta * $reg->cantidad;
				$valorVentaT = round($valorVentaT, 2);
				$newigv = 0.00;
			} else {
				$valorVentaU = $reg->precio_venta / (1 + ($impuesto / 100));
				$valorVentaU = round($valorVentaU, 2);
				$valorVentaT = $reg->precio_venta / (1 + ($impuesto / 100)) * $reg->cantidad - $reg->descuento;
				$valorVentaT = round($valorVentaT, 2);
				$newigv =  ($reg->cantidad * $reg->precio_venta / (1 + ($impuesto / 100)) - $reg->descuento) * ($impuesto / 100);
				$newigv = round($newigv, 2);
				$tipo_cambio = round($tipo_cambio, 2);
			}

			// $sumgrava+=$valorVentaT;
			// $precioSinIgv= $reg->cantidad*$reg->precio_venta/(1+($impuesto/100));
			// $igv = $precioSinIgv*($impuesto/100);

			$sumigv += $newigv;
			$sumdes += $reg->descuento;
			$tipo_cambio += $reg->tipo_cambio;

			echo '<tr class="filas"><td></td><td>' . $reg->nombre . '</td><td>' . $reg->incentivo . '</td><td>' . $reg->unidad_medida . '</td><td>' . $reg->cantidad . '</td><td>' . $valorVentaU . '</td><td>' . $newigv . '</td><td>' . $reg->precio_venta . '</td><td>' . $valorVentaT . '</td><td>' . $reg->subtotal . '</td></tr>';
			$total = $total + ($reg->precio_venta * $reg->cantidad - $reg->descuento);
			$op_gravadas = $reg->op_gravadas;
			$op_inafectas = $reg->op_inafectas;
			$op_exoneradas = $reg->op_exoneradas;
			$op_gratuitas = $reg->op_gratuitas;
			$total_soles = $reg->v;
			$tipo_cambio = $reg->tipo_cambio;
			$isc = $reg->isc;
		}

		echo '<tfoot>
                                  <tr>
                                    <th colspan="7"></th>
                                    <th colspan="2">TOTAL VENTA GRAVADO</th>
                                    <th><h4 id="totalg">' . number_format($op_gravadas, 2, ".", ",") . '</h4><input type="hidden" name="total_venta_gravado" id="total_venta_gravado"></th>
                                  </tr>
                                   
                                   <tr>
                                    <th style="height:2px;"  colspan="7"></th>
                                    <th colspan="2">IGV</th>
                                    <th><h4 id="totaligv">' . number_format($sumigv, 2, ".", ",") . '</h4><input type="hidden" name="total_igv" id="total_igv"></th>
                                   </tr>
								   <tr>
								   <th style="height:2px;" colspan="7"></th>
                                    <th style="height:2px;" colspan="2">TIPO DE MONEDA</th>
									<th style="height:2px;"><h4 id="tipo_cambio">' . number_format($tipo_cambio_dolar, 2, ".", ",") . '</h4><input type="hidden" name="tipo_cambio" id="tipo_cambio"></th>
								
									</tr>
									<tr>
									<th style="height:2px;" colspan="7"></th>
                                    <th style="height:2px;" colspan="2">TOTAL IMPORTE EN SOLES</th>
                                    <th style="height:2px;"><h4 id="total_soles">' . number_format($total * $tipo_cambio_dolar, '2', '.', '') . '</h4><input type="hidden" name="total_soles id="total_soles"></th>
									</tr>
									<tr>
									<th style="height:2px;" colspan="7"></th>
                                    <th style="height:2px;" colspan="2">TOTAL IMPORTE EN DOLARES</th>
                                    <th style="height:2px;"><h4 id="totalimp">' . number_format($total, 2, ".", ",") . '</h4><input type="hidden" name="total_importe" id="total_importe"></th>
                                   </tr>
                                </tfoot>';


		break;

	case 'listar':
		$rspta = $venta->listar();
		//Vamos a declarar un array
		$data = array();
		$empresa = $venta->cabecera_perfil();
		// $empresa = $perfil-> fetch_assoc();

		while ($reg = $rspta->fetch_object()) {
			/*if($reg->codigotipo_comprobante=='1' || $reg->codigotipo_comprobante=='3'){
 			//$url='../reportes/Reporte_PDF_Comprobante-A4.php?id=';
 			$url='../reportes/Reporte_PDF_Comprobante-tiket.php?id=';
 			}else{
 				$url='../reportes/preFactura.php?id=';

 			}*/

			$url_ticket = '../reportes/Reporte_PDF_Comprobante-tiket.php?id=';
			$url_pdf = '../reportes/Reporte_PDF_Comprobante-A4.php?id=';
			$nombre_archivo = $empresa['ruc'] . '-' . '0' . $reg->codigotipo_comprobante . '-' . $reg->serie . '-' . $reg->correlativo;
			$data[] = array(
				"0" =>

				'<button id="' . $reg->idventa . '" data-nombrearchivo="' . $nombre_archivo . '" title="Enviar a Sunat" class="btn btn-default btn-xs API_SUNAT" onclick="enviarDatosASunat(\'' . $nombre_archivo . '\', ' . $reg->idventa . ')">
				 <img src="../public/img/logo_sunat.jpg" data-id="40" class="descargar-pdf" style="width: 29px; height: 29px;">
				 </button>' .

					'<a target="_blank" href="' . $url_ticket . $reg->idventa . '"> <button class="btn btn-info"><i class="fa-file-text-o"></i></button></a>' .
					'<a target="_blank" href="' . $url_pdf . $reg->idventa . '"> <button class="btn btn-info"><i class="fa fa-file-pdf-o"></i></button></a>',
				"1" => $reg->fecha,
				"2" => $reg->cliente,
				"3" => $reg->usuario,
				"4" => $reg->descripcion_tipo_comprobante,
				"5" => $reg->serie . '-' . $reg->correlativo,
				"6" => $reg->total_venta,
				"7" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
					'<span class="label bg-red">Anulado</span>',
				"8" => ($reg->rpta_sunat_codigo === null ? '<span class="label bg-gray">NO ENVIADO</span>' : ($reg->rpta_sunat_codigo == 1 ? '<span class="label bg-green">ENVIADO</span>' : ($reg->rpta_sunat_codigo == 2 ? '<span class="label bg-red">RECHAZADO</span>' :
					'<span class="label bg-yellow">ERROR</span>')))
			);
		}
		$results = array(
			"sEcho" => 1, //Información para el datatables
			"iTotalRecords" => count($data), //enviamos el total registros al datatable
			"iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
			"aaData" => $data
		);

		header('Content-Type: application/json');
		echo json_encode($results);

		break;

	case 'selectCliente':
		require_once "../modelos/Persona.php";
		$persona = new Persona();

		$rspta = $persona->listarC();

		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
		}
		break;

	case 'selectUsuario':
		require_once "../modelos/Usuario.php";
		$usuario = new Usuario();
		$rspta = $usuario->listar();
		$html = '';
		$html .= '<option value="all">Todos</option>';
		while ($reg = $rspta->fetch_object()) {
			$html .= '<option value=' . $reg->idusuario . '>' . $reg->nombre . '</option>';
		}
		echo $html;
		break;

	case 'selectTipoComprobante':
		$rspta = $venta->selectTipoComprobante();
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->codigotipo_comprobante . '>' . $reg->descripcion_tipo_comprobante . '</option>';
		}
		break;

	case 'selectTipoComprobanteSalida':
		$rspta = $venta->selectTipoComprobanteSalida();
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->codigotipo_comprobante . '>' . $reg->descripcion_tipo_comprobante . '</option>';
		}
		break;

	case 'selectTipoComprobanteReporte':
		$rspta = $venta->selectTipoComprobanteReporte();
		echo '<option value="all">Todos</option>';
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->codigotipo_comprobante . '>' . $reg->descripcion_tipo_comprobante . '</option>';
		}
		break;
	case 'selectTipoComprobanteReporteU':
		$rspta = $venta->selectTipoComprobanteReporteU();
		echo '<option value="all">Todos</option>';
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->codigotipo_comprobante . '>' . $reg->descripcion_tipo_comprobante . '</option>';
		}
		break;
	case 'selectTipoPago':
		$rspta = $venta->selectTipoPago(); // -- 
		//echo '<option value="all">Todos</option>';
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->codigotipo_pago . '>' . $reg->descripcion_tipo_pago . '</option>';
		}
		break;
	case 'selectMoneda':
		$rspta = $venta->selectMoneda();
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->idmoneda . '>' . $reg->descripcion . '</option>';
		}
		break;
	case 'selecTipoIGV':
		$rspta = $venta->selecTipoIGV();
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->porcentaje . '>' . $reg->porcentaje . '</option>';
		}
		break;
	case 'listarArticulosVenta':
		require_once "../modelos/Articulo.php";
		$articulo = new Articulo();

		$rspta = $articulo->listarActivosVenta();
		//Vamos a declarar un array
		$data = array();
		$i = 0;
		foreach ($rspta as $item) {
			if (@$item['unidad_medida'] == 'otros') {
				$unidadm = @$item['descripcion_otros'];
			} else {
				$unidadm = @$item['unidad_medida'];
			}
			$params =
				$data[] = array(
					"0" => '<button class="btn btn-warning" onclick="agregarDetalle(' . @$item['idarticulo'] . ',\'' . @$item['nombre'] . '\',\'' . $unidadm . '\',\'' . @$item['precio_venta'] . '\',\'' . @$item['afectacion'] . '\',\'' . @$item['codigo'] . '\')"><span class="fa fa-plus"></span></button>',
					"1" => @$item['nombre'],
					"2" => $unidadm,
					"3" => @$item['categoria'],
					"4" => @$item['codigo'],
					"5" => @$item['stock'],
					"6" => @$item['precio_venta'],
					"7" => @$item['controlador'],
					// "8"=>'Gravado<input type="radio" name="afectacion'.$reg->idarticulo.'" checked value="10">  Exonerado<input type="radio" name="afectacion'.$reg->idarticulo.'" value="20"> Inafecta<input type="radio" name="afectacion'.$reg->idarticulo.'" value="30">'
					"8" => @$item['afectacion'],
				);
		}
		$results = array(
			"sEcho" => 1, //Información para el datatables
			"iTotalRecords" => count($data), //enviamos el total registros al datatable
			"iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
			"aaData" => $data
		);
		echo json_encode($results);
		break;
}
