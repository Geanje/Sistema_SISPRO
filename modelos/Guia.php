<?php 

date_default_timezone_set('America/Lima'); 
require_once('../config/Conexion.php');


Class Guia
{

	public function __construct(){

	}

  public function insertar($id_cliente,$id_venta,$id_tranportista,$id_moti_translado,$dire_partida,$dire_llegada,$moda_transportista,$tipo_translado,$peso_bruto,$fecha_emision,$fecha_translado,
          $idarticulo,$cantidad,$precio_venta,$descuento,$serieArticulo){

    $sql="INSERT INTO guia (id_cliente,
                            id_venta,
                            id_tranportista,
                            id_moti_translado,
                            dire_partida,
                            dire_llegada,
                            moda_transportista,
                            tipo_translado,
                            peso_bruto,
                            fecha_emision,
                            fecha_translado) 
    
    VALUES ('$id_cliente','$id_venta','$id_tranportista','$id_moti_translado','$dire_partida','$dire_llegada','$moda_transportista','$tipo_translado','$peso_bruto','$fecha_emision','$fecha_translado','$op_gratuitas','$isc','$total_descuentos','$total_igv','$total_venta','$leyenda','Aceptado','$idmoneda',null, '$codigotipo_pago')";
    // return ejecutarConsulta($sql);
    $idguianew=ejecutarConsulta_retornarID($sql);

    $num_elementos=0;

    while ($num_elementos<count($idarticulo)) {

      $sql_detalle = "INSERT INTO detalle_guia(idguia,idarticulo,cantidad) 
      VALUES ('$idguianew', '$idarticulo[$num_elementos]','$cantidad[$num_elementos]')";
      ejecutarConsulta($sql_detalle);
      $num_elementos=$num_elementos + 1;

    }

  }


  public function selectTipoComprobante(){
    $sql="SELECT * from tipo_comprobante WHERE codigotipo_comprobante = 8";
    return ejecutarConsulta($sql);

	}

  public function mostrarDatoTransportista($id){
    $sql="SELECT * from persona WHERE idpersona = '$id'";
    return ejecutarConsultaSimpleFila($sql);

	}



  public function selectMotivoTraslado(){
		$sql="SELECT * from motivo_traslado WHERE codigo_traslado";
		return ejecutarConsulta($sql);
	}

  public function selectPuntoPartida(){
		$sql="SELECT * FROM perfil WHERE direccion ='SEC. PANAMERICANA NORTE SANTA ROSA CAL. LOTIZACION CALLE N 27'";
		return ejecutarConsulta($sql);
	}

	public function selectMoneda(){
		$sql="SELECT * FROM moneda";
		return ejecutarConsulta($sql);
	}

	public function anular($idguia)
	{
		$sql="UPDATE venta SET estado='Anulado' WHERE idventa='$idguia'";
		return ejecutarConsulta($sql);
	}

	public function mostrar($idguia)
	{
		$sql="SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,v.codigotipo_comprobante,tc.descripcion_tipo_comprobante,v.serie,v.correlativo,v.impuesto,v.op_gravadas,v.op_inafectas,v.op_exoneradas,v.op_gratuitas,v.isc,v.total_venta,v.idmoneda,m.descripcion FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario INNER JOIN tipo_comprobante tc ON v.codigotipo_comprobante=tc.codigotipo_comprobante INNER JOIN moneda m ON v.idmoneda=m.idmoneda WHERE v.idventa='$idguia'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetalle($idguia)
	{
		$sql="SELECT dv.idventa,dv.idarticulo,a.nombre,a.unidad_medida,a.afectacion,dv.cantidad,dv.precio_venta,dv.descuento,dv.serie,(dv.cantidad*dv.precio_venta-dv.descuento) as subtotal,v.op_gravadas,v.op_inafectas,v.op_exoneradas,v.op_gratuitas,v.isc,v.total_venta FROM detalle_venta dv inner join articulo a on dv.idarticulo=a.idarticulo inner join venta v on v.idventa=dv.idventa where dv.idventa='$idguia'";
		return ejecutarConsulta($sql);
	}

	// INSERT INTO `detalle_venta` (`iddetalle_venta`, `idventa`, `idarticulo`, `cantidad`, `precio_venta`, `descuento`, `afectacion`) VALUES (NULL, '12', '1', '3', '3', '0', NULL)


	public function listarguiaprincipal()
	{
    $sql="SELECT g.id,g.fecha_emision as fecha,g.id_cliente,p.nombre as cliente,g.serie_correlativo,g.serie_correlativo_guia ,g.id_moti_translado as moti_translado
    FROM guia g 
    INNER JOIN persona p ON g.id_cliente=p.idpersona";
		return ejecutarConsulta($sql);
	}

  public function listarComprobantes()
  {
    $cliente=$_REQUEST["idcliente"];
    $sql="SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,v.codigotipo_comprobante,tc.descripcion_tipo_comprobante,v.serie,v.correlativo,v.total_venta,v.fecha_ven,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario INNER JOIN tipo_comprobante tc ON v.codigotipo_comprobante=tc.codigotipo_comprobante WHERE v.estado!='Cotizado' AND v.estado!='AnuladoC' AND v.estado!='Anulado' AND v.codigotipo_comprobante IN (1,3) AND v.idcliente='$cliente' ORDER BY v.idventa DESC";
    return ejecutarConsulta($sql);
  }

  public function listardetallecomprobantes($idguia){
		$sql="SELECT dv.idventa,dv.idarticulo,dv.serie,a.nombre,a.unidad_medida,a.afectacion,dv.cantidad,dv.precio_venta,dv.descuento,(dv.cantidad*dv.precio_venta-dv.descuento) as subtotal, v.op_gravadas,v.op_inafectas,v.op_exoneradas,v.op_gratuitas,v.isc,v.total_venta 
    FROM detalle_venta dv 
    inner join articulo a on dv.idarticulo=a.idarticulo 
    inner join venta v on v.idventa=dv.idventa where dv.idventa='$idguia'";
		return ejecutarConsulta($sql);
	}

	public function ventacabecera($idguia){
		$sql="SELECT g.fecha_emision,g.fecha_translado,g.id_moti_translado,g.id_venta,g.moda_transportista,g.dire_llegada,g.dire_partida,p.nombre,p.num_documento,g.serie_correlativo,g.serie_correlativo_guia,g.peso_bruto,v.referencia,
    (SELECT nombre FROM persona WHERE idpersona=g.id_tranportista) as nombret,
    (SELECT num_documento FROM persona where idpersona=g.id_tranportista) as num_documentot
    FROM guia g 
    inner join venta v on g.id_venta=v.idventa 
    inner join persona p on g.id_cliente=p.idpersona 
    WHERE g.id='$idguia'";
		return ejecutarConsulta($sql);
	}

	public function ventadetalle($idguia){
		$sql="SELECT dg.cantidad, dg.id_articulo, dg.id_guia, a.nombre,a.unidad_medida FROM detalle_guia dg inner join articulo a on dg.id_articulo=a.idarticulo WHERE dg.id_guia='$idguia'";
		return ejecutarConsulta($sql);
	}

  public function ultimoCorrelativo(){
    $sql="SELECT idventa,codigotipo_comprobante,correlativo,serie 
    from venta where codigotipo_comprobante=7
    order by idventa desc limit 1";
    return ejecutarConsultaSimpleFila($sql);
  }

	

	
	
}
 ?>
