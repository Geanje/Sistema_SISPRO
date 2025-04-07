<?php
require_once "../modelos/consulta-ventas-general.php";

$ConsultaVentaxSerie = new ConsultaVentaxSerie();



switch ($_REQUEST["operacion"]) {
    case "consultaxSerieVendida":
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $producto = $_REQUEST["producto"];
        $serie = $_REQUEST["serie"];

        $rspta = $ConsultaVentaxSerie->getVentasxSerie($fecha_inicio, $fecha_fin, $producto, $serie);

        $data = array();

        while ($reg = $rspta->fetch_object()) {
            list($anno, $mes, $dia) = explode("-", $reg->fecha);

            $data[] = [
                "0" => $dia . "-" . $mes . "-" . $anno,
                "1" => $reg->cliente,
                "2" => $reg->serie . '-' . $reg->correlativo,
                "3" => $reg->articulo,
                "4" => $reg->serieArticulo,
                "5" => $reg->cantidad,
                "6" => $reg->precio_venta
            ];
        }

        $result = [
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ];
        echo json_encode($result);
        $encoded_data = json_encode($data, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
        file_put_contents('../reportes/temp_rptjson/consulta-ventas-genereral.json', $encoded_data);
        break;
}
