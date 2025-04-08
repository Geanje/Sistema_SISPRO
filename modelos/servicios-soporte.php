<?php
//Incluimos conexion a la base de trader_cdlrisefall3methods
require "../config/Conexion.php";

class Soporte
{
  //Implementando nuestro constructor
  public function __construct()
  {
  }
  //Implementamos metodo para insertar registro
  public function insertar(
    $codigo_soporte,
    $nombre_cliente,
    $area_servicio,
    $codigotipo_comprobante,
    $telefono,
    $tecnico_respon,
    $fecha_ingreso,
    $fecha_salida,
    $marca,
    $problema,
    $solucion,
    $tipo_servicio,
    $estado_servicio,
    $estado_pago,
    $total,
    $estado_entrega,
    $direccion,
    $accesorio,
    $recomendacion,
    $garantia
  ) {
    $saber = "SELECT serie,correlativo FROM soporte WHERE codigotipo_comprobante='$codigotipo_comprobante'";
    $saberExiste = ejecutarConsultaSimpleFila($saber);
    if ($saberExiste["serie"] == null and $saberExiste["correlativo"] == null) {
      if ($codigotipo_comprobante == 20 || $codigotipo_comprobante == 21) {
        if ($codigotipo_comprobante == 20) {
          $serie = 'ST01';
        } else {
          $serie = 'SG01';
        }
      }
      $correlativo = '00000001';
    } else {
      $sqlmaxserie = "SELECT max(serie) as maxSerie FROM soporte WHERE codigotipo_comprobante='$codigotipo_comprobante' ";
      $maxserie = ejecutarConsultaSimpleFila($sqlmaxserie);
      $serie = $maxserie["maxSerie"];
      $ultimoCorrelativo = "SELECT max(correlativo) as ultimocorrelativo,serie,correlativo FROM soporte WHERE codigotipo_comprobante='$codigotipo_comprobante'  and serie='$serie'";
      $ultimo = ejecutarConsultaSimpleFila($ultimoCorrelativo);
      if ($ultimo["ultimocorrelativo"] == '99999999') {
        $ser = substr($serie, 1) + 1;
        $seri = str_pad((string)$ser, 3, "0", STR_PAD_LEFT);
        if ($codigotipo_comprobante == 20 || $codigotipo_comprobante == 21) {
          if ($codigotipo_comprobante == 20) {
            $serie = "ST" . $seri;
          } else {
            $serie = "SG" . $seri;
          }
        }
        $correlativo = '00000001';
      } else {
        $corre = $ultimo["ultimocorrelativo"] + 1;
        $correlativo = str_pad($corre, 8, "0", STR_PAD_LEFT);
      }
    }
    $sql = "INSERT INTO soporte (codigo_soporte,nombre_cliente,codigotipo_comprobante,serie,correlativo,area_servicio,telefono,tecnico_respon,fecha_ingreso,fecha_salida,marca,
    problema,solucion,tipo_servicio,estado_servicio,estado_pago,total,estado_entrega,direccion,accesorio,recomendacion,garantia)
      VALUES ('$codigo_soporte','$nombre_cliente','$codigotipo_comprobante','$serie','$correlativo','$area_servicio','$telefono','$tecnico_respon','$fecha_ingreso','$fecha_salida',
      '$marca','$problema','$solucion','$tipo_servicio','$estado_servicio','$estado_pago','$total','$estado_entrega','$direccion','$accesorio','$recomendacion','$garantia')";
    return ejecutarConsulta($sql);
  }
  //Implementamos un metodo para editar registro
  public function editar($idsoporte, $nombre_cliente, $codigo_servicio, $area_servicio, $telefono, $tecnico_respon, $fecha_ingreso, $fecha_salida, $marca, $problema, $solucion, $tipo_servicio, $codigo_soporte, $estado_servicio, $estado_pago, $total, $estado_entrega, $direccion, $accesorio, $recomendacion, $garantia) //,,$idsoporten,$idusuario,$fecha_pago, $cuotas, $saldos, $tipo_pago
  {
    $sql = "UPDATE soporte SET 
      nombre_cliente='$nombre_cliente',
      codigo_servicio='$codigo_servicio',
      area_servicio='$area_servicio',
      telefono='$telefono',
      tecnico_respon='$tecnico_respon',
      fecha_ingreso='$fecha_ingreso',
      fecha_salida='$fecha_salida',
      marca='$marca',
      solucion='$solucion',
      problema='$problema',
      tipo_servicio='$tipo_servicio',
      codigo_soporte='$codigo_soporte' ,
      estado_servicio='$estado_servicio',
      estado_pago='$estado_pago',
      total='$total',
      estado_entrega='$estado_entrega',
      direccion='$direccion',
      accesorio='$accesorio',
      recomendacion='$recomendacion',
      garantia='$garantia' 
      WHERE idsoporte='$idsoporte'";
    return ejecutarConsulta($sql);
    //var_dump($idsoporte);
  }
  public function insertarPagos($nombre_cliente, $idsoporte, $idusuario, $fecha_pago, $cuotas, $saldos, $tipo_pago)
  {
    $sql_pago = "INSERT INTO soporte_pago (idcliente, idsoporte, idusuario, fecha_pago, cuota, saldo, tipo_pago)
                 VALUES ('$nombre_cliente', '$idsoporte', '$idusuario', '$fecha_pago', '$cuotas', '$saldos', '$tipo_pago')";

    return ejecutarConsulta($sql_pago);
  }

  public function insertarIntegrantes($idsoporte, $nombre_integrantes)
  {
    $sql = "INSERT INTO integrantes_ser_general (idsoporte,nombre_integrantes) VALUES ('$idsoporte','$nombre_integrantes')";
    return ejecutarConsulta($sql);
  }


  //Implementamos un metodo para eliminar registro
  public function eliminar($idsoporte)
  {
    $sql = "DELETE FROM soporte WHERE idsoporte = '$idsoporte'";
    ejecutarConsulta($sql);
  }


  public function mostrarPagos($idsoporte)
  {
    $sql = "SELECT idsoporte,fecha_pago,cuota,saldo,tipo_pago,idsoportepago FROM soporte_pago WHERE idsoporte='$idsoporte'";
    return ejecutarConsulta($sql);
  }

  public function mostrarintegrantes($idsoporte)
  {
    $sql = "SELECT idsoporte,nombre_integrantes,id_integrante_servicio FROM integrantes_ser_general WHERE idsoporte='$idsoporte'";
    return ejecutarConsulta($sql);
  }


  //Implementamos un metodo para mostrar los datos de un registro a modificar
  public function mostrar($idsoporte)
  {
    $sql = "SELECT s.idsoporte, s.fecha_ingreso, s.nombre_cliente, s.tipo_equipo, s.tipo_servicio, s.codigo_servicio, s.codigotipo_comprobante, s.area_servicio, s.estado_servicio, s.estado_entrega, s.estado_pago, s.tecnico_respon, s.solucion, s.marca, s.problema, s.total, sp.cuota, sp.saldo, s.fecha_ingreso, s.direccion, s.accesorio, s.recomendacion, s.garantia, p.nombre, p.telefono, p.direccion, s.codigo_soporte, s.fecha_salida, t.nombre as tecnico, p.nombre as cliente, p.telefono as telefono, sp.fecha_pago, sp.tipo_pago, tc.descripcion_tipo_comprobante
    FROM soporte s
    LEFT JOIN tecnico t ON s.tecnico_respon = t.idtecnico
    LEFT JOIN persona p ON s.nombre_cliente = p.idpersona
    LEFT JOIN soporte_pago sp ON s.idsoporte = sp.idsoporte
    LEFT JOIN tipo_comprobante tc ON s.codigotipo_comprobante = tc.codigotipo_comprobante
    WHERE s.idsoporte='$idsoporte'";
    //echo $sql;
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar metodo para listar los registros
  public function listar()
  {
    $sql = "SELECT s.idsoporte,s.fecha_ingreso,s.nombre_cliente, s.codigo_servicio, s.area_servicio,s.codigo_soporte, s.tipo_servicio,s.serie,s.correlativo, s.estado_servicio, s.estado_entrega, s.estado_pago, s.tecnico_respon, s.solucion, s.marca, s.telefono,s.problema, s.total, s.cuota, s.saldo, s.fecha_ingreso, s.direccion, s.accesorio, s.recomendacion, s.garantia, p.nombre ,t.nombre as tecnico
      FROM soporte s
      left JOIN tecnico t ON s.tecnico_respon=t.idtecnico
      INNER JOIN persona p ON s.nombre_cliente=p.idpersona";
    return ejecutarConsulta($sql);
  }

  public function listarSalidas()
  {
    $sql = "SELECT v.idventa, DATE(v.fecha_hora) as fecha, v.idcliente, p.nombre as cliente, u.idusuario, u.nombre as usuario, v.codigotipo_comprobante, tc.descripcion_tipo_comprobante, v.serie, v.correlativo, v.total_venta, v.impuesto, v.estado, s.area_servicio, s.tipo_servicio, CONCAT(s.serie, s.correlativo) AS CodigoServ FROM venta v INNER JOIN persona p ON v.idcliente = p.idpersona INNER JOIN usuario u ON v.idusuario = u.idusuario INNER JOIN tipo_comprobante tc ON v.codigotipo_comprobante = tc.codigotipo_comprobante LEFT JOIN soporte s ON v.idsoporte = s.idsoporte WHERE v.estado != 'Cotizado' AND v.estado != 'AnuladoC' AND v.codigotipo_comprobante = 22 ORDER BY v.idventa DESC";
    return ejecutarConsulta($sql);
  }

  public function mostrarSoporte($idsoporte)
  {
    $sql = "SELECT s.idsoporte,s.fecha_ingreso,s.nombre_cliente, s.tipo_servicio, s.codigo_servicio, s.area_servicio, s.estado_servicio, s.estado_entrega, s.estado_pago, s.tecnico_respon, s.solucion, s.marca,s.problema, s.total, sp.cuota, sp.saldo, s.fecha_ingreso, s.direccion, s.accesorio, s.recomendacion, s.garantia, p.nombre, p.telefono , p.direccion, s.codigo_soporte,s.fecha_salida,t.nombre as tecnico, p.nombre as cliente, p.telefono as telefono, sp.fecha_pago, sp.tipo_pago
       FROM soporte s
       left JOIN tecnico t ON s.tecnico_respon=t.idtecnico
       LEFT JOIN persona p ON s.nombre_cliente=p.idpersona
       LEFT JOIN soporte_pago sp ON s.idsoporte=sp.idsoporte
       where s.idsoporte='$idsoporte'";
    //echo $sql;
    return ejecutarConsulta($sql);
  }

  /*public function mostrarDatoCliente($id){
      $sql="SELECT * from persona WHERE idpersona = '$id'";
      return ejecutarConsultaSimpleFila($sql);
  
    }*/

  public function pagoSoporte($idsoporte)
  {
    $sql = "SELECT cuota,saldo,fecha_pago,tipo_pago
      FROM soporte_pago
      WHERE idsoporte='$idsoporte'";
    return ejecutarConsulta($sql);
  }

  public function selectTipoComprobante()
  {
    $sql = "SELECT * from tipo_comprobante WHERE codigotipo_comprobante in (20,21) order by codigotipo_comprobante desc";
    return ejecutarConsulta($sql);
  }
}
