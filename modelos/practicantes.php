<?php
//Incluimos conexion a la base de trader_cdlrisefall3methods
require "../config/Conexion.php";

class Practicantes
{
  //Implementando nuestro constructor
  public function __construct()
  {
  }
  //Implementamos metodo para insertar registro
  public function insertar($nombres_apellidos,  $institucion, $dni, $sede, $especialidad, $modalidad, $correo, $numero, $fecha_inicio, $fecha_termino, $estado, $grupo, $tarea)
  {
    $query = "SELECT * FROM practicantes where dni = '$dni'";
    $resultado = ejecutarConsultaSimpleFila($query);
    
    if ($resultado) {
      return 2; // DNI already exists
    } else {
      $sql = "INSERT INTO practicantes (nombres_apellidos,institucion,dni,sede,especialidad,modalidad,correo,numero,fecha_inicio,fecha_termino,estado,grupo,tarea)
      VALUES ('$nombres_apellidos','$institucion','$dni','$sede','$especialidad','$modalidad','$correo','$numero','$fecha_inicio','$fecha_termino','$estado','$grupo','$tarea')";
      return ejecutarConsulta($sql);
    }
  }
  //Implementamos un metodo para editar registro
  public function editar($idpracticante, $nombres_apellidos, $institucion,  $dni, $sede, $especialidad, $modalidad, $correo, $numero, $fecha_inicio, $fecha_termino, $estado, $grupo, $tarea)
  {
    $sql = "UPDATE 
              practicantes 
            SET
              nombres_apellidos='$nombres_apellidos',              
              institucion='$institucion',
              dni='$dni',
              sede='$sede',
              especialidad='$especialidad',
              modalidad='$modalidad',
              correo='$correo',
              numero='$numero',
              fecha_inicio='$fecha_inicio',
              fecha_termino='$fecha_termino',
              estado='$estado',
              grupo= '$grupo',
              tarea= '$tarea'
            where 
                  idpracticante='$idpracticante'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un metodo para eliminar registro
  public function eliminar($idpracticante)
  {
    $sql = "DELETE FROM practicantes
      where idpracticante='$idpracticante'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un metodo para mostrar los datos de un registro a modificar
  public function mostrar($idpracticante)
  {
    $sql = "SELECT * FROM practicantes where idpracticante='$idpracticante'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function listar()
  {
    $sql = "SELECT * FROM practicantes";
    return ejecutarConsulta($sql);
  }

  public function listarsucursal()
  {
    $sql = "SELECT * FROM practicantes";
    return ejecutarConsulta($sql);
  }
  
  public function listarByDNI($dni)
  {
  $sql = "SELECT * FROM practicantes WHERE dni='$dni'";
  return ejecutarConsulta($sql);
  }
}