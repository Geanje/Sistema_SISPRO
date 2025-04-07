<?php
require_once "../config/Conexion.php";

class ConsultaVentaxSerie
{

    public function getVentasxSerie($fecha_inicio, $fecha_fin, $producto, $serie)
    {

        $sql = "SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,v.codigotipo_comprobante,tc.descripcion_tipo_comprobante,v.serie,v.correlativo,v.total_venta,v.idmoneda,m.descripcion, a.nombre as articulo,dv.cantidad,dv.precio_venta,dv.serie as serieArticulo FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario INNER JOIN tipo_comprobante tc ON v.codigotipo_comprobante=tc.codigotipo_comprobante INNER JOIN moneda m ON v.idmoneda=m.idmoneda inner join detalle_venta dv ON dv.idventa=v.idventa inner join articulo a ON a.idarticulo= dv.idarticulo WHERE DATE(v.fecha_hora)>= '$fecha_inicio' and DATE(v.fecha_hora) <= '$fecha_fin' and dv.serie like '%$serie%' and a.nombre like '%$producto%' and tc.codigotipo_comprobante in (1,3) order by v.idventa DESC";



        return ejecutarConsulta($sql);
    }
}
