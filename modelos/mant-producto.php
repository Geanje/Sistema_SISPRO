<?php
//Incluimos conexion a la base de trader_cdlrisefall3methods
require "../config/Conexion.php";

Class MantProducto
{
  //Implementando nuestro constructor
  public function __construct()
  {


  }

    //Implementamos un metodo para editar registro
    public function guardaryeditar($id_detalle_ingreso,$precio_venta)
    {
      $sql="UPDATE detalle_ingreso SET precio_venta='$precio_venta'
      where iddetalle_ingreso='$id_detalle_ingreso'";
      return ejecutarConsulta($sql);
    }

    //Implementar metodo para listar los registros
    public function listar_productos()
    {
      $sql="SELECT
      di.iddetalle_ingreso,
      a.idarticulo,
      a.codigo,
      a.nombre,
      a.unidad_medida,
      a.descripcion_otros,
      a.condicion,
      c.nombre AS categoria,
      a.stock_ingreso,
      a.stock_salida,
      a.stock,
      di.precio_compra,
      di.precio_venta
  FROM
      articulo a
      INNER JOIN categoria c ON a.idcategoria = c.idcategoria
      INNER JOIN (
          SELECT
              ddi.idarticulo,
              COUNT(ddi.idarticulo) AS cantidadIngresos,
              ddi.precio_compra,
              ddi.precio_venta,
              ddi.iddetalle_ingreso
          FROM
              detalle_ingreso ddi
          WHERE
              ddi.iddetalle_ingreso = (
                  SELECT
                      MAX(ddi2.iddetalle_ingreso)
                  FROM
                      detalle_ingreso ddi2
                  WHERE
                      ddi2.idarticulo = ddi.idarticulo
              )
          GROUP BY
              ddi.idarticulo
      ) di ON di.idarticulo = a.idarticulo
  ORDER BY
      a.idarticulo DESC";
      return ejecutarConsulta($sql);

    }

    public function listar_precios(){
      $sql="SELECT a.idarticulo,a.codigo,a.nombre,a.unidad_medida,a.descripcion_otros,a.condicion,c.nombre as categoria,a.stock_ingreso,a.stock_salida,a.stock,di.precio_compra,di.precio_venta FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria INNER JOIN (SELECT ddi.idarticulo,count(ddi.idarticulo) as cantidadIngresos, ddi.precio_compra,ddi.precio_venta FROM detalle_ingreso ddi  group by ddi.idarticulo order by ddi.idarticulo desc) di ON di.idarticulo = a.idarticulo order by di.idarticulo desc";
      return ejecutarConsulta($sql);
    }


    //Implementar metodo para listar los registros activos
    public function listarActivos()
    {
      $sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,a.descripcion,a.imagen,a.condicion,a.afectacion FROM articulo a inner join categoria c on a.idcategoria=c.idcategoria where a.condicion='1'";
      return ejecutarConsulta($sql);

    }

    public function listarActivosVenta()
    {
      $sql="SELECT a.idarticulo, a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.unidad_medida,a.descripcion_otros,a.stock,(SELECT precio_venta FROM detalle_ingreso where idarticulo=a.idarticulo order by iddetalle_ingreso desc limit 0,1) as precio_venta,a.descripcion,a.imagen,a.condicion,a.afectacion from articulo a inner join categoria c on a.idcategoria=c.idcategoria where a.condicion='1'";
      return ejecutarConsulta($sql);
    }

  }
 ?>
