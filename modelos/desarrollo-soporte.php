<?php
//Incluimos conexion a la base de trader_cdlrisefall3methods
require "../config/Conexion.php";

Class Desarrollo
{
  //Implementando nuestro constructor
  public function __construct()
  {


  }
  //Implementamos metodo para insertar registro
    public function insertar($idcliente,$idusuario,$fecha_ingreso,$estado_servicio,$estado_entrega,$estado_pago,$nombre_proyecto,$costo_desarrollo)
    {
      $fecha_ingreso = date("Y-m-d"); // Obtener la fecha y hora actual
      $sql="INSERT INTO desarrollo (idcliente,idusuario,fecha_ingreso,estado_servicio,estado_entrega,estado_pago,nombre_proyecto,costo_desarrollo)
      VALUES ('$idcliente','$idusuario','$fecha_ingreso','$estado_servicio','$estado_entrega','$estado_pago','$nombre_proyecto','$costo_desarrollo')";
     return ejecutarConsulta($sql);
   }


   // Dentro del método insertarProcesoDesarrollo de la clase Desarrollo
public function insertarProcesoDesarrollo($idproc_desarrollo, $iddesarrollo,$AN_fecha_inicio,$MAN_fecha_termino)
{
   $AN_fecha_inicio = date("Y-m-d");
   $MAN_fecha_termino = date("Y-m-d"); // Obtener la fecha y hora actual // Obtener la fecha y hora actual
    $sql = "INSERT INTO proceso_desarrollo (idproc_desarrollo, iddesarrollo,AN_fecha_inicio,MAN_fecha_termino)
            VALUES ('$idproc_desarrollo', '$iddesarrollo','$AN_fecha_inicio', '$MAN_fecha_termino')";
            
    return ejecutarConsulta($sql);
}

    //Implementamos un metodo para editar registro
    public function editar(
    $iddesarrollo,
    $estado_servicio,
    $estado_entrega,
    $estado_pago,
    $nombre_proyecto,
    $costo_desarrollo)
    {
      $sql="UPDATE desarrollo SET 
      estado_servicio='$estado_servicio',
      estado_entrega='$estado_entrega',
      estado_pago='$estado_pago',
      nombre_proyecto='$nombre_proyecto',
      costo_desarrollo='$costo_desarrollo'
      WHERE iddesarrollo='$iddesarrollo'";
      return ejecutarConsulta($sql);
      //var_dump($iddesarrollo);
  }
  
  public function insertarPagos($iddesarrollo,$fecha, $monto, $saldo, $tipo_pago) {
    $sql_pago = "INSERT INTO det_pag_desarrollo (iddesarrollo,fecha,monto,saldo,tipo_pago)
                 VALUES ('$iddesarrollo','$fecha', '$monto', '$saldo','$tipo_pago')";
    
    return ejecutarConsulta($sql_pago);
}
public function insertarIntegrantes($nombre_integrantes) {
  // Obtener el último ID de desarrollo insertado
  $sql_last_id = "SELECT MAX(iddesarrollo) AS max_id FROM desarrollo";
  $result = ejecutarConsulta($sql_last_id);
  $row = mysqli_fetch_assoc($result);
  $ultimo_id = $row['max_id'];
  // Incrementar el último ID para obtener el nuevo ID de desarrollo
  $nuevo_id = $ultimo_id + 1;
  // Insertar el nuevo integrante con el nuevo ID de desarrollo
  $sql_insert = "INSERT INTO integrantes_desarrollo (iddesarrollo, nombre_integrantes)
                 VALUES ('$nuevo_id', '$nombre_integrantes')";

  return ejecutarConsulta($sql_insert);
}
public function EditarIntegrante($iddesarrollo,$nombre_integrantes) {
  $sql= "INSERT INTO integrantes_desarrollo (iddesarrollo,nombre_integrantes)
               VALUES ('$iddesarrollo','$nombre_integrantes')";
  return ejecutarConsulta($sql);
}

      public function mostrarPagos($iddesarrollo){
        $sql="SELECT iddesarrollo,fecha,monto,saldo,tipo_pago,iddet_pag_desarrollo FROM det_pag_desarrollo WHERE iddesarrollo='$iddesarrollo'";
        return ejecutarConsulta($sql);
      }
      function mostrarIntegrantes($iddesarrollo) {
        $sql = "SELECT iddesarrollo, nombre_integrantes, idintegrant_desarrollo FROM integrantes_desarrollo WHERE iddesarrollo = '$iddesarrollo'";
        return ejecutarConsulta($sql);
    }


   
   
      //Implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($iddesarrollo)
    {
      $sql = "SELECT d.iddesarrollo,d.idcliente,d.estado_servicio, d.estado_entrega,d.estado_pago,d.costo_desarrollo,p.num_documento,p.telefono,p.direccion, d.nombre_proyecto, dp.fecha,dp.monto, dp.saldo, dp.tipo_pago
      FROM desarrollo d
      LEFT JOIN persona p ON d.idcliente = p.idpersona
      LEFT JOIN det_pag_desarrollo dp ON d.iddesarrollo = dp.iddesarrollo
      WHERE d.iddesarrollo ='$iddesarrollo'";
      //echo $sql;
      return ejecutarConsultaSimpleFila($sql);

    }
    public function edit($iddesarrollo)
    {
      $sql = "SELECT d.iddesarrollo,d.idcliente,d.estado_servicio, d.estado_entrega,d.estado_pago,d.costo_desarrollo,p.num_documento,p.telefono,p.direccion, d.nombre_proyecto, dp.fecha,dp.monto, dp.saldo, dp.tipo_pago
      FROM desarrollo d
      LEFT JOIN persona p ON d.idcliente = p.idpersona
      LEFT JOIN det_pag_desarrollo dp ON d.iddesarrollo = dp.iddesarrollo
      WHERE d.iddesarrollo ='$iddesarrollo'";
      //echo $sql;
      return ejecutarConsultaSimpleFila($sql);

    }
    //Implementar metodo para listar los registros
    public function listar()
    {
      $sql="SELECT d.iddesarrollo,d.estado_servicio,d.estado_pago,d.costo_desarrollo,
      DATE_FORMAT(d.fecha_ingreso,  '%d-%m-%Y') AS fecha_ingreso, 
      DATE_FORMAT(pd.AN_fecha_inicio, '%d-%m-%Y') AS AN_fecha_inicio, 
      DATE_FORMAT(pd.MAN_fecha_termino, '%d-%m-%Y') AS MAN_fecha_termino, 
      d.nombre_proyecto, p.nombre
      FROM desarrollo d
      left join proceso_desarrollo  pd ON  d.iddesarrollo=pd.iddesarrollo
      LEFT JOIN persona p ON d.idcliente = p.idpersona
      ORDER BY d.iddesarrollo DESC" ;
      return ejecutarConsulta($sql);
    }

    public function mostrardesarrollo($iddesarrollo)
    {
      $sql="SELECT d.iddesarrollo,d.nombre_proyecto,d.estado_servicio, d.estado_pago,dp.fecha, dp.monto, dp.saldo,dp.tipo_pago,  i.nombre_integrantes as integrante, d.garantia_desarrollo
       FROM desarrollo d
       left JOIN integrantes_desarrollo i ON d.iddesarrollo = i.iddesarrollo
       INNER JOIN persona p ON d.idcliente = p.idpersona
       LEFT JOIN det_pag_desarrollo dp ON d.iddesarrollo=dp.iddesarrollo
       where d.iddesarrollo='$iddesarrollo'";
      //echo $sql;
      return ejecutarConsulta($sql);

    }

    /*public function mostrarDatoCliente($id){
      $sql="SELECT * from persona WHERE idpersona = '$id'";
      return ejecutarConsultaSimpleFila($sql);
  
    }*/

    public function pagodesarrollo($iddesarrollo){
      $sql="SELECT fecha,monto,saldo,tipo_pago
      FROM det_pag_desarrollo
      WHERE iddesarrollo='$iddesarrollo'";
      return ejecutarConsulta($sql);
    }
  
    public function listarNombresProyectos()
    {
        $sql = "SELECT nombre_proyecto FROM de  sarrollo";
        
        // Ejecuta la consulta utilizando tu función ejecutarConsulta y devuelve el resultado.
        return ejecutarConsulta($sql);
    }
    
  }

 ?>
