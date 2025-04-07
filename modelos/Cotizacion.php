<?php
date_default_timezone_set('America/Lima');
require_once('../config/Conexion.php');

class Cotizacion
{

	public function __construct()
	{
	}

	public function insertar($idusuario, $idcliente, $correlativo, $tipo_proforma, $igv_total, $referencia, $total_venta, $fecha_hora, $descripcion, $validez, $cantidad, $precio, $igv_asig)
	{


		$saber = "SELECT serie,correlativo FROM cotizacion";
		$saberExiste = ejecutarConsultaSimpleFila($saber);
		if ($saberExiste["serie"] == null and $saberExiste["correlativo"] == null) {

			$serie = 'C001';
			$correlativo = '00000001';
		} else {
			$sqlmaxserie = "SELECT max(serie) as maxSerie FROM cotizacion";
			$maxserie = ejecutarConsultaSimpleFila($sqlmaxserie);
			$serie = $maxserie["maxSerie"];
			$ultimoCorrelativo = "SELECT max(correlativo) as ultimocorrelativo,serie,correlativo FROM cotizacion WHERE serie='$serie'";
			$ultimo = ejecutarConsultaSimpleFila($ultimoCorrelativo);
			if ($ultimo["ultimocorrelativo"] == '99999999') {
				$ser = substr($serie, 1) + 1;
				$seri = str_pad((string)$ser, 3, "0", STR_PAD_LEFT);
				$serie = "C" . $seri;
				$correlativo = '00000001';
			} else {
				$corre = $ultimo["ultimocorrelativo"] + 1;
				$correlativo = str_pad($corre, 8, "0", STR_PAD_LEFT);
			}
		}


		//$validezs=implode(',',$validez);
		$sql = "INSERT INTO cotizacion(idusuario,idcliente,correlativo,tipo_proforma,igv_total,referencia,total_venta,fecha_hora,validez,estado,serie,igv_asig) VALUES ('$idusuario','$idcliente','$correlativo','$tipo_proforma','$igv_total','$referencia','$total_venta','$fecha_hora','$validez','Cotizado','$serie','$igv_asig')";
		//return ejecutarConsulta($sql);
		$idcotizacionew = ejecutarConsulta_retornarID($sql);
		//var_dump($validez);


		$num_elementos = count($cantidad);
		$sw = true;
		for ($i = 0; $i < $num_elementos; $i++) {
			$sql_detalle = "INSERT INTO detalle_cotizacion(idcotizacion,descripcion,cantidad,precio) VALUES ('$idcotizacionew','$descripcion[$i]','$cantidad[$i]','$precio[$i]')";
			ejecutarConsulta($sql_detalle) or $sw = false;
		}

		return $sw;
	}



	public function selectTipoComprobante()
	{
		$sql = "SELECT * from tipo_comprobante where codigotipo_comprobante=10";
		return ejecutarConsulta($sql);
	}

	public function selectMoneda()
	{
		$sql = "SELECT * FROM moneda";
		return ejecutarConsulta($sql);
	}

	public function anular($idcotizacion)
	{
		$sql = "UPDATE cotizacion SET estado='AnuladoC' WHERE idcotizacion='$idcotizacion'";
		return ejecutarConsulta($sql);
	}

	public function mostrar($idcotizacion)
	{
		$sql = "SELECT v.idcotizacion,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,v.codigotipo_comprobante,tc.descripcion_tipo_comprobante,v.serie,v.correlativo,v.impuesto,v.op_gravadas,v.op_inafectas,v.op_exoneradas,v.op_gratuitas,v.isc,v.precio_venta,v.idmoneda,m.descripcion FROM cotizacion v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario INNER JOIN tipo_comprobante tc ON v.codigotipo_comprobante=tc.codigotipo_comprobante INNER JOIN moneda m ON v.idmoneda=m.idmoneda WHERE v.idcotizacion='$idcotizacion'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetalle($idcotizacion)
	{
		$sql = "SELECT dv.idcotizacion,dv.idarticulo,a.nombre,a.unidad_medida,a.afectacion,dv.cantidad,dv.precio_venta,dv.descuento,dv.serie as serieCotizacion,(dv.cantidad*dv.precio_venta-dv.descuento) as subtotal,v.op_gravadas,v.op_inafectas,v.op_exoneradas,v.op_gratuitas,v.isc,v.precio_venta FROM cotizacion dv inner join articulo a on dv.idarticulo=a.idarticulo inner join cotizacion v on v.idcotizacion=dv.idcotizacion where dv.idcotizacion='$idcotizacion'";
		return ejecutarConsulta($sql);
	}

	// INSERT INTO `detalle_venta` (`iddetalle_venta`, `idventa`, `idarticulo`, `cantidad`, `precio_venta`, `descuento`, `afectacion`) VALUES (NULL, '12', '1', '3', '3', '0', NULL)

	public function listar()
	{
		$sql = "SELECT c.idcotizacion, DATE(c.fecha_hora) as fecha ,c.idcliente,pe.nombre as cliente ,c.idusuario, u.nombre as usuario ,c.total_venta,c.tipo_proforma,c.serie,c.correlativo,c.estado 
		FROM cotizacion c 
		INNER JOIN persona pe ON c.idcliente=pe.idpersona 
		INNER JOIN usuario u ON c.idusuario=u.idusuario  
		WHERE c.estado='Cotizado' OR c.estado='AnuladoC' ORDER BY c.idcotizacion desc";
		return ejecutarConsulta($sql);
	}

	public function ventacabecera($idcotizacion)
	{
		$sql = "SELECT v.idcotizacion,v.idcliente, p.nombre as cliente , p.direccion, p.tipo_documento, p.num_documento,p.email,p.telefono, v.idusuario, u.nombre as usuario ,v.correlativo, date(v.fecha_hora) as fecha ,v.tipo_proforma,v.igv_total,v.igv_asig,v.total_venta,v.validez,v.referencia, v.serie
                FROM cotizacion v
                INNER JOIN persona p ON v.idcliente = p.idpersona
                INNER JOIN usuario u ON v.idusuario = u.idusuario
                WHERE v.idcotizacion = '$idcotizacion'";
		return ejecutarConsulta($sql);
		//echo $sql;
		//echo "Hola";
	}

	public function ventadetalle($idcotizacion)
	{
		$sql = "SELECT c.cantidad,c.precio,c.descripcion,(c.cantidad*c.precio) as subtotal 
		FROM detalle_cotizacion c 
		WHERE c.idcotizacion='$idcotizacion'";
		return ejecutarConsulta($sql);
	}


	public function selecTipoIGV()
	{
		$sql = "SELECT * FROM `igv`";
		return ejecutarConsulta($sql);
	}
}
