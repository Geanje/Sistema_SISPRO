<?php

require_once '../config/Conexion.php';

Class Registro {
    public function __construct()
    {
        
    }

    public function insertar($idusuario, $idcliente, $idservicio, $fecha_inicio,$fecha_termino,$monto_pago)
{
    // Obtener el último correlativo de la base de datos
    $saber = "SELECT serie, correlativo FROM registro_servicio";
    $saberExiste = ejecutarConsultaSimpleFila($saber);
    if ($saberExiste["serie"] == null && $saberExiste["correlativo"] == null) {
        $serie = 'CS001';
        $correlativo = '00000001';
    } else {
        $sqlmaxserie = "SELECT max(serie) as maxSerie FROM registro_servicio";
        $maxserie = ejecutarConsultaSimpleFila($sqlmaxserie);
        $serie = $maxserie["maxSerie"];
        $ultimoCorrelativo = "SELECT max(correlativo) as ultimocorrelativo FROM registro_servicio WHERE serie='$serie'";
        $ultimo = ejecutarConsultaSimpleFila($ultimoCorrelativo);
        if ($ultimo["ultimocorrelativo"] == '99999999') {
            $ser = substr($serie, 1) + 1;
            $seri = str_pad((string)$ser, 4, "0", STR_PAD_LEFT);
            $serie = "C" . $seri;
            $correlativo = '00000001';
        } else {
            $corre = $ultimo["ultimocorrelativo"] + 1;
            $correlativo = str_pad($corre, 8, "0", STR_PAD_LEFT);
        }
    }
    
    // Insertar el registro con el nuevo correlativo
    $sql = "INSERT INTO registro_servicio (idusuario, idcliente, idservicio, fecha_inicio, fecha_termino,costo_total, estado, serie, correlativo) 
            VALUES ('$idusuario', '$idcliente', '$idservicio', '$fecha_inicio','$fecha_termino','$monto_pago', '1','$serie' ,'$correlativo')";
    return ejecutarConsulta($sql);
}



    public function editar($id_r_servicio,$idusuario, $idcliente, $idservicio, $fecha_inicio,$fecha_termino,$monto_pago) 
    {
        $sql = "UPDATE registro_servicio SET 
        id_r_servicio = '$id_r_servicio',
        idusuario = '$idusuario',
        idcliente = '$idcliente',
        idservicio = '$idservicio',
        fecha_inicio = '$fecha_inicio',
        fecha_termino = '$fecha_termino',
        costo_total = '$monto_pago'
        WHERE id_r_servicio='$id_r_servicio'";
        return ejecutarConsulta($sql);
    }

    //funcion para mostrar los datos del servicio seleccionado 
    public function mostrar ($id_r_servicio) 
    {
        $sql = "SELECT rs.idservicio, s.nombre as concepto, rs.fecha_inicio, rs.estado, p.nombre, p.direccion, p.telefono, p.num_documento, rs.idcliente, rs.id_r_servicio, s.costo, rs.fecha_termino
        FROM registro_servicio rs
        INNER JOIN persona p ON p.idpersona = rs.idcliente
        INNER JOIN servicio s ON s.idservicio = rs.idservicio
        WHERE rs.id_r_servicio = '$id_r_servicio'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function mostrardetalle ($id_r_servicio) 
    {
        $sql = "SELECT rs.idservicio, s.nombre as concepto, rs.fecha_inicio, rs.estado, p.nombre, p.direccion, p.telefono, p.num_documento, rs.idcliente, rs.id_r_servicio, s.costo
        FROM registro_servicio rs
        INNER JOIN persona p ON p.idpersona = rs.idcliente
        INNER JOIN servicio s ON s.idservicio = rs.idservicio
        WHERE rs.id_r_servicio = '$id_r_servicio'";
        return ejecutarConsulta($sql);
    }


    //funcion para listar los servicios al inicio
    public function listar () 
    {
        $sql = "SELECT rs.id_r_servicio,rs.idservicio, s.nombre as concepto, rs.fecha_inicio, rs.fecha_termino, rs.estado, p.nombre as cliente, p.direccion, p.telefono, p.num_documento, rs.costo_total AS costo, rs.serie, rs.correlativo
        FROM registro_servicio rs
        INNER JOIN persona p ON p.idpersona = rs.idcliente
        INNER JOIN servicio s ON s.idservicio = rs.idservicio";
        return ejecutarConsulta($sql);
    }

    public function listarContratosActivos () 
    {
        $sql = "SELECT rs.id_r_servicio,rs.idservicio, s.nombre as concepto, rs.fecha_inicio, rs.fecha_termino, rs.estado, p.nombre as cliente, p.direccion, p.telefono, p.num_documento, rs.costo_total AS costo, rs.serie, rs.correlativo
        FROM registro_servicio rs
        INNER JOIN persona p ON p.idpersona = rs.idcliente
        INNER JOIN servicio s ON s.idservicio = rs.idservicio
        WHERE rs.estado = '1'";
        return ejecutarConsulta($sql);
    }

    //funcion para listar los contratos del cliente seleccionado
    public function listarContratos () 
    {
        $cliente=$_REQUEST["idcliente"];
        $sql = "SELECT rs.id_r_servicio,rs.idservicio, s.nombre as concepto, rs.fecha_inicio, rs.estado, p.nombre as cliente, p.direccion, p.telefono, p.num_documento, rs.idcliente, s.costo
        FROM registro_servicio rs
        INNER JOIN persona p ON p.idpersona = rs.idcliente
        INNER JOIN servicio s ON s.idservicio = rs.idservicio
        WHERE rs.idcliente = '$cliente'";
        return ejecutarConsulta($sql);
    }


    //funcion para desactivar el servicio
    public function desactivar($id_r_servicio)
    {
      $sql="UPDATE registro_servicio SET estado='0' where id_r_servicio='$id_r_servicio'";
      return ejecutarConsulta($sql);
    }


    //funcion para activar el servicio
    public function activar($id_r_servicio)
    {
      $sql="UPDATE registro_servicio SET estado='1' where id_r_servicio='$id_r_servicio'";
      return ejecutarConsulta($sql);
    }

    public function listarServicio()
    {
      $sql="SELECT * FROM servicio";
      return ejecutarConsulta($sql);

    }
    

    public function mostrarServicio ($idservicio) {
        $sql = "SELECT idservicio, nombre, costo, costo_dia, costo_dia_31, costo_dia_28, costo_dia_29 FROM servicio WHERE idservicio = '$idservicio'";
        return ejecutarConsultaSimpleFila($sql);
    }


    //funcion para listar un servicio especifico con sus datos
    public function servicioCabecera($id_r_servicio)
    {
        $sql="SELECT rs.id_r_servicio, rs.idcliente, rs.idusuario, rs.idservicio,s.costo, s.nombre as concepto, rs.fecha_inicio, p.nombre as cliente, p.direccion, p.num_documento, p.telefono, u.nombre
        FROM registro_servicio rs
        INNER JOIN persona p ON p.idpersona = rs.idcliente
        INNER JOIN usuario u ON u.idusuario = rs.idusuario
        INNER JOIN servicio s ON s.idservicio = rs.idservicio
        WHERE rs.id_r_servicio = '$id_r_servicio'";
        return ejecutarConsulta($sql);
    }


    //funcion para listar solo los servicios activos
    public function servicioMensual(){
         $sql="SELECT rs.id_r_servicio, rs.idcliente, rs.idusuario, rs.idservicio,s.costo, s.nombre as concepto, rs.fecha_inicio, p.nombre as cliente, p.direccion, p.num_documento, p.telefono, u.nombre as usuario
         FROM registro_servicio rs
         INNER JOIN persona p ON p.idpersona = rs.idcliente
         INNER JOIN usuario u ON u.idusuario = rs.idusuario
         INNER JOIN servicio s ON s.idservicio = rs.idservicio
         WHERE rs.estado='1'";
        return ejecutarConsulta($sql);
      }
  
}

?>