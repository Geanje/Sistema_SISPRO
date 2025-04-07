<?php
require_once '../config/Conexion.php';

class RegServicio {
    public function __construct()
    {
        
    }

    public function insertar($nombre, $costo, $costo_dia, $costo_dia_31, $costo_dia_28, $costo_dia_29) {
        $sql = "INSERT INTO servicio (nombre, costo, costo_dia, costo_dia_31, costo_dia_28, costo_dia_29) VALUES ('$nombre','$costo', '$costo_dia', '$costo_dia_31', '$costo_dia_28', '$costo_dia_29')";
        return ejecutarConsulta($sql);
    }

    public function editar($idservicio,$nombre,$costo, $costo_dia, $costo_dia_31, $costo_dia_28, $costo_dia_29) {
        $sql = "UPDATE servicio SET 
        idservicio = '$idservicio',
        nombre = '$nombre',
        costo = '$costo',
        costo_dia = '$costo_dia',
        costo_dia_31 = '$costo_dia_31',
        costo_dia_28 = '$costo_dia_28',
        costo_dia_29 = '$costo_dia_29'
        WHERE idservicio = '$idservicio'";
        return ejecutarConsulta($sql);
    }
    public function mostrar ($idservicio) {
        $sql = "SELECT idservicio, nombre, costo, costo_dia, costo_dia_31, costo_dia_28, costo_dia_29 FROM servicio WHERE idservicio = '$idservicio'";
        return ejecutarConsultaSimpleFila($sql);
    }
    public function listar() {
        $sql = "SELECT idservicio, nombre, costo, costo_dia, costo_dia_31, costo_dia_28, costo_dia_29 FROM servicio";
        return ejecutarConsulta($sql);
    }
}
?>