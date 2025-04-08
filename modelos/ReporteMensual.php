<?php 
require_once('../config/Conexion.php');

Class ReporteMensual{

	public function __construct(){

	}

	public function listar($mes,$anno)
	{
		$sql="SELECT new.summaArticulos,new.fecha_mas_vendido, new.idarticulo,a.codigo,a.nombre,a.imagen FROM (SELECT sum(f.cantidad) as summaArticulos,f.idarticulo,f.fecha_mas_vendido  from (SELECT * FROM detalle_venta dv WHERE MONTH(dv.fecha_mas_vendido) = $mes and YEAR(dv.fecha_mas_vendido) = $anno) as f group by f.idarticulo order by sum(f.cantidad) DESC ) as new INNER JOIN articulo a ON a.idarticulo=new.idarticulo order by summaArticulos DESC";
		return ejecutarConsulta($sql);
	}
}

 ?>