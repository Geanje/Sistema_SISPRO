<?php
if (strlen(session_id()) < 1)//se cambio de mayor a menor
  session_start();

require_once "../modelos/Proforma.php";

$proforma=new Proforma();

$idproforma=isset($_POST["idproforma"])? limpiarCadena($_POST["idproforma"]):"";
$idusuario=$_SESSION["idusuario"];//tenia error de sintaxis arreglado 
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$correlativo=isset($_POST["correlativo"])? limpiarCadena($_POST["correlativo"]):"";
$referencia=isset($_POST["referencia"])? limpiarCadena($_POST["referencia"]):"";
$tipo_proforma=isset($_POST["tipo_proforma"])? limpiarCadena($_POST["tipo_proforma"]):"";
$igv_total=isset($_POST["igv_total"])? limpiarCadena($_POST["igv_total"]):"";
$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$moneda=isset($_POST["moneda"])? limpiarCadena($_POST["moneda"]):"";

//$precio_venta=isset($_POST["precio_venta"])? limpiarCadena($_POST["precio_venta"]):"";
//$descuento=isset($_POST["descuento"])? limpiarCadena($_POST["descuento"]):"";
//$serieCotizacion=isset($_POST["serieCotizacion"])? limpiarCadena($_POST["serieCotizacion"]) :"";
$validez=isset($_POST["validez"])? limpiarCadena($_POST["validez"]):"";
$tiempoEntrega=isset($_POST["tiempoEntrega"])? limpiarCadena($_POST["tiempoEntrega"]):"";
//$serie=isset($_POST["serie"])? limpiarCadena($_POST["serie"]):"";
//$correlativo=isset($_POST["correlativo"])? limpiarCadena($_POST["correlativo"]):"";

$igv_asig=isset($_POST["igv_asig"])? limpiarCadena($_POST["igv_asig"]):"";


//$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
//$precio=isset($_POST["precio"])? limpiarCadena($_POST["precio"]):"";
//


switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idproforma)){
			$rspta=$proforma->insertar($idusuario,$idcliente,$correlativo,$fecha_hora,$validez,$tiempoEntrega,$_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_venta"],$_POST["serieProforma"],$total_venta,$igv_total,$igv_asig);
			echo $rspta ? "¡Ha registrado la Proforma exitosamente" : "No se pudieron registrar todos los datos de la Proforma";
		}
		else {
		}
	break;

	case 'anular':
		$rspta=$proforma->anular($idproforma);
 		echo $rspta ? "Proforma anulada" : "Proforma  no se puede anular";
	break;

	case 'mostrar':
		$rspta=$proforma->mostrar($idproforma);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'mostrarDatoCliente':
		require_once "../modelos/Persona.php";
		$cliente = new Persona();
		$rspta=$cliente->mostrar($idcliente);
 		echo json_encode($rspta);

	break;


	case 'listarDetalle':
		//Recibimos el idingreso
		$id=$_GET['id'];

		$rspta = $proforma->listarDetalle($id);
		$subt=0.00;
		$sumigv=0.00;
		$total=0.00;

		 /*<th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Precio Venta</th>
                                    <th>Descuento</th>
                                    <th>Subtotal</th>*/
		/*echo '<thead style="background-color:#A9D0F5">
                                   
                                    <th>Opciones</th>
                                    <th >Articulo</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Precio Parcial</th>
                                </thead>';*/
		echo '<thead style="background-color:#A9D0F5">
                                   
                <th>Opciones</th>
                <th>Artículo</th>
                <th>Serie</th>
                <th>U. Medida</th>
                                 
                <th>Cantidad</th>
                <th>Val. Vta. U.</th>
                                 
                <th>Impuestos</th>
                <th>Precio Venta</th>
                <th>Val. Vta. Total</th>
                <th>Importe</th>
            </thead>';


			while ($reg = $rspta->fetch_object())
				{

					if($reg->afectacion=='Exonerado'){
						$valorVentaU=$reg->precio_venta;
						$valorVentaU=round($valorVentaU,2);
				    	$valorVentaT=$reg->precio_venta*$reg->cantidad;
				    	$valorVentaT=round($valorVentaT,2);
				    	$igv_total= 0.00;
					}else{
						$valorVentaU=$reg->precio_venta/(1+($impuesto/100));
						$valorVentaU=round($valorVentaU,2);
				    	$valorVentaT=$reg->precio_venta/(1+($impuesto/100))*$reg->cantidad - $reg->descuento;
				    	$valorVentaT=round($valorVentaT,2);
				    	$igv_total=  ($reg->cantidad*$reg->precio_venta/(1+($impuesto/100))-$reg->descuento)*($impuesto/100);
    					$igv_total=round($igv_total,2);
					}
					
			    	// $sumgrava+=$valorVentaT;
			    	// $precioSinIgv= $reg->cantidad*$reg->precio_venta/(1+($impuesto/100));
				    // $igv = $precioSinIgv*($impuesto/100);
    				
    				$sumigv+=$igv_total;
    				$sumdes+=$reg->descuento;

					echo '<tr class="filas"><td></td><td>'.$reg->nombre.'</td><td>'.$reg->serieProforma.'</td><td>'.$reg->unidad_medida.'</td><td>'.$reg->cantidad.'</td><td>'.$valorVentaU.'</td><td>'.$igv_total.'</td><td>'.$reg->precio_venta.'</td><td>'.$valorVentaT.'</td><td>'.$reg->subtotal.'</td></tr>';
					$total=$total+($reg->precio_venta*$reg->cantidad-$reg->descuento);
					$op_gravadas=$reg->op_gravadas;
					$op_inafectas=$reg->op_inafectas;
					$op_exoneradas=$reg->op_exoneradas;
					$op_gratuitas=$reg->op_gratuitas;
					$isc=$reg->isc;
					
				}
		//linea 127 cambie total_venta_agravado por precio_centa
    	echo '<tfoot>
                                  <tr>
                                    <th colspan="7"></th>
                                    <th colspan="2">TOTAL VENTA GRAVADO</th>
                                    <th><h4 id="totalg">'.$subt.'</h4><input type="hidden" name="ssubtotal" id="ssubtotal"></th>
                                  </tr>
                               
                                   <tr>
                                    <th style="height:2px;"  colspan="7"></th>
                                    <th colspan="2">IGV</th>
                                    <th><h4 id="totaligv">'.$sumigv.'</h4><input type="hidden" name="igv_total" id="igv_total"></th>
                                   </tr>
                                   <tr>
                                    <th style="height:2px;" colspan="7"></th>
                                    <th style="height:2px;" colspan="2">TOTAL IMPORTE</th>
                                    <th style="height:2px;"><h4 id="totalimp">'.$total.'</h4><input type="hidden" name="total_venta" id="total_venta"></th>
                                   </tr>
                                </tfoot>';


		/*while ($reg = $rspta->fetch_object())
				{


					echo '<tr class="filas"><td></td><td>'.$reg->articulo.'</td><td>'.$reg->serie.'</td><td>'.$reg->cantidad.'</td><td>'.$reg->precio.'</td></tr>';
				

					$subt += $reg->subtotal;
					$sumigv= $subt*18;
					$total = $subt+$sumigv;

					
				}
		
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


	break;*/

	case 'listar':
		$rspta=$proforma->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			// if($reg->codigotipo_comprobante=='1' || $reg->codigotipo_comprobante=='3'){
 			// 	$url='../reportes/Facturacion.php?id=';
 			// }else{
 				$url='../reportes/Proformas.php?id=';

 			// }

 			$data[]=array(
 				"0"=>//(($reg->estado=='AceptadoP')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idproforma.')"><i class="fa fa-eye"></i></button>'.
 				 					' <button class="btn btn-danger" onclick="anular('.$reg->idproforma.')"><i class="fa fa-close"></i></button>'.
				//'<a target="_blank" href="'.$url.$reg->idproforma.'" > <button class="btn btn-warning"><i class="fa fa-eye"></i></button></a>').
 				'<a target="_blank" href="'.$url.$reg->idproforma.'" > <button class="btn btn-info"><i class="fa fa-file"></i></button></a>',
 				"1"=>$reg->fecha,
 				"2"=>$reg->cliente,
 				//"3"=>$reg->tipo_proforma
				"3"=>$reg->usuario,
				"4"=>'Proforma',
				"5"=>$reg->serie.'-'.$reg->correlativo,
 				"6"=>$reg->total_venta,
 				"7"=>($reg->estado=='AceptadoP')?'<span class="label bg-green">Aceptado</span>':
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

	case 'selectCliente':
		require_once "../modelos/Persona.php";
		$persona = new Persona();

		$rspta = $persona->listarC();

		while ($reg = $rspta->fetch_object())
				{
				echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
				}
	break;

	case 'selecTipoIGV':
		$rspta=$proforma->selecTipoIGV();
		while($reg=$rspta->fetch_object()){
			echo '<option value='.$reg->porcentaje.'>'.$reg->porcentaje.'</option>';
		}
		break;

	case 'selectUsuario':
	 	require_once "../modelos/Usuario.php";
	 	$usuario = new Usuario();
	 	$rspta=$usuario->listar();
		$html = '';
		$html .= '<option value="all">Todos</option>';
	 	while ($reg=$rspta->fetch_object()) {
	 		echo '<option value='.$reg->idusuario.'>'.$reg->nombre.'</option>';
	 	}
		echo $html;
	 break;

		
	case 'listarArticulosVenta':
		require_once "../modelos/Articulo.php"; 
		$articulo=new Articulo();

		$rspta=$articulo->listarActivosVenta();
 		//Vamos a declarar un array
 		$data= Array();
		$i=0;
		foreach ($rspta as $item) {
			if(@$item['unidad_medida']=='otros'){
				$unidadm = @$item['descripcion_otros'];
			}else{
				$unidadm = @$item['unidad_medida'];
			}
			$params = 
			$data[]=array(
				"0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.@$item['idarticulo'].',\''.@$item['nombre'].'\',\''.$unidadm.'\',\''.@$item['precio_venta'].'\',\''.@$item['afectacion'].'\',\''.@$item['precio_compra'].'\',\''.@$item['incentivo'].'\')"><span class="fa fa-plus"></span></button>',
				"1"=>@$item['nombre'],
				"2"=>$unidadm,
				"3"=>@$item['categoria'],
				"4"=>@$item['codigo'],
				"5"=>@$item['stock'],
				"6"=>@$item['precio_venta'],
				"7"=>@$item['controlador'],
				// "8"=>'Gravado<input type="radio" name="afectacion'.$reg->idarticulo.'" checked value="10">  Exonerado<input type="radio" name="afectacion'.$reg->idarticulo.'" value="20"> Inafecta<input type="radio" name="afectacion'.$reg->idarticulo.'" value="30">'
				"8"=>@$item['afectacion'],
				);
		}
 		$results = array(
 			"sEcho"=>1, //Informaci��n para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);
	break;

	/* case 'listarArticulosVenta':
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
	 break;
			*/

	 case 'selectMoneda':
		$rspta = $proforma->selectMoneda();
		while ($reg=$rspta->fetch_object()) {
			echo '<option value='.$reg->idmoneda.' selected>'.$reg->descripcion.'</option>';
		}
		break;	
		
}
?>
