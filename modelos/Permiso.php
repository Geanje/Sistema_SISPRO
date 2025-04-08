<?php
//Incluimos conexion a la base de trader_cdlrisefall3methods
require "../config/Conexion.php";

Class Permiso
{
  //Implementando nuestro constructor
  public function __construct()
  {


  }






    //Implementar metodo para listar los registros
    public function listar()
    {
      $sql="SELECT * FROM permiso";
      return ejecutarConsulta($sql);

    }







  }







 ?>
