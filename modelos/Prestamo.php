<?php 

date_default_timezone_set('America/Lima'); 
require_once('../config/Conexion.php');


Class Prestamo
{

	public function __construct(){

	}
	
	public function insertar($idsucursal,$idusuario,$codigotipo_comprobante,$fecha_hora,$idarticulo,$cantidad,$serieArticulo){

      $saber = "SELECT serie,correlativo FROM venta WHERE codigotipo_comprobante='$codigotipo_comprobante'";
      $saberExiste = ejecutarConsultaSimpleFila($saber);
      if($saberExiste["serie"] == null and $saberExiste["correlativo"] == null){
        $serie='PP001';
        $correlativo='00000001';
      }else{
        $sqlmaxserie="SELECT max(serie) as maxSerie FROM venta WHERE codigotipo_comprobante='$codigotipo_comprobante' ";
        $maxserie = ejecutarConsultaSimpleFila($sqlmaxserie);
        $serie= $maxserie["maxSerie"];
        $ultimoCorrelativo="SELECT max(correlativo) as ultimocorrelativo,serie,correlativo FROM venta WHERE codigotipo_comprobante='$codigotipo_comprobante'  and serie='$serie'";
        $ultimo = ejecutarConsultaSimpleFila($ultimoCorrelativo);
        if($ultimo["ultimocorrelativo"] =='99999999'){
          $ser = substr($serie,1)+1;
          $seri= str_pad((string)$ser,3,"0",STR_PAD_LEFT);
          $serie = "PP".$seri;
          $correlativo = '00000001';
        }else{
          $corre = $ultimo["ultimocorrelativo"] + 1;
          $correlativo = str_pad($corre,8,"0",STR_PAD_LEFT);
        }
      }

     $fecha_todo = date('Y-m-d H:i:s');
     $estado ="Aceptado";
		$sql="INSERT INTO venta (idcliente,idusuario,codigotipo_comprobante,serie,correlativo,fecha_hora,total_descuentos,  total_igv,total_venta,leyenda,estado,idmoneda) VALUES ('$idsucursal','$idusuario','$codigotipo_comprobante','$serie','$correlativo','$fecha_todo','0','0','0','','$estado','1')";
		// return ejecutarConsulta($sql);
		$idventanew=ejecutarConsulta_retornarID($sql);

		$num_elementos=0;
		$sw=true;

		while ($num_elementos<count($idarticulo)) {
			$item = $num_elementos+1;

			$sql_detalle = "INSERT INTO detalle_venta(idventa, idarticulo,cantidad,precio_venta,fecha_mas_vendido,item,serie,estado) VALUES ('$idventanew', '$idarticulo[$num_elementos]','$cantidad[$num_elementos]','0','$fecha_hora','$item','$serieArticulo[$num_elementos]','$estado')";
			ejecutarConsulta($sql_detalle) or $sw = false;
			$num_elementos=$num_elementos + 1;

		}
  
		
		 return $sw;
	}

	// public function selectTipoComprobante(){
	// 	$sql="SELECT * from tipo_comprobante WHERE codigotipo_comprobante in (3) order by codigotipo_comprobante desc";
	// 	return ejecutarConsulta($sql);
	// }

	// public function selectMoneda(){
	// 	$sql="SELECT * FROM moneda";
	// 	return ejecutarConsulta($sql);
	// }

	public function anular($idventa)
	{
		$sql="UPDATE venta SET estado='Anulado' WHERE idventa='$idventa'";
		return ejecutarConsulta($sql);
	}



	public function mostrar($idventa)
	{
		$sql="SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,v.codigotipo_comprobante,tc.descripcion_tipo_comprobante,v.serie,v.correlativo,v.impuesto,v.op_gravadas,v.op_inafectas,v.op_exoneradas,v.op_gratuitas,v.isc,v.total_venta,v.idmoneda,m.descripcion FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario INNER JOIN tipo_comprobante tc ON v.codigotipo_comprobante=tc.codigotipo_comprobante INNER JOIN moneda m ON v.idmoneda=m.idmoneda WHERE v.idventa='$idventa'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetalle($idventa)
	{
		$sql="SELECT dv.idventa,dv.idarticulo,a.nombre,a.unidad_medida,a.afectacion,dv.cantidad,dv.precio_venta,dv.descuento,dv.serie,(dv.cantidad*dv.precio_venta-dv.descuento) as subtotal,v.op_gravadas,v.op_inafectas,v.op_exoneradas,v.op_gratuitas,v.isc,v.total_venta,v.total_descuentos,v.total_igv FROM detalle_venta dv inner join articulo a on dv.idarticulo=a.idarticulo inner join venta v on v.idventa=dv.idventa where dv.idventa='$idventa'";
		return ejecutarConsulta($sql);
	}

	// INSERT INTO `detalle_venta` (`iddetalle_venta`, `idventa`, `idarticulo`, `cantidad`, `precio_venta`, `descuento`, `afectacion`) VALUES (NULL, '13', '1', '3', '3', '0', NULL)

	public function listar()
	{
		$sql="SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,v.codigotipo_comprobante,tc.descripcion_tipo_comprobante,v.serie,v.correlativo,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario INNER JOIN tipo_comprobante tc ON v.codigotipo_comprobante=tc.codigotipo_comprobante where v.codigotipo_comprobante = 13 ORDER by v.idventa desc";
		return ejecutarConsulta($sql);
	}

	public function ventacabecera($idventa){
		$sql="SELECT v.idventa,v.idcliente,p.nombre as cliente,p.direccion,p.tipo_documento,p.num_documento,p.email,p.telefono,v.idusuario,u.nombre as usuario,v.codigotipo_comprobante,v.serie,v.correlativo,date(v.fecha_hora) as fecha,v.fecha_hora as fechaCompleta,v.impuesto,v.op_gravadas,v.op_inafectas,v.op_exoneradas,v.op_gratuitas,v.isc,v.total_venta,v.total_descuentos,v.total_igv,v.idmoneda FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idventa='$idventa'";
		return ejecutarConsulta($sql);
	}

	public function ventadetalle($idventa){
		$sql="SELECT a.nombre as articulo,a.unidad_medida,a.descripcion_otros,a.afectacion,d.cantidad,d.precio_venta,d.descuento,d.serie,(d.cantidad*d.precio_venta-d.descuento) as subtotal FROM detalle_venta d INNER JOIN articulo a ON d.idarticulo=a.idarticulo WHERE d.idventa='$idventa'";
		return ejecutarConsulta($sql);
	}


	public function listarVentaCredito(){
    $sql = "SELECT v.idventa,u.nombre as usuario,p.nombre as cliente,v.codigotipo_comprobante,v.total_venta,v.fecha_hora,v.serie,v.correlativo, v.estado FROM venta as v inner join persona p on v.idcliente = p.idpersona inner join usuario u on u.idusuario=v.idusuario WHERE v.codigotipo_comprobante=11 AND v.estado='Credito'";
      return ejecutarConsulta($sql);
  }

	
	
}
 ?>
