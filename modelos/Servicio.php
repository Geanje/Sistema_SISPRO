<?php

require_once "../config/Conexion.php";


Class Servicio {
    public function __construct()
    {
        
    }

    public function insertar ($nombre,$costo) 
    {
        $sql = "INSERT INTO servicio (nombre,costo) VALUES ('$nombre','$costo')";
        return ejecutarConsulta($sql);
    }

    public function editar ($idservicio,$nombre,$costo)
    {
        $sql = "UPDATE servicio SET 
        idservicio = '$idservicio',
        nombre = '$nombre',
        costo = '$costo'
        WHERE idservicio = '$idservicio'";
        return ejecutarConsulta($sql);
    }

    public function listar ()
    {
        $sql = "SELECT nombre, costo FROM servicio";
        return ejecutarConsulta($sql);
    }

    public function mostrar ($idservicio) 
    {
        $sql = "SELECT nombre, costo, idservicio FROM servicio WHERE idservicio = '$idservicio'";
        return ejecutarConsulta($sql);
    }
}

?>