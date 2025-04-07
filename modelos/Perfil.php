<?php 
	require_once "../config/Conexion.php";

Class Perfil{


	public function __construct(){

	}

	public function cabecera_perfil(){
		$sql="SELECT * FROM perfil";
		return ejecutarConsulta($sql);
	}


}

 ?>
