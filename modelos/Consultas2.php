<?php
//Incluimos conexion a la base de trader_cdlrisefall3methods
require "../config/Conexion.php";

class Consultas
{
  //Implementando nuestro constructor
  public function __construct()
  {
  }




  public function totalcomprahoy()
  {
    $sql = "SELECT IFNULL(SUM(total_compra),0) as total_compra FROM ingreso where date(fecha_hora)=curdate()";
    return ejecutarConsulta($sql);
  }

  public function totalventahoy()
  {
    $sql = "SELECT IFNULL(SUM(total_venta),0) as total_venta FROM venta where estado!='Cotizado' and date(fecha_hora)=curdate()";
    return ejecutarConsulta($sql);
  }
  public function comprasultimos_10dias()
  {
    $sql = "SELECT CONCAT(DAY(fecha_hora),'-',MONTH(fecha_hora)) as fecha,SUM(total_compra) as total FROM ingreso GROUP BY fecha_hora ORDER BY fecha_hora DESC limit 0,10";
    return ejecutarConsulta($sql);
  }


  public function ventasultimos_12meses()
  {
    $sql = "SELECT DATE_FORMAT(fecha_hora,'%M') as fecha, SUM(total_venta) as total from venta where estado!='Cotizado' group by MONTH(fecha_hora) ORDER BY fecha_hora DESC limit 0,12";
    return ejecutarConsulta($sql);
  }

  public function kardex()
  {
    $sql = "SELECT a.codigo,a.nombre,c.nombre as categoria,a.stock_ingreso,a.stock_salida,a.stock FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria where a.condicion='1' ";
    return ejecutarConsulta($sql);
  }
  /*funcion para consultar articulos con precio -relacion con detalle-ingreso*/
  public function kardexIngreso()
  {
    // $sql="SELECT a.codigo,a.nombre,c.nombre as categoria,a.stock_ingreso,a.stock_salida,a.stock,di.precio_compra,di.precio_venta FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria INNER JOIN detalle_ingreso di ON di.idarticulo = a.idarticulo where a.condicion='1' ";
    $sql = "SELECT a.codigo,a.nombre,c.nombre as categoria,a.stock_ingreso,a.stock_salida,a.stock,di.precio_compra,di.precio_venta FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria INNER JOIN (SELECT ddi.idarticulo,count(ddi.idarticulo) as cantidadIngresos, ddi.precio_compra,ddi.precio_venta FROM detalle_ingreso ddi  group by ddi.idarticulo order by ddi.idarticulo desc) di ON di.idarticulo = a.idarticulo where a.condicion='1' order by di.idarticulo desc";
    return ejecutarConsulta($sql);
  }

  public function reiniciarkardex()
  {
    $sql = "UPDATE articulo SET stock_ingreso='0', stock_salida='0' WHERE condicion='1' ";
    return ejecutarConsulta($sql);
  }

  public function consultaCotizaciones($fecha_inicio, $fecha_fin)
  {
    $sql = "SELECT DATE(v.fecha_hora) as fecha,u.nombre as usuario, p.nombre as cliente, v.codigotipo_comprobante,tc.descripcion_tipo_comprobante,v.serie,v.correlativo,v.total_venta,v.impuesto,v.estado from venta v inner join persona p on v.idcliente=p.idpersona inner join usuario u on v.idusuario=u.idusuario INNER JOIN codigotipo_comprobante tc ON tc.codigotipo_comprobante=v.codigotipo_comprobante where date(v.fecha_hora)>='$fecha_inicio' and date(v.fecha_hora)<='$fecha_fin' and v.codigotipo_comprobante='10' and v.estado!='Aceptado' and v.estado!='Anulado'";
    return ejecutarConsulta($sql);
  }

  public function ventasfechausuario($fecha_inicio, $fecha_fin, $idusuario, $modulo)
  {
    $tables = [
      0 => ['venta', 'factura'],
      1 => ['venta'],
      2 => ['factura'],
    ];
    $comprobantes = [
      'venta' => [1, 3, 12],
      'factura' => [1, 3],
    ];
    $sql = "";
    foreach ($tables[$modulo] as $table) {
      $query = "SELECT DATE_FORMAT({$table}.fecha_hora,'%d-%m-%Y %h:%i %p') as fecha, u.nombre as usuario, tp.descripcion_tipo_pago, p.nombre as cliente, p.num_documento as num_doc, tc.descripcion_tipo_comprobante, {$table}.serie, {$table}.correlativo, {$table}.total_venta, {$table}.impuesto, {$table}.estado
              FROM {$table}
              LEFT JOIN persona p ON {$table}.idcliente=p.idpersona
              LEFT JOIN usuario u ON {$table}.idusuario=u.idusuario
              LEFT JOIN tipo_comprobante tc ON tc.codigotipo_comprobante={$table}.codigotipo_comprobante
              LEFT JOIN tipo_pago tp ON tp.codigotipo_pago={$table}.codigotipo_pago
              WHERE
              DATE({$table}.fecha_hora) >= '$fecha_inicio'
              AND DATE({$table}.fecha_hora) <= '$fecha_fin'
              AND {$table}.codigotipo_comprobante IN (" . implode(',', $comprobantes[$table]) . ")";
      if ($idusuario != "all") {
        $query .= " AND {$table}.idusuario='$idusuario'";
      }
      $sql .= ($sql != "" ? " UNION " : "") . $query;
    }
    return ejecutarConsulta($sql);
  }

  public function FiltradoFactura($fecha_inicio, $fecha_fin, $idusuario)
  {
    if ($idusuario == "all") {
      $sql = "SELECT DATE(f.fecha_hora) as fecha,u.nombre as usuario, p.nombre as cliente, p.num_documento as num_doc,f.codigotipo_comprobante,tc.descripcion_tipo_comprobante,f.serie,f.correlativo,f.total_venta,f.impuesto,f.estado from factura f inner join persona p on f.idcliente=p.idpersona inner join usuario u on f.idusuario=u.idusuario INNER JOIN codigotipo_comprobante tc ON tc.codigotipo_comprobante=f.codigotipo_comprobante where date(f.fecha_hora)>='$fecha_inicio' and date(f.fecha_hora)<='$fecha_fin'";
    } else {
      $sql = "SELECT DATE(f.fecha_hora) as fecha,u.nombre as usuario, p.nombre as cliente, p.num_documento as num_doc,f.codigotipo_comprobante,tc.descripcion_tipo_comprobante,f.serie,f.correlativo,f.total_venta,f.impuesto,f.estado from factura f inner join persona p on f.idcliente=p.idpersona inner join usuario u on f.idusuario=u.idusuario INNER JOIN codigotipo_comprobante tc ON tc.codigotipo_comprobante=f.codigotipo_comprobante where f.idusuario='$idusuario' and date(f.fecha_hora)>='$fecha_inicio' and date(f.fecha_hora)<='$fecha_fin' AND f.codigotipo_comprobante in (1,3)";
    }
    return ejecutarConsulta($sql);
  }

  public function FiltradoVenta($fecha_inicio, $fecha_fin, $idusuario)
  {
    if ($idusuario == "all") {
      $sql = "SELECT DATE(v.fecha_hora) as fecha,u.nombre as usuario, p.nombre as cliente, p.num_documento as num_doc,v.codigotipo_comprobante,tc.descripcion_tipo_comprobante,v.serie,v.correlativo,v.total_venta,v.impuesto,v.estado from venta v inner join persona p on v.idcliente=p.idpersona inner join usuario u on v.idusuario=u.idusuario INNER JOIN codigotipo_comprobante tc ON tc.codigotipo_comprobante=v.codigotipo_comprobante where  date(v.fecha_hora)>='$fecha_inicio' and date(v.fecha_hora)<='$fecha_fin' AND v.codigotipo_comprobante in (1,3,12) and v.estado!='Cotizado' and v.estado!='Anulado' and v.estado!='AnuladoC'";
    } else {
      $sql = "SELECT DATE(v.fecha_hora) as fecha,u.nombre as usuario, p.nombre as cliente, p.num_documento as num_doc,v.codigotipo_comprobante,tc.descripcion_tipo_comprobante,v.serie,v.correlativo,v.total_venta,v.impuesto,v.estado from venta v inner join persona p on v.idcliente=p.idpersona inner join usuario u on v.idusuario=u.idusuario INNER JOIN codigotipo_comprobante tc ON tc.codigotipo_comprobante=v.codigotipo_comprobante where f.idusuario='$idusuario' and date(v.fecha_hora)>='$fecha_inicio' and date(v.fecha_hora)<='$fecha_fin' AND v.codigotipo_comprobante in (1,3,12) and v.estado!='Cotizado' and v.estado!='Anulado' and v.estado!='AnuladoC'";
    }
    return ejecutarConsulta($sql);
  }

  public function ventasfechausuarioNC($fecha_inicio, $fecha_fin, $idusuario)
  {
    // --
    if ($idusuario == "all") {
      $sql = "SELECT DATE(v.fecha_hora) as fecha,u.nombre as usuario, p.nombre as cliente, p.num_documento as num_doc,v.codigotipo_comprobante,tc.descripcion_tipo_comprobante,v.idmotivo_doc,v.serie,v.correlativo,v.total_venta,v.impuesto,v.estado,md.motivo,
        ( SELECT serie FROM venta where idventa=v.doc_relacionado) as serie_rela, 
        ( SELECT correlativo FROM venta where idventa=v.doc_relacionado) as correlativo_rela
        from venta v 
        inner join persona p on v.idcliente=p.idpersona 
        inner join usuario u on v.idusuario=u.idusuario 
        INNER JOIN tipo_comprobante tc ON tc.codigotipo_comprobante=v.codigotipo_comprobante 
        INNER JOIN motivo_documento md ON md.idmotivo_documento=v.idmotivo_doc 
        where  date(v.fecha_hora)>='$fecha_inicio' 
        and date(v.fecha_hora)<='$fecha_fin' 
        AND v.codigotipo_comprobante in (7)";
    } else {
      $sql = "SELECT DATE(v.fecha_hora) as fecha,u.nombre as usuario, p.nombre as cliente, p.num_documento as num_doc,v.codigotipo_comprobante,tc.descripcion_tipo_comprobante,v.idmotivo_doc,v.serie,v.correlativo,v.total_venta,v.impuesto,v.estado,md.motivo, 
        ( SELECT serie FROM venta where idventa=v.doc_relacionado) as serie_rela, 
        ( SELECT correlativo FROM venta where idventa=v.doc_relacionado) as correlativo_rela
        from venta v 
        inner join persona p on v.idcliente=p.idpersona 
        inner join usuario u on v.idusuario=u.idusuario 
        INNER JOIN tipo_comprobante tc ON tc.codigotipo_comprobante=v.codigotipo_comprobante 
        INNER JOIN motivo_documento md ON md.idmotivo_documento=v.idmotivo_doc 
        where v.idusuario='$idusuario' 
        and date(v.fecha_hora)>='$fecha_inicio' 
        and date(v.fecha_hora)<='$fecha_fin' 
        AND v.codigotipo_comprobante in (7)";
    }
    // $sql="SELECT DATE(v.fecha_hora) as fecha,u.nombre as usuario, p.nombre as cliente, v.codigotipo_comprobante,tc.descripcion_tipo_comprobante,v.serie,v.correlativo,v.total_venta,v.impuesto,v.estado from venta v inner join persona p on v.idcliente=p.idpersona inner join usuario u on v.idusuario=u.idusuario INNER JOIN codigotipo_comprobante tc ON tc.codigotipo_comprobante=v.codigotipo_comprobante where v.idusuario='$idusuario' AND v.codigotipo_comprobante in (1,3) and (v.estado!='Cotizado' or v.estado!='AnuladoC')";

    // --
    return ejecutarConsulta($sql);
  }

  public function sumventasfechausuario($fecha_inicio, $fecha_fin, $idusuario, $modulo)
  {
    $tables = [
      0 => ['venta', 'factura'],
      1 => ['venta'],
      2 => ['factura'],
    ];
    $comprobantes = [
      'venta' => [1, 3, 12],
      'factura' => [1, 3],
    ];
    $sql = "";
    foreach ($tables[$modulo] as $table) {
      $query = "SELECT {$table}.total_venta, u.nombre as usuario
            FROM {$table}
            LEFT JOIN usuario u ON {$table}.idusuario=u.idusuario
            WHERE
            DATE({$table}.fecha_hora) >= '$fecha_inicio'
            AND DATE({$table}.fecha_hora) < DATE_ADD('$fecha_fin', INTERVAL 1 DAY)
            AND {$table}.estado NOT IN ('Cotizado', 'Anulado', 'AnuladoC')
            AND {$table}.codigotipo_comprobante IN (" . implode(',', $comprobantes[$table]) . ")";
      if ($idusuario != "all") {
        $query .= " AND {$table}.idusuario='$idusuario'";
      }
      $sql .= ($sql != "" ? " UNION ALL " : "") . $query;
    }
    $sql = "SELECT IFNULL(SUM(total_venta),0) AS sumatotalusuario, usuario FROM (" . $sql . ") AS subquery";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function sumventasfechausuarioNC($fecha_inicio, $fecha_fin, $idusuario)
  {
    // --
    if ($idusuario == "all") {
      $sql = "SELECT IFNULL(sum(v.total_venta),0) AS sumatotalusuario, u.nombre as usuario FROM venta v inner join usuario u on v.idusuario=u.idusuario  where date(v.fecha_hora)>='$fecha_inicio' and date(v.fecha_hora)<='$fecha_fin' AND v.codigotipo_comprobante in (7) and v.estado!='Cotizado' and v.estado!='Anulado' and v.estado!='AnuladoC' ";
    } else {
      $sql = "SELECT IFNULL(sum(v.total_venta),0) AS sumatotalusuario, u.nombre as usuario FROM venta v inner join usuario u on v.idusuario=u.idusuario  where v.idusuario='$idusuario' and date(v.fecha_hora)>='$fecha_inicio' and date(v.fecha_hora)<='$fecha_fin' AND v.codigotipo_comprobante in (7) and v.estado!='Cotizado' and v.estado!='Anulado' and v.estado!='AnuladoC' ";
    }

    // --
    return ejecutarConsultaSimpleFila($sql);
  }

  public function comprasfecha($fecha_inicio, $fecha_fin)
  {
    $sql = "SELECT DATE(i.fecha_hora) as fecha,u.nombre as usuario, p.nombre as proveedor ,i.codigotipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado from ingreso i inner join persona p on i.idproveedor=p.idpersona inner join usuario u on i.idusuario=u.idusuario where DATE(i.fecha_hora)>='$fecha_inicio' and DATE(i.fecha_hora)<='$fecha_fin' and i.estado!='Anulado'";

    return ejecutarConsulta($sql);
  }
  public function sumcomprasfecha($fecha_inicio, $fecha_fin)
  {
    $sql = "SELECT IFNULL(sum(total_compra),0) AS sumatotalcompras FROM ingreso  where date(fecha_hora)>='$fecha_inicio' and date(fecha_hora)<='$fecha_fin' and estado!='Anulado'";

    return ejecutarConsultaSimpleFila($sql);
  }


  public function ventasfechacliente($fecha_inicio, $fecha_fin, $idcliente)
  {
    $sql = "SELECT DATE(v.fecha_hora) as fecha,u.nombre as usuario, p.nombre as cliente, p.num_documento,v.codigotipo_comprobante,tc.descripcion_tipo_comprobante,v.serie,v.correlativo,v.total_venta,v.impuesto,v.estado from venta v inner join persona p on v.idcliente=p.idpersona inner join usuario u on v.idusuario=u.idusuario INNER JOIN codigotipo_comprobante tc ON tc.codigotipo_comprobante=v.codigotipo_comprobante where date(v.fecha_hora)>='$fecha_inicio' and date(v.fecha_hora)
      <='$fecha_fin' AND v.idcliente='$idcliente' AND v.codigotipo_comprobante in (1,3)  and v.estado!='Cotizado' and v.estado!='Anulado' and v.estado!='AnuladoC'";

    return ejecutarConsulta($sql);
  }

  public function sumventasfechacliente($fecha_inicio, $fecha_fin, $idcliente)
  {
    $sql = "SELECT IFNULL(sum(v.total_venta),0) AS sumatotalcliente, p.nombre as cliente FROM venta v inner join persona p on v.idcliente=p.idpersona  where v.idcliente='$idcliente' and date(v.fecha_hora)>='$fecha_inicio' and date(v.fecha_hora)<='$fecha_fin' AND v.codigotipo_comprobante in (1,3) and v.estado!='Cotizado' and v.estado!='Anulado' and v.estado!='AnuladoC' ";

    return ejecutarConsultaSimpleFila($sql);
  }
  // SELECT IFNULL(sum(total_venta),0) as ss from venta where idusuario='1' and date(fecha_hora)>='2018-03-01' and date(fecha_hora)<='2018-03-28' AND codigotipo_comprobante in (1,3) and estado!='Cotizado' and estado!='AnuladoC'


  public function ventasfechausuarioSP($fecha_inicio, $fecha_fin, $idusuario)
  {
    // --
    if ($idusuario == "all") {
      $sql = "SELECT DATE(sp.fecha_pago) AS fecha, sp.idsoporte, sp.idusuario, sp.cuota, sp.saldo, sp.tipo_pago, u.nombre as usuario, p.nombre as cliente, p.num_documento, s.estado_entrega, s.codigo_soporte
        FROM soporte_pago sp
        INNER JOIN soporte s ON sp.idsoporte=s.idsoporte
        INNER JOIN usuario u ON sp.idusuario=u.idusuario
        INNER JOIN persona p ON sp.idcliente=p.idpersona
        WHERE  DATE(sp.fecha_pago)>='$fecha_inicio' 
        AND DATE(sp.fecha_pago)<='$fecha_fin'";
    } else {
      $sql = "SELECT DATE(sp.fecha_pago) AS fecha, sp.idsoporte, sp.idusuario, sp.cuota, sp.saldo, sp.tipo_pago, u.nombre as usuario, p.nombre as cliente, p.num_documento, s.estado_entrega, s.codigo_soporte
        FROM soporte_pago sp
        INNER JOIN soporte s ON sp.idsoporte=s.idsoporte
        INNER JOIN usuario u ON sp.idusuario=u.idusuario
        INNER JOIN persona p ON sp.idcliente=p.idpersona
        WHERE sp.idusuario='$idusuario' 
        AND  DATE(sp.fecha_pago)>='$fecha_inicio' 
        AND DATE(sp.fecha_pago)<='$fecha_fin'";
    }
    // $sql="SELECT DATE(v.fecha_hora) as fecha,u.nombre as usuario, p.nombre as cliente, v.codigotipo_comprobante,tc.descripcion_tipo_comprobante,v.serie,v.correlativo,v.total_venta,v.impuesto,v.estado from venta v inner join persona p on v.idcliente=p.idpersona inner join usuario u on v.idusuario=u.idusuario INNER JOIN codigotipo_comprobante tc ON tc.codigotipo_comprobante=v.codigotipo_comprobante where v.idusuario='$idusuario' AND v.codigotipo_comprobante in (1,3) and (v.estado!='Cotizado' or v.estado!='AnuladoC')";

    // --
    return ejecutarConsulta($sql);
  }

  public function sumventasfechausuarioSP($fecha_inicio, $fecha_fin, $idusuario)
  {
    // --
    if ($idusuario == "all") {
      $sql = "SELECT IFNULL(sum(sp.cuota),0) AS sumatotalusuario, u.nombre as usuario FROM soporte_pago sp inner join usuario u on sp.idusuario=u.idusuario  where date(sp.fecha_pago)>='$fecha_inicio' and date(sp.fecha_pago)<='$fecha_fin'";
    } else {
      $sql = "SELECT IFNULL(sum(sp.cuota),0) AS sumatotalusuario, u.nombre as usuario FROM soporte_pago sp inner join usuario u on sp.idusuario=u.idusuario  where sp.idusuario='$idusuario' and date(sp.fecha_pago)>='$fecha_inicio' and date(sp.fecha_pago)<='$fecha_fin'";
    }

    // --
    return ejecutarConsultaSimpleFila($sql);
  }
}
