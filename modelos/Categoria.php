<?php
//Incluimos conexion a la base de trader_cdlrisefall3methods
require "../config/Conexion.php";

Class Categoria
{
  //Implementando nuestro constructor
  public function __construct()
  {


  }
  //Implementamos metodo para insertar registro
    public function insertar($nombre,$descripcion)
    {
      $sql="INSERT INTO categoria (nombre,descripcion,condicion)
      VALUES ('$nombre','$descripcion','1')";
      return ejecutarConsulta($sql);
   }
    //Implementamos un metodo para editar registro
    public function editar($idcategoria, $nombre,$descripcion)
    {
      $sql="UPDATE categoria SET nombre='$nombre', descripcion='$descripcion'
      where idcategoria='$idcategoria'";
      return ejecutarConsulta($sql);
    }

    //Implementamos un metodo para eliminar registro
    public function desactivar($idcategoria)
    {
      $sql="UPDATE categoria SET condicion='0'
      where idcategoria='$idcategoria'";
      return ejecutarConsulta($sql);
    }

    //Implementamos un metodo para eliminar registro
    public function activar($idcategoria)
    {
      $sql="UPDATE categoria SET condicion='1'
      where idcategoria='$idcategoria'";
      return ejecutarConsulta($sql);
    }

    //Implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idcategoria)
    {
      $sql="SELECT * FROM categoria where idcategoria='$idcategoria'";
      return ejecutarConsultaSimpleFila($sql);

    }

    //Implementar metodo para listar los registros
    public function listar()
    {
      $sql="SELECT * FROM categoria";
      return ejecutarConsulta($sql);

    }


    //Implementar metodo para listar los registros y mostrar en el select
    public function select()
    {
      $sql="SELECT * FROM categoria where condicion=1";
      return ejecutarConsulta($sql);

    }




  }







 ?>
