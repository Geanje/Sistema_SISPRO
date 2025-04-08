<?php 

require "../config/Conexion.php";

class Confidencial{

  public function __construct(){


  }

  public function insertar($idpersona,$idusuario,$fecha,$gastos_totales,$compras){
  	  $saber = "SELECT serie,correlativo FROM confidencial";
      $saberExiste = ejecutarConsultaSimpleFila($saber);
      if($saberExiste["serie"] == null and $saberExiste["correlativo"] == null){
       
        $serie='Z001';
        $correlativo='00000001';
      }else{
        $sqlmaxserie="SELECT max(serie) as maxSerie FROM confidencial";
        $maxserie = ejecutarConsultaSimpleFila($sqlmaxserie);
        $serie= $maxserie["maxSerie"];
        $ultimoCorrelativo="SELECT max(correlativo) as ultimocorrelativo,serie,correlativo FROM confidencial WHERE serie='$serie'";
        $ultimo = ejecutarConsultaSimpleFila($ultimoCorrelativo);
        if($ultimo["ultimocorrelativo"] =='99999999'){
          $ser = substr($serie,1)+1;
          $seri= str_pad((string)$ser,3,"0",STR_PAD_LEFT);
          
          $serie = "Z".$seri;
          $correlativo = '00000001';
        }else{
          $corre = $ultimo["ultimocorrelativo"] + 1;
          $correlativo = str_pad($corre,8,"0",STR_PAD_LEFT);
        }
      }

      // $serie = "Z001";
      // $correlativo = "00000001";

  	$sql = "INSERT INTO confidencial(idpersona,idusuario,serie,correlativo,fecha,gastos_totales,compras) VALUES('$idpersona','$idusuario','$serie','$correlativo','$fecha','$gastos_totales','$compras')";
  	return ejecutarConsulta($sql);
  }

  public function editar($idconfidencial,$idpersona,$idusuario,$fecha,$gastos_totales,$compras){
  	$sql = "UPDATE confidencial SET idpersona='$idpersona', idusuario='$idusuario', serie = '$serie', correlativo = '$correlativo', gastos_totales = '$gastos_totales', compras = '$compras'";
  	return ejecutarConsulta($sql);
  }

  public function listar(){
  	$sql = "SELECT c.idconfidencial, p.nombre as proveedor, u.nombre as usuario,serie,correlativo,DATE(fecha) as fecha,gastos_totales,compras FROM confidencial c INNER JOIN persona p ON c.idpersona = p.idpersona INNER JOIN usuario u ON c.idusuario = u.idusuario";
  	return ejecutarConsulta($sql);

  }
  public function ventacabecera($idconfidencial){
    $sql = "SELECT c.idconfidencial, p.nombre as proveedor, u.nombre as usuario,serie,correlativo,DATE(fecha) as fecha,gastos_totales,compras FROM confidencial c INNER JOIN persona p ON c.idpersona = p.idpersona INNER JOIN usuario u ON c.idusuario = u.idusuario where idconfidencial='$idconfidencial'";
    return ejecutarConsulta($sql);
  }


  
}



