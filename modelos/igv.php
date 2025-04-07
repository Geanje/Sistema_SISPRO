<?php

require_once "../config/Conexion.php";

class Igv{
public function insertarIgv($porcentaje){
    $sql="UPDATE `igv` SET `porcentaje`='$porcentaje' WHERE idIGV='1'";
    return ejecutarConsulta($sql);
}
public function mostrarIgv(){
    $sql="SELECT * FROM `igv` WHERE idIGV='1'";
    return ejecutarConsulta($sql);
}
}


?>