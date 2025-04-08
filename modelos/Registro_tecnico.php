<?php
require "../config/Conexion.php";

Class Tecnico{
    public function __construct()
    {

    }

    public function insertar($nombre,$telefono,$dni,$area,$cargo,$tipo_tecnico){
        $sql="INSERT INTO tecnico (nombre,telefono,dni,area,cargo,tipo_tecnico) VALUES ('$nombre','$telefono','$dni','$area','$cargo','$tipo_tecnico')";
        return ejecutarConsulta($sql);
    }

    public function editar($idtecnico,$nombre,$telefono,$dni,$area,$cargo,$tipo_tecnico)
    {
      $sql="UPDATE 
              tecnico 
            SET 
              nombre='$nombre',
              telefono='$telefono',
              dni='$dni',
              area='$area',
              cargo='$cargo',
              tipo_tecnico='$tipo_tecnico'
            where 
              idtecnico='$idtecnico'";
      return ejecutarConsulta($sql);
    }


    public function listarTecnico(){
        $sql="SELECT * FROM tecnico";
        return ejecutarConsulta($sql);
    }

    public function eliminar($idtecnico){
        $sql="DELETE FROM tecnico WHERE idtecnico='$idtecnico'";
        return ejecutarConsulta($sql);
    }

    public function mostrar($idtecnico)
    {
      $sql="SELECT * FROM tecnico where idtecnico='$idtecnico'";
      return ejecutarConsultaSimpleFila($sql);

    }

}

?>