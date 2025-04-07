<?php
if (strlen(session_id()) < 1)
	session_start();

require_once "../modelos/Cotizacion.php";

$cotizacion = new Cotizacion();

$idcotizacion = isset($_POST["idcotizacion"]) ? limpiarCadena($_POST["idcotizacion"]) : "";
$idcliente = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
$idusuario = $_SESSION["idusuario"];
//$cantidad=isset($_POST["cantidad"])? limpiarCadena($_POST["cantidad"]):"";
$codigotipo_comprobante = isset($_POST["codigotipo_comprobante"]) ? limpiarCadena($_POST["codigotipo_comprobante"]) : "";
//$serie=isset($_POST["serie"])? limpiarCadena($_POST["serie"]):"";😀
$correlativo = isset($_POST["correlativo"]) ? limpiarCadena($_POST["correlativo"]) : "";
$fecha_hora = isset($_POST["fecha_hora"]) ? limpiarCadena($_POST["fecha_hora"]) : "";
$impuesto = isset($_POST["impuesto"]) ? limpiarCadena($_POST["impuesto"]) : "";
$referencia = isset($_POST["referencia"]) ? limpiarCadena($_POST["referencia"]) : ""; //😀
$tipo_proforma = isset($_POST["tipo_proforma"]) ? limpiarCadena($_POST["tipo_proforma"]) : ""; //😀

$total_venta = isset($_POST["total_venta"]) ? limpiarCadena($_POST["total_venta"]) : ""; //😀
//$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$precio = isset($_POST["precio"]) ? limpiarCadena($_POST["precio"]) : "";



$moneda = isset($_POST["moneda"]) ? limpiarCadena($_POST["moneda"]) : "";
//$tiempo_entrega=isset($_POST["tiempo_entrega"])? limpiarCadena($_POST["tiempo_entrega"]):"";
$validez = isset($_POST["validez"]) ? limpiarCadena($_POST["validez"]) : "";
//$validez=isset($_POST["validez"])? limpiarCadena($_POST["validez"]):"";
//$total_venta_gravado=isset($_POST["total_venta_gravado"])? limpiarCadena($_POST["total_venta_gravado"]):"";
//$total_venta_exonerado=isset($_POST["total_venta_exonerado"])? limpiarCadena($_POST["total_venta_exonerado"]):"";
//$total_venta_inafectas=isset($_POST["total_venta_inafectas"])? limpiarCadena($_POST["total_venta_inafectas"]):"";
//$total_venta_gratuitas=isset($_POST["total_venta_gratuitas"])? limpiarCadena($_POST["total_venta_gratuitas"]):"";
$total_descuentos = isset($_POST["total_descuentos"]) ? limpiarCadena($_POST["total_descuentos"]) : "";
$isc = isset($_POST["isc"]) ? limpiarCadena($_POST["isc"]) : "";
$igv_total = isset($_POST["igv_total"]) ? limpiarCadena($_POST["igv_total"]) : "";
//$total_importe=isset($_POST["total_importe"])? limpiarCadena($_POST["total_importe"]):"";😀
$igv_asig = isset($_POST["igv_asig"]) ? limpiarCadena($_POST["igv_asig"]) : "";


//$precio_venta=isset($_POST["precio_venta"])? limpiarCadena($_POST["precio_venta"]):"";
$descuento = isset($_POST["descuento"]) ? limpiarCadena($_POST["descuento"]) : "";
//$serieCotizacion=isset($_POST["serieCotizacion"])? limpiarCadena($_POST["serieCotizacion"]):"";
//$idarticulo=isset($_POST["idarticulo"])? limpiarCadena($_POST["idarticulo"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		if (empty($idcotizacion)) {
			$rspta = $cotizacion->insertar($idusuario, $idcliente, $correlativo, $tipo_proforma, $igv_total, $referencia, $total_venta, $fecha_hora, $_POST["descripcion"], $validez, $_POST["cantidad"], $_POST["precio"], $igv_asig); //borre total importe,serie,descuento. 😀
			echo $rspta ? "¡Ha registrado la Cotización exitosamente" : "No se pudieron registrar todos los datos de la cotizacion"; //😀
		} else {
		}
		break;

	case 'anular':
		$rspta = $cotizacion->anular($idcotizacion);
		echo $rspta ? "Proforma anulada" : "Proforma no se puede anular";
		break;

	case 'mostrar':
		$rspta = $cotizacion->mostrar($idcotizacion);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
		break;

		/*case 'listarDetalle':
		//Recibimos el idingreso
		$id=$_GET['id'];

		$rspta = $cotizacion->listarDetalle($id);
		$total=0.00;
		$impuesto;
		$sumigv=0;
		$sumdes=0;
		$grava=0;

		 <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Precio Venta</th>
                                    <th>Descuento</th>
                                    <th>Subtotal</th>
		echo '<thead style="background-color:#A9D0F5">
                                   
									<th>Opciones</th>
									<th >Descripcion</th>
									<th>Cantidad</th>
									<th>Precio Unitario</th>
									<th>Precio Parcial</th>
                                </thead>';

		while ($reg = $rspta->fetch_object())
				{

					if($reg->afectacion=='Exonerado'){
						$valorVentaU=$reg->precio_venta;
						$valorVentaU=round($valorVentaU,2);
				    	$valorVentaT=$reg->precio_venta*$reg->cantidad;
				    	$valorVentaT=round($valorVentaT,2);
				    	$newigv= 0.00;
					}else{
						$valorVentaU=$reg->precio_venta/(1+($impuesto/100));
						$valorVentaU=round($valorVentaU,2);
				    	$valorVentaT=$reg->precio_venta/(1+($impuesto/100))*$reg->cantidad - $reg->descuento;
				    	$valorVentaT=round($valorVentaT,2);
				    	$newigv=  ($reg->cantidad*$reg->precio_venta/(1+($impuesto/100))-$reg->descuento)*($impuesto/100);
    					$newigv=round($newigv,2);
					}
					
			    	// $sumgrava+=$valorVentaT;
			    	// $precioSinIgv= $reg->cantidad*$reg->precio_venta/(1+($impuesto/100));
				    // $igv = $precioSinIgv*($impuesto/100);
    				
    				$sumigv+=$newigv;
    				$sumdes+=$reg->descuento;

					echo '<tr class="filas"><td></td><td>'.$reg->descripcion.'</td><td>'.$reg->cantidad.'</td><td>'.$reg->precio.'</td><td>'.$reg->subtotal.'</td></tr>';
					
					$subt += $reg->subtotal;
					$sumigv= $subt*$impuesto/100;
					//$igv_asig=$impuesto;
					$total = $subt+$sumigv;
					
					/*$total=$total+($reg->precio_venta*$reg->cantidad-$reg->descuento);
					$op_gravadas=$reg->op_gravadas;
					$op_inafectas=$reg->op_inafectas;
					$op_exoneradas=$reg->op_exoneradas;
					$op_gratuitas=$reg->op_gratuitas;
					$isc=$reg->isc;
					
				}
		//linea 127 cambie total_venta_agravado por precio_centa😀
    	echo '<tfoot>
						<tr>
							<th colspan="3"></th>
							<th colspan="">SUBTOTAL</th>
							<th><h4 id="subtotal">'.$subt.'</h4><input type="hidden" name="ssubtotal" id="ssubtotal"></th>
						<tr>
							<th style="height:2px;"  colspan="3"></th>
							<th colspan="">IGV</th>
							<th><h4 id="totaligv">'.$sumigv.'</h4><input type="hidden" name="igv_total" id="igv_total"></th>
						</tr>
						<tr>
							<th style="height:2px;" colspan="3"></th>
							<th style="height:2px;" colspan="">TOTAL IMPORTE</th>
							<th style="height:2px;"><h4 id="totalimp">'.$total.'</h4><input type="hidden" name="total_venta" id="total_venta"></th>
						
						</tr>
                                </tfoot>';


	break;
*/
	case 'listar':
		$rspta = $cotizacion->listar();
		//Vamos a declarar un array
		$data = array();

		while ($reg = $rspta->fetch_object()) {

			$url = '../reportes/cotizacion.php?id=';


			$data[] = array(
				"0" => //(($reg->estado=='Cotizado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idcotizacion.')"><i class="fa fa-eye"></i></button>'.
				' <button class="btn btn-danger" onclick="anular(' . $reg->idcotizacion . ')"><i class="fa fa-close"></i></button>' .
					//'<button class="btn btn-warning" onclick="mostrar('.$reg->idcotizacion.')"><i class="fa fa-eye"></i></button>').
					'<a target="_blank" href="' . $url . $reg->idcotizacion . '"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>',
				"1" => $reg->fecha,
				"2" => $reg->tipo_proforma,
				"3" => $reg->cliente,
				"4" => $reg->usuario,
				"5" => 'Cotización', //😀
				"6" => $reg->serie . '-' . $reg->correlativo,
				"7" => $reg->total_venta, //😀
				"8" => ($reg->estado == 'Cotizado') ? '<span class="label bg-green">Cotizado</span>' :
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

	case 'selectCliente':
		require_once "../modelos/Persona.php";
		$persona = new Persona();

		$rspta = $persona->listarC();

		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
		}
		break;

		/*case 'selectClienteS':
		require_once "../modelos/Persona.php";
		$persona = new Persona();
	  
		$rspta = $persona->listarcs();
	  
		while ($reg = $rspta->fetch_object())
			{
			echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
			}
	  break;*/

	case 'mostrarDatoCliente': //😀
		require_once "../modelos/Persona.php";
		$cliente = new Persona();
		$rspta = $cliente->mostrar($idcliente);
		echo json_encode($rspta);

		break;


	case 'selectTipoComprobante':
		$rspta = $cotizacion->selectTipoComprobante();
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->codigotipo_comprobante . '>' . $reg->descripcion_tipo_comprobante . '</option>';
		}
		break;

	case 'selectUsuario':
		require_once "../modelos/Usuario.php";
		$usuario = new Usuario();
		$rspta = $usuario->listar();
		$html = ''; //😀
		$html .= '<option value="all">Todos</option>'; //😀
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->idusuario . '>' . $reg->nombre . '</option>';
		}
		echo $html;
		break;

	case 'selecTipoIGV':
		$rspta = $cotizacion->selecTipoIGV();
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->porcentaje . '>' . $reg->porcentaje . '</option>';
		}
		break;

		/*case 'selectMoneda':😀
			$rspta = $venta->selectMoneda();
			while ($reg=$rspta->fetch_object()) {
				echo '<option value='.$reg->idmoneda.' selected>'.$reg->descripcion.'</option>';
			}
			break;	*/
		/*case 'listarArticulosVenta':😀
		require_once "../modelos/Articulo.php";
		$articulo=new Articulo();

		$rspta=$articulo->listarActivosVenta();
 		//Vamos a declarar un array
 		$data= Array();
 		$i=0;
 		while ($reg=$rspta->fetch_object()){

 			if($reg->unidad_medida=='otros'){
 				$unidadm = $reg->descripcion_otros;
 			}else{
 				$unidadm = $reg->unidad_medida;
 			}
 			$i+=1;
 			$data[]=array(
 				"0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\',\''.$unidadm.'\','.$reg->precio_venta.',\''.$reg->afectacion.'\')"><span class="fa fa-plus"></span></button>',
 				"1"=>$reg->nombre,
 				"2"=>$unidadm,
 				"3"=>$reg->categoria,
 				"4"=>$reg->codigo,
 				"5"=>$reg->stock,
 				"6"=>$reg->precio_venta,
 				"7"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >",
 				// "8"=>'Gravado<input type="radio" name="afectacion'.$reg->idarticulo.'" checked value="10">  Exonerado<input type="radio" name="afectacion'.$reg->idarticulo.'" value="20"> Inafecta<input type="radio" name="afectacion'.$reg->idarticulo.'" value="30">'
 				"8"=>$reg->afectacion,
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);
	break;*/
}
