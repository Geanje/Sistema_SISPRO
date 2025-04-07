<?php

	require_once "../config/Conexion.php";

	class PrestamoProductos{

		/*=============================================
		=        PRODUCTOS PRESTADOS            
		=============================================*/
	    public function listaPrestamoProductos($fecha_inicio,$fecha_fin,$idsucursal){

	    	if($idsucursal == "todos"){
    			$sql="SELECT DATE(v.fecha_hora) as fecha,u.nombre as usuario, p.nombre as sucursal,dv.cantidad,a.nombre as articulo,v.serie,v.correlativo,v.total_venta,v.estado from venta v inner join persona p on v.idcliente=p.idpersona inner join usuario u on v.idusuario=u.idusuario INNER JOIN detalle_venta dv ON dv.idventa=v.idventa INNER JOIN articulo a ON a.idarticulo=dv.idarticulo where date(v.fecha_hora)>='$fecha_inicio' and date(v.fecha_hora)<='$fecha_fin' AND v.codigotipo_comprobante = 13";

	    	}else{

   	 			$sql="SELECT DATE(v.fecha_hora) as fecha,u.nombre as usuario, p.nombre as sucursal,dv.cantidad,a.nombre as articulo,v.serie,v.correlativo,v.total_venta,v.estado from venta v inner join persona p on v.idcliente=p.idpersona inner join usuario u on v.idusuario=u.idusuario INNER JOIN detalle_venta dv ON dv.idventa=v.idventa INNER JOIN articulo a ON a.idarticulo=dv.idarticulo where v.idcliente='$idsucursal' and date(v.fecha_hora)>='$fecha_inicio' and date(v.fecha_hora)<='$fecha_fin' AND v.codigotipo_comprobante = 13";
	    	}


		      return ejecutarConsulta($sql);

	    }
	    public function cantProductosPrestadosxSucursal($fecha_inicio,$fecha_fin,$idsucursal){
	    	if($idsucursal == "todos"){
		        $sql="SELECT IFNULL(sum(dv.cantidad),0) AS canttotalsucursal,p.nombre as sucursal FROM venta v inner join detalle_venta dv on v.idventa=dv.idventa inner join persona p on v.idcliente=p.idpersona where date(v.fecha_hora)>='$fecha_inicio' and date(v.fecha_hora)<='$fecha_fin' AND v.codigotipo_comprobante = '13' ";

	    	}else{

		        $sql="SELECT IFNULL(sum(dv.cantidad),0) AS canttotalsucursal,p.nombre as sucursal FROM venta v inner join detalle_venta dv on v.idventa=dv.idventa inner join persona p on v.idcliente=p.idpersona where v.idcliente='$idsucursal' and date(v.fecha_hora)>='$fecha_inicio' and date(v.fecha_hora)<='$fecha_fin' AND v.codigotipo_comprobante = '13' ";
		      	
	    	}

	      	return ejecutarConsultaSimpleFila($sql);

	    }
	}	