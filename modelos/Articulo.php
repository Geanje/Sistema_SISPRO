<?php
//Incluimos conexion a la base de trader_cdlrisefall3methods
require "../config/Conexion.php";

class Articulo
{
  //Implementando nuestro constructor
  public function __construct()
  {


  }
  //Implementamos metodo para insertar registro
  public function insertar($idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen, $unidad_medida, $descripcion_otros, $afectacion)
  {
    $sql = "INSERT INTO articulo (idcategoria,codigo,nombre,stock,descripcion,imagen,unidad_medida,descripcion_otros,condicion,afectacion,stock_ingreso,stock_salida)
      VALUES ('$idcategoria','$codigo','$nombre','$stock','$descripcion','$imagen','$unidad_medida','$descripcion_otros','1','$afectacion','$stock','0')";
    return ejecutarConsulta($sql);
  }
  //Implementamos un metodo para editar registro
  public function editar($idarticulo, $idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen, $unidad_medida, $descripcion_otros, $afectacion)
  {
    $sql = "UPDATE articulo SET idcategoria='$idcategoria',codigo='$codigo',nombre='$nombre',stock='$stock', descripcion='$descripcion', imagen='$imagen',unidad_medida='$unidad_medida',descripcion_otros='$descripcion_otros',afectacion='$afectacion',stock_ingreso='$stock',stock_salida='0'
      where idarticulo='$idarticulo'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un metodo para eliminar registro
  public function desactivar($idarticulo)
  {
    $sql = "UPDATE articulo SET condicion='0'
      where idarticulo='$idarticulo'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un metodo para eliminar registro
  public function activar($idarticulo)
  {
    $sql = "UPDATE articulo SET condicion='1'
      where idarticulo='$idarticulo'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un metodo para mostrar los datos de un registro a modificar
  public function mostrar($idarticulo)
  {
    $sql = "SELECT * FROM articulo where idarticulo='$idarticulo'";
    //$sql="SELECT nombre, idcategoria, unidad_medida, descripcion, imagen, codigo FROM articulo where idarticulo='$idarticulo'";
    return ejecutarConsultaSimpleFila($sql);

  }


  //Implementar metodo para listar los registros
  public function listar()
  {
    $sql = "SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,a.descripcion,a.imagen,a.unidad_medida,a.descripcion_otros,a.condicion FROM articulo a inner join categoria c on a.idcategoria=c.idcategoria";
    return ejecutarConsulta($sql);

  }

  public function listar_precios()
  {
    $sql = "SELECT
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


  //Implementar metodo para listar los registros activos
  public function listarActivos()
  {
    $sql = "SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,a.descripcion,a.imagen,a.condicion,a.afectacion FROM articulo a inner join categoria c on a.idcategoria=c.idcategoria where a.condicion='1'";
    return ejecutarConsulta($sql);

  }

  public function listarActivosVenta()
  {
    $sql = "SELECT a.idarticulo, a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.unidad_medida,a.descripcion_otros,a.stock,(SELECT precio_venta FROM detalle_ingreso where idarticulo=a.idarticulo order by iddetalle_ingreso desc limit 0,1) as precio_venta,a.descripcion,a.imagen,a.condicion,a.afectacion from articulo a inner join categoria c on a.idcategoria=c.idcategoria where a.condicion='1'";
    return ejecutarConsulta($sql);
  }


  public function imprimirCodigoBarra($idarticulo)
  {
    $sql = " SELECT idarticulo,codigo FROM articulo WHERE idarticulo='$idarticulo'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function agregarstock($idarticulo, $stockanti, $stocknew)
  {
    $sql = "UPDATE articulo SET stock=$stockanti+$stocknew, stock_ingreso=stock_ingreso+$stocknew where idarticulo='$idarticulo'";
    return ejecutarConsulta($sql);
  }
  public function mostrarStock($idarticulo)
  {
    $sql = "SELECT idarticulo,nombre,stock FROM articulo where idarticulo='$idarticulo'";
    return ejecutarConsultaSimpleFila($sql);
  }
  public function resetearstock($idarticulo)
  {
    $sql = "UPDATE articulo SET stock = 0, stock_ingreso = 0, stock_salida = 0";
    return ejecutarConsulta($sql);
  }


  public function insertarConEstado($idcategoria, $nombre, $codigo, $unidad_medida, $condicion, $imagen)
  {
    global $conexion;  // Usamos la conexión global
    $stock = 0; // Stock inicial
    $sql = "INSERT INTO articulo (
                idcategoria,
                nombre,
                codigo,
                unidad_medida,
                condicion,
                imagen,
                stock
            ) 
            VALUES (
                '$idcategoria',
                '$nombre',
                '$codigo',
                '$unidad_medida',
                '$condicion',
                '$imagen',
                '$stock'
            )";
    $res = mysqli_query($conexion, $sql);
    if (!$res) {
      error_log("Error al insertar (Nombre: $nombre, IDCategoria: $idcategoria, Código: $codigo): " . mysqli_error($conexion));
    }
    return $res;
  }

}
?>