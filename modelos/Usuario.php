<?php
//Incluimos conexion a la base de trader_cdlrisefall3methods
require "../config/Conexion.php";
date_default_timezone_set('America/Lima');
class Usuario
{
  //Implementando nuestro constructor
  public function __construct()
  {
  }
  //Implementamos metodo para insertar registro
  public function insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen, $permisos)
  {
    $sql = "INSERT INTO usuario (nombre,tipo_documento,num_documento,direccion,telefono,email,cargo,login,clave,imagen,condicion)
      VALUES ('$nombre','$tipo_documento','$num_documento','$direccion','$telefono','$email','$cargo','$login','$clave','$imagen','1')";
    //return ejecutarConsulta($sql);
    $idusuarionew = ejecutarConsulta_retornarID($sql);
    $num_elementos = 0;
    $sw = true;

    while ($num_elementos < count($permisos)) {
      $sql_detalle = "INSERT INTO usuario_permiso(idusuario,idpermiso) VALUES('$idusuarionew','$permisos[$num_elementos]')";
      ejecutarConsulta($sql_detalle) or $sw = false;
      $num_elementos = $num_elementos + 1;
    }
    return $sw;
  }
  //Implementamos un metodo para editar registro
  public function editar($idusuario, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen, $permisos)
  {
    $sql = "UPDATE usuario SET nombre='$nombre',tipo_documento='$tipo_documento',num_documento='$num_documento',direccion='$direccion',telefono='$telefono',email='$email',cargo='$cargo',login='$login',clave='$clave',imagen='$imagen'
      where idusuario='$idusuario'";
    ejecutarConsulta($sql);
    //Eliminamos todos los permisos asignados para volverlos a registrar
    $sqldel = "DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";
    ejecutarConsulta($sqldel);

    $num_elementos = 0;
    $sw = true;

    while ($num_elementos < count($permisos)) {
      $sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso) VALUES('$idusuario', '$permisos[$num_elementos]')";
      ejecutarConsulta($sql_detalle) or $sw = false;
      $num_elementos = $num_elementos + 1;
    }

    return $sw;
  }

  //Implementamos un metodo para eliminar registro
  public function desactivar($idusuario)
  {
    $sql = "UPDATE usuario SET condicion='0'
      where idusuario='$idusuario'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un metodo para eliminar registro
  public function activar($idusuario)
  {
    $sql = "UPDATE usuario SET condicion='1'
      where idusuario='$idusuario'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un metodo para mostrar los datos de un registro a modificar
  public function mostrar($idusuario)
  {
    $sql = "SELECT * FROM usuario where idusuario='$idusuario'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar metodo para listar los registros
  public function listar()
  {
    $sql = "SELECT * FROM usuario";
    return ejecutarConsulta($sql);
  }

  //implementar un metodo para listar los permisos marcados
  public function listarmarcados($idusuario)
  {
    $sql = "SELECT * FROM usuario_permiso where idusuario='$idusuario'";
    return ejecutarConsulta($sql);
  }

  //Funcion para verificar el acceso al sistema
  public function verificar($login, $clave)
  {
    $sql = "SELECT idusuario, nombre, tipo_documento,num_documento,telefono, email, cargo, imagen, login, tiempo_bloqueo
      FROM usuario where login='$login' and clave='$clave' and condicion='1'";
    return ejecutarConsulta($sql);
  }

  public function buscarUsuario($logina)
  {
    $sql = "SELECT tiempo_bloqueo,condicion FROM usuario WHERE login = '$logina'";
    return ejecutarConsulta($sql);
  }

  public function resetIntentosFallidos($login)
  {

    $sql = "UPDATE usuario SET intentos_fallidos = 0 WHERE login = '$login'";
    return ejecutarConsulta($sql);
  }

  public function incrementarIntentosFallidos($login)
  {

    $sql = "UPDATE usuario SET intentos_fallidos = intentos_fallidos + 1 WHERE login = '$login'";
    return ejecutarConsulta($sql);
  }

  public function desact_usuario($login)
  {
    $sql = "UPDATE usuario SET condicion = 0 where login = '$login'";
    return ejecutarConsulta($sql);
  }

  

  public function excedeIntentosFallidos($login)
  {
    $sql = "SELECT intentos_fallidos FROM usuario WHERE login = '$login'";
    $resultado = ejecutarConsulta($sql);

    if ($resultado) {
      $row = mysqli_fetch_assoc($resultado);
      return $row['intentos_fallidos'] >= 3;
    } else {
      // Manejar error
      return false;
    }
  }


  public function desactivarusuario($login)
  {
    $sql = "SELECT intentos_fallidos FROM usuario WHERE login = '$login'";
    $desc = ejecutarConsulta($sql);

    if ($desc) {
      $row = mysqli_fetch_assoc($desc);
      return $row['intentos_fallidos'] >= 9;
    } else {
      return false;
    }
  }


  public function bloquearUsuario($login)
  {
    $tiempo_bloqueo = date('Y-m-d H:i:s', strtotime('+5 minutes'));
    $sql = "UPDATE usuario SET tiempo_bloqueo = '$tiempo_bloqueo' WHERE login = '$login'";
    return ejecutarConsulta($sql);
  }

}
