<?php
//Incluimos conexion a la base de trader_cdlrisefall3methods
require "../config/Conexion.php";

Class Persona
{
  //Implementando nuestro constructor
  public function __construct()
  {


  }
  //Implementamos metodo para insertar registro
    public function insertar($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$razon_social,$marca,$placa,$licencia_conducir)
    {
    $query = "SELECT * FROM persona where tipo_documento = '$tipo_documento' and num_documento = '$num_documento'";
		$resultado = ejecutarConsultaSimpleFila($query);
		// --
		if ($resultado) { 
      return 2; // -- 2 Para saber que ya existe
		} else {
      $sql="INSERT INTO persona (tipo_persona, nombre, tipo_documento,num_documento,direccion,telefono,email,razon_social,marca,placa,licencia_conducir)
      VALUES ('$tipo_persona','$nombre','$tipo_documento','$num_documento','$direccion','$telefono','$email','$razon_social','$marca','$placa','$licencia_conducir')";
      return ejecutarConsulta($sql);
   }
        
    }
    //Implementamos un metodo para editar registro
    public function editar($idpersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$razon_social,$marca,$placa,$licencia_conducir)
    {
      $sql="UPDATE 
              persona 
            SET 
              tipo_persona='$tipo_persona',
              nombre='$nombre',
              tipo_documento='$tipo_documento',
              num_documento='$num_documento',
              direccion='$direccion',
              telefono='$telefono',
              email='$email',
              razon_social='$razon_social',
              marca='$marca',
              placa='$placa',
              licencia_conducir='$licencia_conducir'
            where 
              idpersona='$idpersona'";
      return ejecutarConsulta($sql);
    }

    //Implementamos un metodo para eliminar registro
    public function eliminar($idpersona)
    {
      $sql="DELETE FROM persona
      where idpersona='$idpersona'";
      return ejecutarConsulta($sql);
    }

      //Implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idpersona)
    {
      $sql="SELECT * FROM persona where idpersona='$idpersona'";
      return ejecutarConsultaSimpleFila($sql);

    }

    //Implementar metodo para listar los registros Clientes
    public function listarc()
    {
      $sql="SELECT * FROM persona where tipo_persona='Cliente' ORDER BY razon_social ASC";
      return ejecutarConsulta($sql);

    }
    public function listarcs()
    {
      $sql="SELECT * FROM persona where tipo_persona in ('Cliente','Cliente Servicio') ORDER BY razon_social ASC";
      return ejecutarConsulta($sql);

    }
    //Implementar metodo para listar los registros proveedor
    public function listarp()
    {
      $sql="SELECT * FROM persona where tipo_persona='Proveedor' ORDER BY nombre";
      return ejecutarConsulta($sql);

    }

    //Implementar metodo para listar los registros Transportista 
    public function listart()
    {
      $sql="SELECT * FROM persona where tipo_persona='Transportista' ORDER BY nombre";
      return ejecutarConsulta($sql);

    }


    //Implementar metodo para listar los registros
    public function listarsucursal()
    {
      $sql="SELECT * FROM persona where tipo_persona='Sucursal'";
      return ejecutarConsulta($sql);

    }
    

  }



 ?>
