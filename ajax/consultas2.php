<?php
require_once "../modelos/Consultas2.php";

$consulta = new Consultas();


switch ($_GET["op"]) {
  case 'comprasfecha':
    $fecha_inicio = $_REQUEST["fecha_inicio"];
    $fecha_fin = $_REQUEST["fecha_fin"];

    $rspta = $consulta->comprasfecha($fecha_inicio, $fecha_fin);
    //Vamos a declarar un array
    $data = array();
    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => $reg->fecha,
        "1" => $reg->usuario,
        "2" => $reg->proveedor,
        "3" => $reg->tipo_comprobante,
        "4" => $reg->serie_comprobante . ' ' . $reg->num_comprobante,
        "5" => $reg->total_compra,
        "6" => $reg->impuesto,
        "7" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado<span>' : '<span class="label bg-red">Anulado<span>'
      );
    }
    $results = array(
      "sEcho" => 1, //Informacion para el datatable
      "iTotalRecords" => count($data), //Enviamos el total de registtros en el datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);



    break;


  case 'ventasfechacliente':
    $fecha_inicio = $_REQUEST["fecha_inicio"];
    $fecha_fin = $_REQUEST["fecha_fin"];
    $idcliente = $_REQUEST["idcliente"];


    $rspta = $consulta->ventasfechacliente($fecha_inicio, $fecha_fin, $idcliente);
    //Vamos a declarar un array
    $data = array();
    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => $reg->fecha,
        "1" => $reg->usuario,
        "2" => $reg->cliente,
        "3" => $reg->num_documento,
        "4" => $reg->descripcion_tipo_comprobante,
        "5" => $reg->serie . ' - ' . $reg->correlativo,
        "6" => $reg->total_venta,
        "7" => $reg->impuesto,
        "8" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado<span>' : '<span class="label bg-red">Anulado<span>'
      );
    }
    $results = array(
      "sEcho" => 1, //Informacion para el datatable
      "iTotalRecords" => count($data), //Enviamos el total de registtros en el datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);



    break;

  case 'reportekardex':
    $rspta = $consulta->kardex();
    $data = array();
    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => $reg->codigo,
        "1" => $reg->nombre,
        "2" => $reg->categoria,
        "3" => $reg->stock_ingreso,
        "4" => $reg->stock_salida,
        "5" => $reg->stock

      );
    }
    $results = array(
      "isEcho" => 1,
      "iTotalRecords" => count($data), //Enviamos el total de registtros en el datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);
    break;

  case 'reiniciarkardex':
    $rspta = $consulta->reiniciarkardex();
    echo $rspta ? "Kardex reiniciado" : "No se pudo reiniciar";
    break;

  case 'consultaCotizacion':
    $fecha_inicio = $_REQUEST['fecha_inicio'];
    $fecha_fin = $_REQUEST['fecha_fin'];
    $rspta = $consulta->consultaCotizaciones($fecha_inicio, $fecha_fin);
    $data = array();
    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => $reg->fecha,
        "1" => $reg->usuario,
        "2" => $reg->cliente,
        // "3"=>$reg->descripcion_tipo_comprobante,
        "3" => 'Proforma',
        "4" => $reg->serie . ' - ' . $reg->correlativo,
        "5" => $reg->total_venta,
        "6" => $reg->impuesto,
        "7" => ($reg->estado == 'Cotizado') ? '<span class="label bg-green">Cotizado<span>' : '<span class="label bg-red">Anulado<span>'
      );
    }
    $results = array(
      "isEcho" => 1,
      "iTotalRecords" => count($data), //Enviamos el total de registtros en el datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);
    break;

  case 'ventasFechaUsuario':
    $fecha_inicio = $_REQUEST['fecha_inicio'];
    $fecha_fin = $_REQUEST['fecha_fin'];
    $idusuario = $_REQUEST['idusuario'];
    $modulo = $_REQUEST['modulo'];
    $rspta = $consulta->ventasfechausuario($fecha_inicio, $fecha_fin, $idusuario, $modulo);
    $data = array();
    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => $reg->fecha,
        "1" => $reg->cliente,
        "2" => $reg->num_doc,
        "3" => $reg->descripcion_tipo_comprobante,
        "4" => $reg->serie . ' - ' . $reg->correlativo,
        "5" => ($reg->estado == 'Anulado') ? 0 : $reg->total_venta,
        "6" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado<span>' : '<span class="label bg-red">Anulado<span>'

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
    file_put_contents('../reportes/temp_rptjson/ventas-Fecha-Usuario.json', $encoded_data);

    break;

  case 'sumVentasFechaUsuario':
    $fecha_inicio = $_REQUEST['fecha_inicio'];
    $fecha_fin = $_REQUEST['fecha_fin'];
    $idusuario = $_REQUEST['idusuario'];
    $modulo = $_REQUEST['modulo'];
    $rspta = $consulta->sumventasfechausuario($fecha_inicio, $fecha_fin, $idusuario, $modulo);
    echo json_encode($rspta);
    break;
  case 'sumComprasFecha':
    $fecha_inicio = $_REQUEST['fecha_inicio'];
    $fecha_fin = $_REQUEST['fecha_fin'];
    $rspta = $consulta->sumcomprasfecha($fecha_inicio, $fecha_fin);
    echo json_encode($rspta);
    break;

  case 'sumVentasFechaCliente':
    $fecha_inicio = $_REQUEST['fecha_inicio'];
    $fecha_fin = $_REQUEST['fecha_fin'];
    $idcliente = $_REQUEST['idcliente'];
    $rspta = $consulta->sumventasfechacliente($fecha_inicio, $fecha_fin, $idcliente);
    echo json_encode($rspta);
    break;

  case 'ventasFechaUsuarioSP':
    $fecha_inicio = $_REQUEST['fecha_inicio'];
    $fecha_fin = $_REQUEST['fecha_fin'];
    $idusuario = $_REQUEST['idusuario'];
    $rspta = $consulta->ventasfechausuarioSP($fecha_inicio, $fecha_fin, $idusuario);
    $data = array();
    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => $reg->fecha,
        "1" => $reg->cliente,
        "2" => $reg->num_documento,
        "3" => $reg->tipo_pago,
        "4" => $reg->usuario,
        "5" => $reg->cuota,
        "6" => $reg->saldo,
        "7" => ($reg->estado_entrega == 'Pendiente') ? '<span class="label bg-blue">Pendiente<span>' : '<span class="label bg-green">Entregado<span>'

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
    file_put_contents('../reportes/temp_rptjson/soporte_pago_mensual.json', $encoded_data);

    break;

  case 'sumVentasFechaUsuarioSP':
    $fecha_inicio = $_REQUEST['fecha_inicio'];
    $fecha_fin = $_REQUEST['fecha_fin'];
    $idusuario = $_REQUEST['idusuario'];
    $rspta = $consulta->sumventasfechausuarioSP($fecha_inicio, $fecha_fin, $idusuario);
    echo json_encode($rspta);
    break;
}
