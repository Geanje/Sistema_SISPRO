<?php 
require_once('../config/Conexion.php');

Class Rentabilidad{

	public function __construct(){

	}

	public function listarRentabilidadCompras($mes,$anno,$porcentaje){
		$sqlCompras="SELECT IFNULL(sum(i.total_compra),0) as compras from ingreso as i WHERE MONTH(i.fecha_hora) = '$mes' and YEAR(i.fecha_hora) = '$anno'";
		return ejecutarConsultaSimpleFila($sqlCompras);
	}
	public function listarRentabilidadVentas($mes,$anno,$porcentaje){
		$sqlVentas="SELECT IFNULL(sum(v.total_venta),0) as ventas, IFNULL(sum(v.total_venta),0)*$porcentaje/100 as porcentaje from venta as v WHERE MONTH(v.fecha_hora) = '$mes' and YEAR(v.fecha_hora) = '$anno' and v.codigotipo_comprobante in (1,3) ";
		return ejecutarConsultaSimpleFila($sqlVentas);
	}
}

 ?>