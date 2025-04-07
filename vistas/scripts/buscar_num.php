<?php 
	require_once "../../config/Conexion.php";
	// $msjOK=false;
	// $msjR="No se encontro";
	$sql="SELECT max(correlativo) as corre FROM venta where codigotipo_comprobante='1' and estado='Aceptado' order by correlativo desc";
	$rpta=ejecutarConsulta($sql);
	// $num=$rpta->num_rows();
	$rp=$rpta->fetch_array();
	$rp=$rp['corre'];
	// $msjOK=true;

	// if($rp['corre']>0){
	// 	$msjOK=true;
	// 	// $numero=$rpta->fetch_array();
	// 	$msjR=$numero['corre'];
	// }
	$msjR=$rp;

	// $salidaJSON=array('respuesta'=>$msjOK,'mensaje'=>$msjR);
	// echo json_encode($salidaJSON);
	$salidaJSON=array('mensaje'=>$msjR);
	echo json_encode($salidaJSON);

 ?>