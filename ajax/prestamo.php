<?php
if (strlen(session_id()) < 1)
  session_start();

require_once "../modelos/Prestamo.php";

$prestamo=new Prestamo(); 

$idventa=isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";
$idventarelacionado=isset($_POST["idventarelacionado"])? limpiarCadena($_POST["idventarelacionado"]):"";
$idsucursal=isset($_POST["idsucursal"])? limpiarCadena($_POST["idsucursal"]):"";
$idusuario=$_SESSION["idusuario"];
$codigotipo_comprobante=13;
// $serie=isset($_POST["serie"])? limpiarCadena($_POST["serie"]):"";
// $correlativo=isset($_POST["correlativo"])? limpiarCadena($_POST["correlativo"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";


switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idventa)){
			
			$rspta=$prestamo->insertar($idsucursal,$idusuario,$codigotipo_comprobante,$fecha_hora,$_POST["idarticulo"],$_POST["cantidad"],$_POST["serieArticulo"]);
			echo $rspta ? "Préstamo de producto registrado" : "No se pudieron registrar todos los datos del préstamo";
		}
		else {
			echo 'Bueno';
		}
	break;

	case 'anular':
		$rspta=$prestamo->anular($idventa);
 		echo $rspta ? "Préstamo anulado" : "Préstamo no se pudo anular";
	break;


	case 'mostrar':
		$rspta=$prestamo->mostrar($idventa);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'mostrarDatoSucursal':
		require_once "../modelos/Persona.php";
		$sucursal = new Persona();
		$rspta=$sucursal->mostrar($idsucursal);
 		echo json_encode($rspta);

	break;


	case 'listarDetalle':
		//Recibimos el idingreso
		$id=$_GET['id'];

		$rspta = $prestamo->listarDetalle($id);
		$total=0.00;

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
                                    <th>Precio Venta</th>
                                    <th>Importe</th>
                                </thead>';

		while ($reg = $rspta->fetch_object())
				{

    				
    				$subtotal = 0;
					echo '<tr class="filas">
							<td></td>
							<td>'.$reg->nombre.'</td>
							<td>'.$reg->serie.'</td>
							<td>'.$reg->unidad_medida.'</td>
							<td>'.$reg->cantidad.'</td>
							<td>'.number_format($reg->precio_venta,2,'.',',').'</td>
							<td>'.number_format($subtotal,2,'.',',').'</td>
						  </tr>';
					// $total+=$subtotal;
					$total_descuentos = $reg->total_descuentos;
					$total_igv = $reg->total_igv;
					
				}
		
    	echo '<tfoot>
                                  <tr>
                                    <th colspan="4"></th>
                                    <th colspan="2">TOTAL VENTA GRAVADO</th>
                                    <th><h4 id="totalg">0.00</h4><input type="hidden" name="total_venta_gravado" id="total_venta_gravado"></th>
                                  </tr>
                                   
                                   <tr>
                                    <th style="height:2px;"  colspan="4"></th>
                                    <th colspan="2">DESCUENTO</th>
                                    <th><h4 id="totald">'.number_format($total_descuentos,2,".",",").'</h4><input type="hidden" name="total_descuentos" id="total_descuentos"></th>
                                   </tr>
                                   <tr>
                                    <th style="height:2px;"  colspan="4"></th>
                                    <th colspan="2">IGV</th>
                                    <th><h4 id="totaligv">'.number_format($total_igv,2,".",",").'</h4><input type="hidden" name="total_igv" id="total_igv"></th>
                                   </tr>
                                   <tr>
                                    <th style="height:2px;" colspan="4"></th>
                                    <th style="height:2px;" colspan="2">TOTAL IMPORTE</th>
                                    <th style="height:2px;"><h4 id="totalimp">0</h4><input type="hidden" name="total_importe" id="total_importe"></th>
                                   </tr>
                                </tfoot>';


	break;

	case 'listar':
		$rspta=$prestamo->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			if($reg->codigotipo_comprobante=='13'){
 				$url='../reportes/reporte-prestamo.php?id=';
 			}else{
 				$url='';
 			}

 		
 			$data[]=array(
 				"0"=>(($reg->estado=='Aceptado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button>'.
 				 					' <button class="btn btn-danger" onclick="anular('.$reg->idventa.')"><i class="fa fa-close"></i></button>':
 				 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button>').
 				'<a target="_blank" href="'.$url.$reg->idventa.'"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>',
 				"1"=>$reg->fecha,
 				"2"=>$reg->cliente,
 				"3"=>$reg->usuario,
 				"4"=>$reg->serie.'-'.$reg->correlativo,
 				"5"=>$reg->total_venta,
 				"6"=>($reg->estado=='Aceptado')?'<span class="label bg-green">'.$reg->estado.'</span>':
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

	
	case 'selectSucursal':
		require_once "../modelos/Persona.php";
		$persona = new Persona();

		$rspta = $persona->listarsucursal();

		while ($reg = $rspta->fetch_object())
				{
				echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
				}
	break;

	// case 'selectUsuario':
	// 	require_once "../modelos/Usuario.php";
	// 	$usuario = new Usuario();
	// 	$rspta=$usuario->listar();
	// 	while ($reg=$rspta->fetch_object()) {
	// 		echo '<option value='.$reg->idusuario.'>'.$reg->nombre.'</option>';
	// 	}
	// break;

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
 				"0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\',\''.$unidadm.'\','.$reg->precio_venta.')"><span class="fa fa-plus"></span></button>',
 				"1"=>$reg->nombre,
 				"2"=>$unidadm,
 				"3"=>$reg->categoria,
 				"4"=>$reg->codigo,
 				"5"=>$reg->stock,
 				"6"=>$reg->precio_venta,
 				"7"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >",
 				// "8"=>'Gravado<input type="radio" name="afectacion'.$reg->idarticulo.'" checked value="10">  Exonerado<input type="radio" name="afectacion'.$reg->idarticulo.'" value="20"> Inafecta<input type="radio" name="afectacion'.$reg->idarticulo.'" value="30">'
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
