<?php
if (strlen(session_id()) < 1)
  session_start();

require_once "../modelos/ventaCredito.php";

$venta=new VentaCredito();

$idventa=isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idusuario=$_SESSION["idusuario"];
$codigotipo_comprobante=isset($_POST["codigotipo_comprobante"])? limpiarCadena($_POST["codigotipo_comprobante"]):"";
// $serie=isset($_POST["serie"])? limpiarCadena($_POST["serie"]):"";
// $correlativo=isset($_POST["correlativo"])? limpiarCadena($_POST["correlativo"]):"";
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



switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idventa)){
			require_once "../reportes/numeroALetras.php";
			$letras = NumeroALetras::convertir($total_importe);
			list($num,$cen)=explode('.',$total_importe);
			$leyenda = $letras.'Y '.$cen.'/100 SOLES';

			$rspta=$venta->insertar($idcliente,$idusuario,$codigotipo_comprobante,$fecha_hora,$impuesto,$total_venta_gravado,$total_venta_inafectas,$total_venta_exonerado,$total_venta_gratuitas,$isc,$total_descuentos,$total_igv,$total_importe,$leyenda,$moneda,$_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_venta"],$_POST["descuento"],$_POST["serieArticulo"]);
			echo $rspta ? "Venta a crédito registrada" : "No se pudieron registrar todos los datos de la venta a crédito";
		}
		else {
		}
	break;

	case 'anular':
		$rspta=$venta->anular($idventa);
 		echo $rspta ? "Venta a crédito cancelada" : "Venta a crédito no se puede cancelar";
	break;

	case 'mostrar':
		$rspta=$venta->mostrar($idventa);
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

		$rspta = $venta->listarDetalle($id);
		$total=0.00;
		$impuesto=18;
		$sumigv=0;
		$sumdes=0;
		$grava=0;

		 /*<th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Precio Venta</th>
                                    <th>Descuento</th>
                                    <th>Subtotal</th>*/
		echo '<thead style="background-color:#A9D0F5">
                                   
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Serie</th>
                                    <th>U. Medida</th>
                                    <th>Cantidad</th>
                                    <th>Descuento</th>
                                    <th>Precio Venta</th>
                                    <th>Importe</th>
                                </thead>';

		while ($reg = $rspta->fetch_object())
				{

			
			    	

    				$subtotal = $reg->subtotal;


					echo '<tr class="filas"><td></td><td>'.$reg->nombre.'</td><td>'.$reg->serie.'</td><td>'.$reg->unidad_medida.'</td><td>'.$reg->cantidad.'</td><td>'.$reg->descuento.'</td><td>'.number_format($reg->precio_venta,2,'.',',').'</td><td>'.number_format($subtotal,2,'.',',').'</td></tr>';



					

					$total=$reg->total_venta;
					// $total=$total+($reg->precio_venta*$reg->cantidad-$reg->descuento);
					$total_igv = $reg->total_igv;
					$total_descuentos = $reg->total_descuentos;
					$op_gravadas=$reg->op_gravadas;
					$op_inafectas=$reg->op_inafectas;
					$op_exoneradas=$reg->op_exoneradas;
					$op_gratuitas=$reg->op_gratuitas;
					$isc=$reg->isc;
					
				}
		
    	echo '<tfoot>
                                  <tr>
                                    <th colspan="5"></th>
                                    <th colspan="2">TOTAL VENTA GRAVADO</th>
                                    <th><h4 id="totalg">'.number_format($op_gravadas,2,".",",").'</h4><input type="hidden" name="total_venta_gravado" id="total_venta_gravado"></th>
                                  </tr>
                                  <tr>
                                    <th style="height:2px;"  colspan="5"></th>
                                    <th colspan="2">DESCUENTO</th>
                                    <th><h4 id="totald">'.number_format($total_descuentos,2,".",",").'</h4><input type="hidden" name="total_descuentos" id="total_descuentos"></th>
                                   </tr>
                                   
                                   <tr>
                                    <th style="height:2px;"  colspan="5"></th>
                                    <th colspan="2">IGV</th>
                                    <th><h4 id="totaligv">'.number_format($total_igv,2,".",",").'</h4><input type="hidden" name="total_igv" id="total_igv"></th>
                                   </tr>
                                   <tr>
                                    <th style="height:2px;" colspan="5"></th>
                                    <th style="height:2px;" colspan="2">TOTAL IMPORTE</th>
                                    <th style="height:2px;"><h4 id="totalimp">'.number_format($total,2,".",",").'</h4><input type="hidden" name="total_importe" id="total_importe"></th>
                                   </tr>
                                </tfoot>';


	break;

	case 'listar':
		$rspta=$venta->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			
 				$url='../reportes/Orden.php?id=';
 		

 			$data[]=array(
 				"0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button><a target="_blank" href="'.$url.$reg->idventa.'"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>',
 				"1"=>$reg->fecha,
 				"2"=>$reg->cliente,
 				"3"=>$reg->usuario,
 				"4"=>$reg->descripcion_tipo_comprobante,
 				"5"=>$reg->serie.'-'.$reg->correlativo,
 				"6"=>$reg->total_venta,
 				"7"=>($reg->estado=='Credito')?'<span class="label bg-green">Crédito</span>':
 				'<span class="label bg-red">Cancelado</span>',
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

	case 'selectUsuario':
		require_once "../modelos/Usuario.php";
		$usuario = new Usuario();
		$rspta=$usuario->listar();
		while ($reg=$rspta->fetch_object()) {
			echo '<option value='.$reg->idusuario.'>'.$reg->nombre.'</option>';
		}
	break;

	case 'selectTipoComprobante':
		$rspta = $venta->selectTipoComprobante();
		while ($reg = $rspta->fetch_object()) {
			echo '<option value='.$reg->codigotipo_comprobante.'>'.$reg->descripcion_tipo_comprobante.'</option>';
		}
		break;

	case 'selectMoneda':
			$rspta = $venta->selectMoneda();
			while ($reg=$rspta->fetch_object()) {
				echo '<option value='.$reg->idmoneda.'>'.$reg->descripcion.'</option>';
			}
			break;	
	case 'listarArticulosVenta':
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
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);
	break;
}
?>
