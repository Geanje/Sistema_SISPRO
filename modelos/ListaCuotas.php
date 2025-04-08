<?php 
	require_once "../config/Conexion.php";

	Class ListaCuotas{
		public function __construct(){

		}

		public function listar(){
			$sql = "SELECT p.idpago,p.idingreso,p.valor_cuota,DATE(p.fecha_pago) as fecha_pago,p.estado,i.serie_comprobante,i.num_comprobante FROM pago p INNER JOIN ingreso i on i.idingreso=p.idingreso where p.estado = 'Pendiente'";
			return ejecutarConsulta($sql);
		}

		public function cancelarLetra(){
			$sql = "UPDATE FROM pago SET estado = 'Cancelado'";
			return ejecutarConsulta($sql);
		}
	}

