<?php

class Conexion{

public function conectar(){

    $link = new PDO("mysql:host=solucionesintegralesjb.com;dbname=soluciones_prajfactura",
                    "soluciones_prajadmin",
                    ".~oPlwgQgy{Y",
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                          PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
                    );

    return $link;

}

}