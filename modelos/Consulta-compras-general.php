<?php
    require_once "../config/Conexion.php";
    
    class ConsultaCompraxSerie{

        public function getComprasxSerie($fecha_inicio,$fecha_fin,$producto,$serie){

            $sql="SELECT i.idingreso,DATE(i.fecha_hora) as fecha,i.idproveedor,p.nombre as proveedor,u.idusuario,u.nombre as usuario,i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra, a.nombre as articulo,di.cantidad,di.precio_venta,di.serie as serieArticulo 
            FROM ingreso i 
            INNER JOIN persona p ON i.idproveedor=p.idpersona 
            INNER JOIN usuario u ON i.idusuario=u.idusuario 
            inner join detalle_ingreso di ON di.idingreso=i.idingreso 
            inner join articulo a ON a.idarticulo= di.idarticulo 
            WHERE DATE(i.fecha_hora)>= '$fecha_inicio' and DATE(i.fecha_hora) <= '$fecha_fin' 
            and di.serie like '%$serie%' 
            and a.nombre like '%$producto%' 
            order by i.idingreso DESC";

          

            return ejecutarConsulta($sql);
        }
    }

?>