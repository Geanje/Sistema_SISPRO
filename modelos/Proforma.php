<?php 
//date_default_timezone_set('America/Lima');// 
require_once('../config/Conexion.php');

Class Proforma
{
  //private $conexion;
  
    public function __construct(){
      //$this->conexion =  Conexion::conectar();
      //$this->conexion->conectar();

  }


  public function insertar($idusuario,$idcliente,$correlativo,$fecha_hora,$validez,$tiempoEntrega,$idarticulo,$cantidad,$precio_venta,$serieProforma,$total_venta,$igv_total,$igv_asig){

     $saber = "SELECT serie,correlativo FROM proforma";
    $saberExiste = ejecutarConsultaSimpleFila($saber);
    if($saberExiste["serie"] == null and $saberExiste["correlativo"] == null){
      
        $serie='P001';
      $correlativo='00000001';
    }else{
      $sqlmaxserie="SELECT max(serie) as maxSerie FROM proforma";
      $maxserie = ejecutarConsultaSimpleFila($sqlmaxserie);
      $serie= $maxserie["maxSerie"];
      $ultimoCorrelativo="SELECT max(correlativo) as ultimocorrelativo,serie,correlativo FROM proforma WHERE serie='$serie'";
      $ultimo = ejecutarConsultaSimpleFila($ultimoCorrelativo);
      if($ultimo["ultimocorrelativo"] =='99999999'){
        $ser = substr($serie,1)+1;
        $seri= str_pad((string)$ser,3,"0",STR_PAD_LEFT);
          $serie = "P".$seri;
        $correlativo = '00000001';
      }else{
        $corre = $ultimo["ultimocorrelativo"] + 1;
        $correlativo = str_pad($corre,8,"0",STR_PAD_LEFT);
      }
    }


    $sql="INSERT INTO proforma (idusuario,idcliente,correlativo,fecha_hora,estado,validez,tiempoEntrega,serie,total_venta,igv_total,igv_asig) VALUES ('$idusuario','$idcliente','$correlativo','$fecha_hora','AceptadoP','$validez','$tiempoEntrega','$serie','$total_venta','$igv_total','$igv_asig')";
    //return ejecutarConsulta($sql);
    $idproformanew=ejecutarConsulta_retornarID($sql);
   //var_dump($validez);

		$num_elementos = count($idarticulo);
    $sw = true;
    var_dump($num_elementos);
     for ($i = 0; $i < $num_elementos; $i++) {
      $sql_detalle = "INSERT INTO detalle_proforma(idproforma,idarticulo,cantidad,precio_venta,serie) VALUES ('$idproformanew','$idarticulo[$i]','$cantidad[$i]','$precio_venta[$i]','$serieProforma[$i]')";
      ejecutarConsulta($sql_detalle) or $sw = false;
    }
    
    return $sw;
  }


  public function anular($idproforma)
  {
    $sql="UPDATE proforma SET estado='AnuladoP' WHERE idproforma='$idproforma'";
    return ejecutarConsulta($sql);
  }

  public function mostrar($idproforma)
  {
    $sql="SELECT v.idproforma,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,v.tipo_proforma,v.igv_total,v.correlativo,v.total_venta,v.estado FROM proforma v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idproforma='$idproforma'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function listarDetalle($idproforma)
  {
    $sql="SELECT dp.idproforma,dp.descripcion,dp.cantidad,dp.precio_venta,(dp.cantidad*dp.precio_venta) as subtotal,p.precio_venta FROM detalle_proforma dp inner join proforma p on p.idproforma=dp.idproforma where dp.idproforma='$idproforma'";
    return ejecutarConsulta($sql);
  }


  public function listar()
  {
    $sql="SELECT p.idproforma,DATE(p.fecha_hora) as fecha,p.idcliente,pe.nombre as cliente,p.idusuario,u.nombre as usuario,p.total_venta,p.estado,p.serie,p.correlativo 
    FROM proforma p 
    INNER JOIN persona pe ON p.idcliente=pe.idpersona 
    INNER JOIN usuario u ON p.idusuario=u.idusuario 
    WHERE p.estado!='AnuladoP' OR  p.estado!='AceptadoP' ORDER by p.idproforma desc";
    return ejecutarConsulta($sql);
  }

  public function ventacabecera($idproforma){
    $sql="SELECT v.idproforma, v.idcliente, p.nombre AS cliente, p.direccion, p.tipo_documento, p.num_documento, p.email, v.idusuario, u.nombre AS usuario, v.correlativo, v.serie ,DATE( v.fecha_hora ) AS fecha, v.igv_total,v.igv_asig, v.total_venta, v.validez, v.tiempoEntrega
    FROM proforma v
    INNER JOIN persona p ON v.idcliente = p.idpersona
    INNER JOIN usuario u ON v.idusuario = u.idusuario
    WHERE v.idproforma = '$idproforma'";
    return ejecutarConsulta($sql);
  }

  public function selecTipoIGV(){
    $sql="SELECT * FROM `igv`";
    return ejecutarConsulta($sql);
  }

  public function ventadetalle($idproforma){
    $sql="SELECT d.iddetalle_proforma, d.idarticulo, d.cantidad, d.serie, d.precio_venta, (d.cantidad * d.precio_venta) as subtotal, a.afectacion, a.nombre 
    FROM detalle_proforma d 
    INNER JOIN articulo a ON d.idarticulo = a.idarticulo 
    WHERE d.idproforma='$idproforma'";
    return ejecutarConsulta($sql);
  }

  public function selectMoneda(){
		$sql="SELECT * FROM moneda";
		return ejecutarConsulta($sql);
	}
 
}
 ?>
