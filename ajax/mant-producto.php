<?php
require_once "../modelos/mant-producto.php";

$articulo=new MantProducto();

$id_detalle_ingreso=isset($_POST["id_detalle_ingreso"])? limpiarCadena($_POST["id_detalle_ingreso"]):"";
$precio_venta=isset($_POST["precio_venta"])? limpiarCadena($_POST["precio_venta"]):"";

switch ($_GET["op"]){

	case 'guardaryeditar':
		$rspta=$articulo->guardaryeditar($id_detalle_ingreso, $precio_venta);
		echo $rspta ? "Producto modificado" : "Producto no se pudo modificar";
	break;

	case "listar_productos":
		$rspta = $articulo->listar_productos();
		$data = array();
		while ($reg = $rspta->fetch_object()) {
			if($reg->unidad_medida=='otros' ){
 				$medida=$reg->descripcion_otros;
 			}else{ 
 				$medida=$reg->unidad_medida;
 				if($medida=="NIU"){
 					$medida="UND";
 				}
 			}
 			$data[]=array(

 				"0"=>$reg->nombre,
 				"1"=>$reg->categoria,
 				"2"=>$reg->codigo,
 				"3"=>$reg->stock,
 			// 	"4"=>$reg->precio_compra,
 				"4"=>$reg->precio_venta,
 				"5"=> $medida,
 				// "7"=>$medida,
 				"6"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
				 '<span class="label bg-red">Desactivado</span>',
				"7" => "<button class='btn btn-primary' onclick=\"mostrarPrecioVenta('$reg->nombre', $reg->iddetalle_ingreso, $reg->precio_venta)\" data-toggle='modal' data-target='#modalprecioventa'><i class='fa fa-pencil'></i></button>"
 				
 				);
		}
		$results = array(
			"sEcho"=>1,
			"iTotalRecords"=>count($data),
			"iTotalDisplayRecords"=>count($data),
			"aaData"=>$data
		);
		echo json_encode($results);
	break;

}
?>
