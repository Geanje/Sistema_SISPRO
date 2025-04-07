<?php
//Incluimos conexion a la base de trader_cdlrisefall3methods
require "../config/Conexion.php";

class Pedido {

    public function listar() {
        $sql = "SELECT  DATE(v.fecha_hora) AS fecha,
                        p.nombre AS cliente,
                        u.nombre AS usuario,
                        tc.descripcion_tipo_comprobante AS documento,
                        v.serie,
                        v.total_venta,
                        v.estado,
                        v.idventa,
                        v.idcliente,
                        u.idusuario,
                        CONCAT(v.serie,'-',v.correlativo) as numero
                FROM venta v INNER JOIN persona p 
                ON v.idcliente=p.idpersona INNER JOIN usuario u 
                ON v.idusuario=u.idusuario INNER JOIN tipo_comprobante tc 
                ON v.codigotipo_comprobante=tc.codigotipo_comprobante 
                WHERE v.codigotipo_comprobante = 100
                ORDER BY v.idventa DESC";  
        return ejecutarConsulta($sql);
    }

    public function mostrar($idventa) {
        $sql = "SELECT  DATE(v.fecha_hora) AS fecha,
                        p.nombre AS cliente,
                        u.nombre AS usuario,
                        tc.descripcion_tipo_comprobante AS documento,
                        CONCAT(v.serie,'-',v.correlativo) as numero,
                        v.total_venta,
                        v.estado,
                        v.idventa,
                        v.idcliente
                FROM venta v INNER JOIN persona p 
                ON v.idcliente=p.idpersona INNER JOIN usuario u 
                ON v.idusuario=u.idusuario INNER JOIN tipo_comprobante tc 
                ON v.codigotipo_comprobante=tc.codigotipo_comprobante 
                WHERE 
                    v.codigotipo_comprobante = 100 AND
                    v.idventa = $idventa
                ORDER BY v.idventa DESC"; 
        return ejecutarConsultaSimpleFila($sql);
    }

}

?>