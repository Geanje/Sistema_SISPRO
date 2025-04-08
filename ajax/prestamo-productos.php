<?php
require_once "../modelos/Prestamo-productos.php";

$prestamoProductos = new PrestamoProductos();

switch ($_GET["op"]) {

  case 'listarSucursales':
    require_once "../modelos/Persona.php";
    $persona = new Persona();
    $rspta = $persona->listarsucursal();
    echo "<option value='todos'>Todos</option>";
    while ($reg = $rspta->fetch_object()) {
      echo "<option value='" . $reg->idpersona . "'>" . $reg->nombre . "</option>";
    }

    break;

  case 'listaPrestamoProductos':
    $fecha_inicio = $_REQUEST['fecha_inicio'];
    $fecha_fin = $_REQUEST['fecha_fin'];
    $idsucursal = $_REQUEST['idsucursal'];

    $rspta = $prestamoProductos->listaPrestamoProductos($fecha_inicio, $fecha_fin, $idsucursal);
    $data = array();
    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => $reg->fecha,
        "1" => $reg->usuario,
        "2" => $reg->sucursal,
        "3" => $reg->serie . ' - ' . $reg->correlativo,
        "4" => $reg->articulo,
        "5" => $reg->cantidad,
        "6" => $reg->total_venta,
        "7" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado<span>' : '<span class="label bg-red">Anulado<span>'
      );
    }
    $results = array(
      "isEcho" => 1,
      "iTotalRecords" => count($data),
      "iTotalDisplayRecords" => count($data),
      "aaData" => $data
    );

    echo json_encode($results);
    $encoded_data = json_encode($data, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
    file_put_contents('../reportes/temp_rptjson/prestamos-productos.json', $encoded_data);

    break;

  case 'cantProductosPrestadosxSucursal':
    $fecha_inicio = $_REQUEST['fecha_inicio'];
    $fecha_fin = $_REQUEST['fecha_fin'];
    $idsucursal = $_REQUEST['idsucursal'];
    $rspta = $prestamoProductos->cantProductosPrestadosxSucursal($fecha_inicio, $fecha_fin, $idsucursal);
    echo json_encode($rspta);
    break;
}
