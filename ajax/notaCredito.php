<?php
if (strlen(session_id()) < 1)
	session_start();

require_once "../modelos/NotaCredito.php";
$notaCredito = new NotaCredito();


require_once "../modelos/Venta2.php";
$venta2 = new Venta2();



$idventa = isset($_POST["idventa"]) ? limpiarCadena($_POST["idventa"]) : "";
$idventarelacionado = isset($_POST["idventarelacionado"]) ? limpiarCadena($_POST["idventarelacionado"]) : "";
$idcliente = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
$idusuario = $_SESSION["idusuario"];
// $codigotipo_comprobante=isset($_POST["codigotipo_comprobante"])? limpiarCadena($_POST["codigotipo_comprobante"]):"";
$idtiponotacredito = isset($_POST["idtiponotacredito"]) ? limpiarCadena($_POST["idtiponotacredito"]) : "";

$serie = isset($_POST["serie"]) ? limpiarCadena($_POST["serie"]) : "";
$correlativo = isset($_POST["correlativo"]) ? limpiarCadena($_POST["correlativo"]) : "";
// $fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$fecha_hora = date('Y-m-d');
$impuesto = isset($_POST["impuesto"]) ? limpiarCadena($_POST["impuesto"]) : "";
$idmoneda = isset($_POST["idmoneda"]) ? limpiarCadena($_POST["idmoneda"]) : "";

$total_venta_gravado = isset($_POST["total_venta_gravado"]) ? limpiarCadena($_POST["total_venta_gravado"]) : "";
$total_venta_exonerado = isset($_POST["total_venta_exonerado"]) ? limpiarCadena($_POST["total_venta_exonerado"]) : "";
$total_venta_inafectas = isset($_POST["total_venta_inafectas"]) ? limpiarCadena($_POST["total_venta_inafectas"]) : "";
$total_venta_gratuitas = isset($_POST["total_venta_gratuitas"]) ? limpiarCadena($_POST["total_venta_gratuitas"]) : "";
$total_descuentos = isset($_POST["total_descuentos"]) ? limpiarCadena($_POST["total_descuentos"]) : "";
$isc = isset($_POST["isc"]) ? limpiarCadena($_POST["isc"]) : "";
$total_igv = isset($_POST["total_igv"]) ? limpiarCadena($_POST["total_igv"]) : "";
$total_importe = isset($_POST["total_importe"]) ? limpiarCadena($_POST["total_importe"]) : "";
$sustento = isset($_POST["sustento"]) ? limpiarCadena($_POST["sustento"]) : "";

$isession = isset($_POST["isession"]) ? limpiarCadena($_POST["isession"]) : "";

// $tiponcredito3=isset($_POST["tiponcredito3"])? limpiarCadena($_POST["tiponcredito3"]):"";
$igv_asig = 18;
$rpta_sunat_codigo = isset($_POST["rpta_sunat_codigo"]) ? limpiarCadena($_POST["rpta_sunat_codigo"]) : "";

switch ($_GET["op"]) {

	case 'guardaryeditar':

		if (empty($idventa)) {
			require_once "../reportes/numeroALetras.php";
			$letras = NumeroALetras::convertir($total_importe);
			list($num, $cen) = explode('.', $total_importe);
			$leyenda = $letras . 'Y ' . $cen . '/100 SOLES';

			// if($_POST["tiponc3"] == null){
			// 	$rspta=$notaCredito->insertar($idcliente,$idusuario,$serie,$correlativo,$fecha_hora,$impuesto,$total_venta_gravado,$total_venta_inafectas,$total_venta_exonerado,$total_venta_gratuitas,$isc,$total_importe,$idmoneda,$idtiponotacredito,$sustento,$idventarelacionado,$_POST["idarticuloo"],$_POST["cantidadd"],$_POST["precio_ventaa"],$_POST["descuentoo"],"");
			// 	echo $rspta ? "Venta registrada" : "No se pudieron registrar todos los datos de la venta";
			// }else{
			$rspta = $notaCredito->insertar($idcliente, $idusuario, $serie, $correlativo, $fecha_hora, $impuesto, $igv_asig, $total_venta_gravado, $total_venta_inafectas, $total_venta_exonerado, $total_venta_gratuitas, $isc, $total_igv, $total_importe, $leyenda, $idmoneda, $idtiponotacredito, $sustento, $idventarelacionado, $_POST["idarticuloo"], $_POST["cantidadd"], $_POST["precio_ventaa"], $_POST["descuentoo"], $_POST["tiponc3"], $_POST["seriee"]);
			//echo $rspta ? "Venta registrada" : "No se pudieron registrar todos los datos de la venta";
			$rsptaVenta = false;
			if ($rspta) {
				$rsptaVenta = $venta2->anular($idventarelacionado);
			}
		} else {
		}
		break;


	case 'numNotaCredito':
		$rspta = $venta->numNotaCredito();
		echo $rspta ? "Venta anulada" : "Venta no se puede anular";
		break;

	case 'anular':
		$rspta = $notaCredito->anular($idventa);
		echo $rspta ? "Nota de crédito anulada" : "Nota de crédito no se puede anular";
		break;

	case 'mostrarDocRel':
		$rspta = $notaCredito->mostrarDocRelacionado($idventa, $idventarelacionado);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
		break;

	case 'isession':

		$_SESSION['isession'] = $isession;
		// echo "Bien hecho".$_SESSION['isession'];
		session_regenerate_id();
		break;
	case 'devoisession':
		echo $_SESSION['isession'];
		break;

	case 'unsetsession':
		$_SESSION['isession'] = "";
		unset($_SESSION['isession']);
		// session_destroy();


		break;
	case 'listar':
		$rspta = $notaCredito->listar();
		$data = array();
		$empresa = $venta2->cabecera_perfil();
		while ($reg = $rspta->fetch_object()) {
			if ($reg->idmotivo_doc == '1' || $reg->idmotivo_doc == '2' || $reg->idmotivo_doc == '3' || $reg->idmotivo_doc == '4' || $reg->idmotivo_doc == '5' || $reg->idmotivo_doc == '6' || $reg->idmotivo_doc == '7') {
				$url = '../reportes/Reporte_PDF_NotaCredito.php?id=';
			} else {
				$url = '../reportes/preFactura.php?id=';
			}
			$nombre_archivo = $empresa['ruc'] . '-' . '0' . $reg->codigotipo_comprobante . '-' . $reg->serie . '-' . $reg->correlativo;
			$data[] = array(
				"0" => ($reg->estado == 'AceptadoNC') ?
					'<button id="' . $reg->idventa . '" data-nombrearchivo="' . $nombre_archivo . '" title="Enviar a Sunat" class="btn btn-default btn-xs API_SUNAT" onclick="enviarDatosASunat(\'' . $nombre_archivo . '\', ' . $reg->idventa . ')">
				<img src="../public/img/logo_sunat.jpg" data-id="40" class="descargar-pdf" style="width: 29px; height: 32px;">
			</button>' .
					'<a target="_blank" href="' . $url . $reg->idventa . '"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>' :

					'<a target="_blank" href="' . $url . $reg->idventa . '"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>',
				"1" => $reg->fecha,
				"2" => $reg->cliente,
				"3" => $reg->usuario,
				"4" => $reg->descripcion_tipo_comprobante,
				"5" => $reg->serie . '-' . $reg->correlativo,
				"6" => $reg->motivo,
				"7" => '<center><button class="btn btn-info" onclick="mostrarDocRel(' . $reg->idventa . ',' . $reg->doc_relacionado . ')" data-toggle="modal" data-target="#docRelacionadoModal">' . $reg->doc_relacionado . '</button></center>',
				"8" => ($reg->estado == 'AceptadoNC') ? '<span class="label bg-green">Aceptado</span>' :
					'<span class="label bg-red">Anulado</span>',
				"9" => ($reg->rpta_sunat_codigo === null ? '<span class="label bg-gray">NO ENVIADO</span>' : ($reg->rpta_sunat_codigo == 1 ? '<span class="label bg-green">ENVIADO</span>' : ($reg->rpta_sunat_codigo == 2 ? '<span class="label bg-red">RECHAZADO</span>' :
						'<span class="label bg-yellow">ERROR</span>')))

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

	case 'selectTipoNotaCredito':
		$rspta = $notaCredito->listarTipoNotaCredito();
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->idmotivo_documento . '>' . $reg->motivo . '</option>';
		}
		break;
	case 'anular':
		$rspta = $notaCredito->anular($idventa);
		echo $rspta ? "Nota de credito anulada" : "Nota de credito no se pudo anular";
		break;

	case 'listarComprobantes':
		$rspta = $notaCredito->listarComprobantes();
		// -- Obtener el ultimo correlativo estatico--
		$rspta_correlativo = $notaCredito->ultimoCorrelativo();
		//Vamos a declarar un array
		$data = array();
		while ($reg = $rspta->fetch_object()) {
			$ultimo_correlativo = '00000001';
			$s = substr($reg->serie, -4, 1);
			$serie = $reg->serie;
			if ($rspta_correlativo) {
				if ($rspta_correlativo["correlativo"] == '99999999') {
					$ser = substr($rspta_correlativo["serie"], 1) + 1;
					$seri = str_pad((string)$ser, 3, "0", STR_PAD_LEFT);

					if ($s == "F") {
						$serie = "F" . $seri;
					} else {
						$serie = "B" . $seri;
					}
					$ultimo_correlativo = '00000001';
				} else {
					// -- Aumenta de numeros hacia la izquierda--
					$corre = $rspta_correlativo["correlativo"] + 1;
					$serie = $reg->serie;
					$ultimo_correlativo = str_pad($corre, 8, "0", STR_PAD_LEFT);
				}
			}
			$data[] = array(
				// "0"=>'<button class="btn btn-warning" onclick=""><span class="fa fa-plus"></span></button>',
				"0" => '<button class="btn btn-warning" data-dismiss="modal" name="agregarDocu" onclick="agregarDocumento(' . $reg->idventa . ',' . $_SESSION['isession'] . ',' . $reg->idcliente . ',\'' . $reg->cliente . '\',' . $reg->num_documento . ',\'' . $reg->serie . '\',' . $reg->correlativo . ',' . $reg->idmoneda . ',\'' . $reg->descripcion . '\',\'' . $reg->descripcion_tipo_comprobante . '\',\'' . $reg->fecha . '\',' . $reg->op_gravadas . ',' . $reg->op_exoneradas . ',' . $reg->total_venta . ',' . $reg->impuesto . ',\'' . $ultimo_correlativo . '\',\'' . $serie . '\');mostrarform(true);" ><span class="fa fa-plus"></span></button>',
				"1" => $reg->fecha,
				"2" => $reg->cliente,
				"3" => $reg->usuario,
				"4" => $reg->descripcion_tipo_comprobante,
				"5" => $reg->serie . '-' . $reg->correlativo,
				"6" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
					'<span class="label bg-red">Anulado</span>'
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


	case 'listarDetalleComprobantes':
		$id = $_GET['id'];
		$idtiponotacredito = $_SESSION['isession'];
		$cont = 0;
		$rspta = $notaCredito->listardetallecomprobantes($id);
		$total = 0.00;
		$impuesto = 18;
		$sumigv = 0;
		$sumdes = 0;
		$grava = 0;

		echo '<thead style="background-color:#A9D0F5">
                                   
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Serie</th>
                                    <th>Afectacion</th>
                                    <th>Cantidad</th>
                                    <th>Val. Vta. U.</th>
                                    <th>Descuento</th>
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
			}

			$sumigv += $newigv;
			$sumdes += $reg->descuento;

			// echo '<tr class="filas"><td></td><td>'.$reg->nombre.'</td><td>'.$reg->unidad_medida.'</td><td>'.$reg->afectacion.'</td><td>'.$reg->cantidad.'</td><td>'.$valorVentaU.'</td><td>'.$reg->descuento.'</td><td>'.$newigv.'</td><td>'.$reg->precio_venta.'</td><td>'.$valorVentaT.'</td><td>'.$reg->subtotal.'</td></tr>';
			$total = $total + ($reg->precio_venta * $reg->cantidad - $reg->descuento);
			$op_gravadas = $reg->op_gravadas;
			$op_inafectas = $reg->op_inafectas;
			$op_exoneradas = $reg->op_exoneradas;
			$op_gratuitas = $reg->op_gratuitas;
			$isc = $reg->isc;

			if ($idtiponotacredito == '1' || $idtiponotacredito == '2' || $idtiponotacredito == '6') {
				echo '<tr class="fila" id="fila' . $cont . '>' .
					'<td><button type="button" class="btn btn-danger" onclick="">X</button></td>' .
					'<td></td>' .
					'<td><input type="hidden" name="idarticuloo[]" value="' . $reg->idarticulo . '">' . $reg->nombre . '</td>' .
					'<td><input type="hidden" name="seriee[]" value="' . $reg->seried . '">' . $reg->seried . '</td>' .
					'<td><input type="hidden" name="afectacio[]" value="' . $reg->afectacion . '">' . $reg->afectacion . '</td>' .
					'<td><input type="hidden" name="cantidadd[]"  value="' . $reg->cantidad . '" >' . $reg->cantidad . '</td>' .
					'<td><span name="valor_venta_u" id="valor_venta_u' . $cont . '" >' . $valorVentaU . '</span></td>' .

					'<td><input type="hidden" name="descuentoo[]" value="' . $reg->descuento . '" >' . $reg->descuento . '</td>' .
					'<td><span name="impuest" id="impuest' . $cont . '" >' . $newigv . '</span></td>' .
					'<td><input type="hidden" name="precio_ventaa[]" value="' . $reg->precio_venta . '">' . $reg->precio_venta . '</td>' .
					'<td><span name="valor_venta_t" id="valor_venta_t' . $cont . '" >' . $valorVentaT . '</span></td>' .
					'<td><span name="subtotal" id="subtotal' . $cont . '">' . $reg->subtotal . '</span></td>' .
					'</tr>';
				$cont++;
			} else if ($idtiponotacredito == '3') {

				echo '<tr class="fila' . $cont . '" id="fila' . $cont . '>' .
					'<td><button type="button" class="btn btn-danger" onclick="">X</button></td>' .
					'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle3(' . $cont . ')"><i class="fa fa-times-circle"></i></button></td>' .
					'<td id="articuloo' . $cont . '" ><button type="button" onclick="agregarTipoNota3(' . $cont . ',\'' . $reg->nombre . '\')" class="btn btn-info" data-toggle="modal" data-target="#tiponota3"><i class="fa fa-edit"></i></button> ' . $reg->nombre . '</td><input type="hidden" name="idarticuloo[]" value="' . $reg->idarticulo . '"><input type="hidden" name="tiponc3[]" id="tiponc3' . $cont . '">' .
					'<td><input type="hidden" name="afectacio[]" value="' . $reg->afectacion . '">' . $reg->afectacion . '</td>' .
					'<td><input type="hidden" name="cantidadd[]"  value="' . $reg->cantidad . '" >' . $reg->cantidad . '</td>' .
					'<td><span name="valor_venta_u" id="valor_venta_u' . $cont . '" >' . $valorVentaU . '</span></td>' .

					'<td><input type="hidden" name="descuentoo[]" value="' . $reg->descuento . '" >' . $reg->descuento . '</td>' .
					'<td><span name="impuest" id="impuest' . $cont . '" >' . $newigv . '</span></td>' .
					'<td><input type="hidden" name="precio_ventaa[]" value="' . $reg->precio_venta . '">' . $reg->precio_venta . '</td>' .
					'<td><span name="valor_venta_t" id="valor_venta_t' . $cont . '" >' . $valorVentaT . '</span></td>' .
					'<td><span name="subtotal" id="subtotal' . $cont . '">' . $reg->subtotal . '</span></td>' .
					// '<td><button type="button" onclick="modificarSubtotales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'.

					'</tr>';
				$cont++;
			} else if ($idtiponotacredito == '7') {
				echo '<tr class="fila' . $cont . '" id="fila' . $cont . '>' .
					'<td><button type="button" class="btn btn-danger" onclick="">X</button></td>' .
					'<td><button type="button" class="btn btn-danger" onclick="eliminarDetallee(' . $cont . ')"><i class="fa fa-times-circle"></i></button></td>' .
					'<td><input type="hidden" name="idarticuloo[]" value="' . $reg->idarticulo . '">' . $reg->nombre . '</td>' .
					'<td><input type="hidden" name="seriee[]" value="' . $reg->seried . '">' . $reg->seried . '</td>' .
					'<td><input type="hidden" name="afectacio[]" value="' . $reg->afectacion . '">' . $reg->afectacion . '</td>' .
					'<td ><input type="number" name="cantidadd[]"  id="cantidad' . $cont . '" style="text-align: center;width:50px;" value="' . $reg->cantidad . '" ></td>' .
					'<td><span name="valor_venta_u" id="valor_venta_u' . $cont . '" >' . $valorVentaU . '</span></td>' .

					'<td><input type="hidden" name="descuentoo[]" value="' . $reg->descuento . '" >' . $reg->descuento . '</td>' .
					'<td><span name="impuest" id="impuest' . $cont . '" >' . $newigv . '</span></td>' .
					'<td><input type="hidden" name="precio_ventaa[]" value="' . $reg->precio_venta . '">' . $reg->precio_venta . '</td>' .
					'<td><span name="valor_venta_t" id="valor_venta_t' . $cont . '" >' . $valorVentaT . '</span></td>' .
					'<td><span name="subtotal" id="subtotal' . $cont . '">' . $reg->subtotal . '</span></td>' .
					'</tr>';

				echo '<script>

    				$("#cantidad' . $cont . '").keyup(modificarSubtotales);
    				$("#cantidad' . $cont . '").change(modificarSubtotales);

		    	</script>';
				$cont++;
			}
		};


		if ($idtiponotacredito == '1' || $idtiponotacredito == '2' || $idtiponotacredito == '6') {
			echo '<tfoot>
                                  <tr>
                                    <th colspan="7"></th>
                                    <th colspan="2">TOTAL VENTA GRAVADO</th>
                                    <th><h4 id="totalg">' . $op_gravadas . '</h4><input type="hidden" name="total_venta_gravado" id="total_venta_gravado" value="' . $op_gravadas . '"></th>
                                  </tr>
                                   
                                  
                                  
                                   <tr>
                                    <th style="height:2px;"  colspan="7"></th>
                                    <th colspan="2">IGV</th>
                                    <th><h4 id="totaligv">' . $sumigv . '</h4><input type="hidden" name="total_igv" id="total_igv" value="' . $sumigv . '"></th>
                                   </tr>
                                   <tr>
                                    <th style="height:2px;" colspan="7"></th>
                                    <th style="height:2px;" colspan="2">TOTAL IMPORTE</th>
                                    <th style="height:2px;"><h4 id="totalimp">' . $total . '</h4><input type="hidden" name="total_importe" id="total_importe" value="' . $total . '"></th>
                                   </tr>
                                </tfoot>';
		} else if ($idtiponotacredito == '3') {
			echo '<tfoot>
                                  <tr>
                                    <th colspan="7"></th>
                                    <th colspan="2">TOTAL VENTA GRAVADO</th>
                                    <th><h4 id="totalg">0.00</h4><input type="hidden" name="total_venta_gravado" id="total_venta_gravado" value="0.00"></th>
                                  </tr>
                                   <tr>
                                    <th colspan="7"></th>
                                    <th colspan="2">TOTAL VENTA EXONERADO</th>
                                    <th><h4 id="totale">0.00</h4><input type="hidden" name="total_venta_exonerado" id="total_venta_exonerado" value="0.00"></th>
                                   </tr>
                                   <tr>
                                    <th colspan="7"></th>
                                    <th colspan="2">TOTAL VENTA INAFECTAS</th>
                                    <th><h4 id="totali">0.00</h4><input type="hidden" name="total_venta_inafectas" id="total_venta_inafectas" value="0.00"></th>
                                   </tr>
                                   <tr>
                                    <th colspan="7"></th>
                                    <th colspan="2">TOTAL VENTA GRATUITAS</th>
                                    <th><h4 id="totalgt">0.00</h4><input type="hidden" name="total_venta_gratuitas" id="total_venta_gratuitas" value="0.00"></th>
                                   </tr>
                                   <tr>
                                    <th colspan="7"></th>
                                    <th colspan="2">TOTAL DESCUENTO</th>
                                    <th><h4 id="totald">0.00</h4><input type="hidden" name="total_descuentos" id="total_descuentos" value="0.00"></th>
                                   </tr>
                                   <tr>
                                    <th colspan="7"></th>
                                    <th colspan="2">ISC</th>
                                    <th><h4 id="totalisc">0.00</h4><input type="hidden" name="isc" id="isc" value="0.00"></th>
                                   </tr>
                                   <tr>
                                    <th style="height:2px;"  colspan="7"></th>
                                    <th colspan="2">IGV</th>
                                    <th><h4 id="totaligv">0.00</h4><input type="hidden" name="total_igv" id="total_igv" value="0.00"></th>
                                   </tr>
                                   <tr>
                                    <th style="height:2px;" colspan="7"></th>
                                    <th style="height:2px;" colspan="2">TOTAL IMPORTE</th>
                                    <th style="height:2px;"><h4 id="totalimp">0.00</h4><input type="hidden" name="total_importe" id="total_importe" value="0.00"></th>
                                   </tr>
                                </tfoot>';
		} else if ($idtiponotacredito == '7') {
			echo '<tfoot>
                                  <tr>
                                    <th colspan="7"></th>
                                    <th colspan="2">TOTAL VENTA GRAVADO</th>
                                    <th><h4 id="totalg">' . $op_gravadas . '</h4><input type="hidden" name="total_venta_gravado" id="total_venta_gravado" value="' . $op_gravadas . '"></th>
                                  </tr>
                                  
                                   <tr>
                                    <th style="height:2px;"  colspan="7"></th>
                                    <th colspan="2">IGV</th>
                                    <th><h4 id="totaligv">' . $sumigv . '</h4><input type="hidden" name="total_igv" id="total_igv" value="' . $sumigv . '"></th>
                                   </tr>
                                   <tr>
                                    <th style="height:2px;" colspan="7"></th>
                                    <th style="height:2px;" colspan="2">TOTAL IMPORTE</th>
                                    <th style="height:2px;"><h4 id="totalimp">' . $total . '</h4><input type="hidden" name="total_importe" id="total_importe" value="' . $total . '"></th>
                                   </tr>
                                </tfoot>';
		};

		break;
}
