<?php
if (strlen(session_id()) < 1)
    session_start();


require_once "../modelos/NCServicio.php";
$nservicio = new NServicio();

require_once "../modelos/Factura.php";
$factura = new Factura();

$id_factura = isset($_POST["id_factura"]) ? limpiarCadena($_POST["id_factura"]) : "";
$idfacturarelacionado = isset($_POST["idfacturarelacionado"]) ? limpiarCadena($_POST["idfacturarelacionado"]) : "";
$idcliente = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
$idtiponotacredito = isset($_POST["idtiponotacredito"]) ? limpiarCadena($_POST["idtiponotacredito"]) : "";
$serie = isset($_POST["serie"]) ? limpiarCadena($_POST["serie"]) : "";
$correlativo = isset($_POST["correlativo"]) ? limpiarCadena($_POST["correlativo"]) : "";
$impuesto = isset($_POST["impuesto"]) ? limpiarCadena($_POST["impuesto"]) : "";
$idmoneda = isset($_POST["idmoneda"]) ? limpiarCadena($_POST["idmoneda"]) : "";
$total_venta_gravado = isset($_POST["total_venta_gravado"]) ? limpiarCadena($_POST["total_venta_gravado"]) : "";
$total_venta_exonerado = isset($_POST["total_venta_exonerado"]) ? limpiarCadena($_POST["total_venta_exonerado"]) : "";
$total_venta_inafectas = isset($_POST["total_venta_inafectas"]) ? limpiarCadena($_POST["total_venta_inafectas"]) : "";
$total_venta_gratuitas = isset($_POST["total_venta_gratuitas"]) ? limpiarCadena($_POST["total_venta_gratuitas"]) : "";
$total_descuentos = isset($_POST["total_descuentos"]) ? limpiarCadena($_POST["total_descuentos"]) : "";
$isc = isset($_POST["isc"]) ? limpiarCadena($_POST["isc"]) : "";
$total_igv = isset($_POST["total_igv"]) ? limpiarCadena($_POST["total_igv"]) : "";
$total_importe = isset($_POST["total_importe"]) ? limpiarCadena($_POST["total_importe"]) : "";
$sustento = isset($_POST["sustento"]) ? limpiarCadena($_POST["sustento"]) : "";

$isession = isset($_POST["isession"]) ? limpiarCadena($_POST["isession"]) : "";
$fecha_hora = date('Y-m-d');
$idusuario = $_SESSION["idusuario"];
$igv_asig = 18;
$rpta_sunat_codigo = isset($_POST["rpta_sunat_codigo"]) ? limpiarCadena($_POST["rpta_sunat_codigo"]) : "";

switch ($_GET["op"]) {

    case 'guardaryeditar':
        if (empty($idventa)) {
            require_once "../reportes/numeroALetras.php";
            $letras = NumeroALetras::convertir($total_importe);
            list($num, $cen) = explode('.', $total_importe);
            $leyenda = $letras . 'Y ' . $cen . '/100 SOLES';
            $rspta = $nservicio->insertar($idcliente, $idusuario, $serie, $correlativo, $fecha_hora, $impuesto, $igv_asig, $total_venta_gravado, $total_venta_inafectas, $total_venta_exonerado, $total_venta_gratuitas, $isc, $total_igv, $total_importe, $leyenda, $idmoneda, $idtiponotacredito, $sustento, $idfacturarelacionado, $_POST["nombre"], $_POST["cantidadd"], $_POST["precio_ventaa"], $_POST["descuentoo"], $_POST["seriee"]);
            //echo $rspta ? "Venta registrada" : "No se pudieron registrar todos los datos de la venta";
            $rsptaVenta = false;
            if ($rspta) {
                $rsptaVenta = $factura->anular($idfacturarelacionado);
            }
        } else {
        }
        break;


    case 'listar':
        $rspta = $nservicio->listar();
        $data = array();
        $empresa = $factura->cabecera_perfil();
        $valid_ids = ['1', '2', '6', '7'];
        while ($reg = $rspta->fetch_object()) {
            $url = in_array($reg->idmotivo_doc, $valid_ids)
                ? '../reportes/ReportePDF_NCServicio.php?id='
                : '../reportes/preFactura.php?id=';
            $nombre_archivo = $empresa['ruc'] . '-' . '0' . $reg->codigotipo_comprobante . '-' . $reg->serie . '-' . $reg->correlativo;
            $data[] = array(
                "0" => ($reg->estado == 'AceptadoNC') ?
                    '<button id="' . $reg->id_factura . '" data-nombrearchivo="' . $nombre_archivo . '" title="Enviar a Sunat" class="btn btn-default btn-xs API_SUNAT" onclick="enviarDatosASunat(\'' . $nombre_archivo . '\', ' . $reg->id_factura . ')">
            <img src="../public/img/logo_sunat.jpg" data-id="40" class="descargar-pdf" style="width: 29px; height: 32px;">
        </button>' .
                    '<a target="_blank" href="' . $url . $reg->id_factura . '"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>' :

                    '<a target="_blank" href="' . $url . $reg->id_factura . '"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>',
                "1" => $reg->fecha,
                "2" => $reg->cliente,
                "3" => $reg->usuario,
                "4" => $reg->descripcion_tipo_comprobante,
                "5" => $reg->serie . '-' . $reg->correlativo,
                "6" => $reg->motivo,
                "7" => '<center><button class="btn btn-info" onclick="mostrarDocRel(' . $reg->id_factura . ',' . $reg->doc_relacionado . ')" data-toggle="modal" data-target="#docRelacionadoModal">' . $reg->doc_relacionado . '</button></center>',
                "8" => ($reg->estado == 'AceptadoNC') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>',
                "9" => ($reg->rpta_sunat_codigo === null ? '<span class="label bg-gray">NO ENVIADO</span>' :
                    ($reg->rpta_sunat_codigo == 1 ? '<span class="label bg-green">ENVIADO</span>' :
                        ($reg->rpta_sunat_codigo == 2 ? '<span class="label bg-red">RECHAZADO</span>' :
                            '<span class="label bg-yellow">ERROR</span>')))
            );
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    case 'listarComprobantes':
        $rspta = $nservicio->listarComprobantes();
        $rspta_correlativo = $nservicio->ultimoCorrelativo();
        $data = array();
        while ($reg = $rspta->fetch_object()) {
            $ultimo_correlativo = '00000001';
            $s = substr($reg->serie, -4, 1);
            $serie = $reg->serie;
            if ($rspta_correlativo) {
                if ($rspta_correlativo["correlativo"] == '99999999') {
                    $ser = substr($rspta_correlativo["serie"], 1) + 1;
                    $seri = str_pad((string) $ser, 3, "0", STR_PAD_LEFT);

                    if ($s == "F") {
                        $serie = "F" . $seri;
                    } else {
                        $serie = "B" . $seri;
                    }
                    $ultimo_correlativo = '00000001';
                } else {
                    $corre = $rspta_correlativo["correlativo"] + 1;
                    $serie = $reg->serie;
                    $ultimo_correlativo = str_pad($corre, 8, "0", STR_PAD_LEFT);
                }
            }
            $data[] = array(
                "0" => '<button class="btn btn-warning" data-dismiss="modal" name="agregarDocu" onclick="agregarDocumento(' . $reg->id_factura . ',' . $_SESSION['isession'] . ',' . $reg->idcliente . ',\'' . $reg->cliente . '\',' . $reg->num_documento . ',\'' . $reg->serie . '\',' . $reg->correlativo . ',' . $reg->idmoneda . ',\'' . $reg->descripcion . '\',\'' . $reg->descripcion_tipo_comprobante . '\',\'' . $reg->fecha . '\',' . $reg->op_gravadas . ',' . $reg->op_exoneradas . ',' . $reg->total_venta . ',' . $reg->impuesto . ',\'' . $ultimo_correlativo . '\',\'' . $serie . '\');mostrarform(true);" ><span class="fa fa-plus"></span></button>',
                "1" => $reg->fecha,
                "2" => $reg->cliente,
                "3" => $reg->usuario,
                "4" => $reg->descripcion_tipo_comprobante,
                "5" => $reg->serie . '-' . $reg->correlativo,
                "6" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>'
            );
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    case 'selectTipoNotaCredito':
        $rspta = $nservicio->listarTipoNotaCredito();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idmotivo_documento . '>' . $reg->motivo . '</option>';
        }
        break;

    case 'mostrarDocRel':
        $rspta = $nservicio->mostrarDocRelacionado($id_factura, $idfacturarelacionado);
        echo json_encode($rspta);
        break;

    case 'isession':
        $_SESSION['isession'] = $isession;
        session_regenerate_id();
        break;

    case 'anular':
        $rspta = $nservicio->anular($id_factura);
        echo $rspta ? "Nota de crédito anulada" : "Nota de crédito no se puede anular";
        break;

    case 'listarDetalleComprobantes':
        $id = $_GET['id'];
        $idtiponotacredito = $_SESSION['isession'];
        $cont = 0;
        $rspta = $nservicio->listardetallecomprobantes($id);
        $total = 0.00;
        $impuesto = 18;
        $sumigv = 0;
        $sumdes = 0;
        $grava = 0;

        echo '<thead style="background-color:#A9D0F5">
                                        <th>Opciones</th>
                                        <th>Artículo</th>
                                        <th>Serie</th>
                                        <th>Afectacion</th>
                                        <th>Cantidad</th>
                                        <th>Val. Vta. U.</th>
                                        <th>Descuento</th>
                                        <th>Impuestos</th>
                                        <th>Precio Venta</th>
                                        <th>Val. Vta. Total</th>
                                        <th>Importe</th>
                                    </thead>';

        while ($reg = $rspta->fetch_object()) {
            if ($reg->afectacion == 'Exonerado') {
                $valorVentaU = $reg->precio_venta;
                $valorVentaU = round($valorVentaU, 2);
                $valorVentaT = $reg->precio_venta * $reg->cantidad;
                $valorVentaT = round($valorVentaT, 2);
                $newigv = 0.00;
            } elseif ($reg->Tipo == 'Con_IGV') {
                $valorVentaU = $reg->precio_venta / (1 + ($impuesto / 100));
                $valorVentaU = round($valorVentaU, 2);
                $valorVentaT = $reg->precio_venta / (1 + ($impuesto / 100)) * $reg->cantidad - $reg->descuento;
                $valorVentaT = round($valorVentaT, 2);
                $newigv = ($reg->cantidad * $reg->precio_venta / (1 + ($impuesto / 100)) - $reg->descuento) * ($impuesto / 100);
                $newigv = round($newigv, 2);
                $total = $total + ($reg->precio_venta * $reg->cantidad - $reg->descuento);
                $precio_venta = $reg->precio_venta;
                $subtotal = $reg->subtotal;
                $afectacion = "Gravado";
            } else {
                $valorVentaU = $reg->precio_venta;
                $valorVentaU = round($valorVentaU, 2);
                $valorVentaT = $reg->precio_venta * $reg->cantidad - $reg->descuento;
                $valorVentaT = round($valorVentaT, 2);
                $newigv = $reg->cantidad * $reg->precio_venta * ($impuesto / 100);
                $newigv = round($newigv, 2);
                $total = $total + ($reg->precio_venta * $reg->cantidad - $reg->descuento) + $newigv;
                $precio_venta = $reg->precio_venta * 1.18;
                $subtotal = $reg->subtotal * 1.18;
                $afectacion = "Gravado";
            }
            $sumigv += $newigv;
            $sumdes += $reg->descuento;
            $op_gravadas = $reg->op_gravadas;
            $op_inafectas = $reg->op_inafectas;
            $op_exoneradas = $reg->op_exoneradas;
            $op_gratuitas = $reg->op_gratuitas;
            $isc = $reg->isc;

            if ($idtiponotacredito == '1' || $idtiponotacredito == '2' || $idtiponotacredito == '6') {
                echo '<tr class="fila" id="fila' . $cont . '>' .
                    '<td><button type="button" class="btn btn-danger" onclick="">X</button></td>' .
                    '<td></td>' .
                    '<td><input type="hidden" name="nombre[]" value="' . $reg->id_detalle_factura . '">' . $reg->nombre . '</td>' .
                    '<td><input type="hidden" name="seriee[]" value="' . $reg->seried . '">' . $reg->seried . '</td>' .
                    '<td><input type="hidden" name="afectacio[]" value="' . $cont . '">' . $afectacion . '</td>' .
                    '<td><input type="hidden" name="cantidadd[]"  value="' . $reg->cantidad . '" >' . $reg->cantidad . '</td>' .
                    '<td><span name="valor_venta_u" id="valor_venta_u' . $cont . '" >' . $valorVentaU . '</span></td>' .
                    '<td><input type="hidden" name="descuentoo[]" value="' . $reg->descuento . '" >' . $reg->descuento . '</td>' .
                    '<td><span name="impuest" id="impuest' . $cont . '" >' . $newigv . '</span></td>' .
                    '<td><input type="hidden" name="precio_ventaa[]" value="' . $precio_venta . '">' . $precio_venta . '</td>' .
                    '<td><span name="valor_venta_t" id="valor_venta_t' . $cont . '" >' . $valorVentaT . '</span></td>' .
                    '<td><span name="subtotal" id="subtotal' . $cont . '">' . $subtotal . '</span></td>' .
                    '</tr>';
                $cont++;
            } else if ($idtiponotacredito == '7') {
                echo '<tr class="fila' . $cont . '" id="fila' . $cont . '>' .
                    '<td><button type="button" class="btn btn-danger" onclick="">X</button></td>' .
                    '<td><button type="button" class="btn btn-danger" onclick="eliminarDetallee(' . $cont . ')"><i class="fa fa-times-circle"></i></button></td>' .
                    '<td><input type="hidden" name="idarticuloo[]" value="' . $reg->nombre . '">' . $reg->nombre . '</td>' .
                    '<td><input type="hidden" name="seriee[]" value="' . $reg->seried . '">' . $reg->seried . '</td>' .
                    '<td><input type="hidden" name="afectacio[]" value="' . $cont . '">' . $afectacion . '</td>' .
                    '<td ><input type="number" name="cantidadd[]"  id="cantidad' . $cont . '" style="text-align: center;width:50px;" value="' . $reg->cantidad . '" ></td>' .
                    '<td><span name="valor_venta_u" id="valor_venta_u' . $cont . '" >' . $valorVentaU . '</span></td>' .

                    '<td><input type="hidden" name="descuentoo[]" value="' . $reg->descuento . '" >' . $reg->descuento . '</td>' .
                    '<td><span name="impuest" id="impuest' . $cont . '" >' . $newigv . '</span></td>' .
                    '<td><input type="hidden" name="precio_ventaa[]" value="' . $precio_venta . '">' . $precio_venta . '</td>' .
                    '<td><span name="valor_venta_t" id="valor_venta_t' . $cont . '" >' . $valorVentaT . '</span></td>' .
                    '<td><span name="subtotal" id="subtotal' . $cont . '">' . $subtotal . '</span></td>' .
                    '</tr>';
                echo '<script>
                        $("#cantidad' . $cont . '").keyup(modificarSubtotales);
                        $("#cantidad' . $cont . '").change(modificarSubtotales);
                    </script>';
                $cont++;
            }
        }
        ;


        if ($idtiponotacredito == '1' || $idtiponotacredito == '2' || $idtiponotacredito == '6') {
            echo '<tfoot>
                                      <tr>
                                        <th colspan="7"></th>
                                        <th colspan="2">TOTAL VENTA GRAVADO</th>
                                        <th><h4 id="totalg">' . $op_gravadas . '</h4><input type="hidden" name="total_venta_gravado" id="total_venta_gravado" value="' . $op_gravadas . '"></th>
                                      </tr>
                                       
                                      
                                      
                                       <tr>
                                        <th style="height:2px;"  colspan="7"></th>
                                        <th colspan="2">IGV</th>
                                        <th><h4 id="totaligv">' . $sumigv . '</h4><input type="hidden" name="total_igv" id="total_igv" value="' . $sumigv . '"></th>
                                       </tr>
                                       <tr>
                                        <th style="height:2px;" colspan="7"></th>
                                        <th style="height:2px;" colspan="2">TOTAL IMPORTE</th>
                                        <th style="height:2px;"><h4 id="totalimp">' . $total . '</h4><input type="hidden" name="total_importe" id="total_importe" value="' . $total . '"></th>
                                       </tr>
                                    </tfoot>';
        } else if ($idtiponotacredito == '7') {
            echo '<tfoot>
                                      <tr>
                                        <th colspan="7"></th>
                                        <th colspan="2">TOTAL VENTA GRAVADO</th>
                                        <th><h4 id="totalg">' . $op_gravadas . '</h4><input type="hidden" name="total_venta_gravado" id="total_venta_gravado" value="' . $op_gravadas . '"></th>
                                      </tr>
                                      
                                       <tr>
                                        <th style="height:2px;"  colspan="7"></th>
                                        <th colspan="2">IGV</th>
                                        <th><h4 id="totaligv">' . $sumigv . '</h4><input type="hidden" name="total_igv" id="total_igv" value="' . $sumigv . '"></th>
                                       </tr>
                                       <tr>
                                        <th style="height:2px;" colspan="7"></th>
                                        <th style="height:2px;" colspan="2">TOTAL IMPORTE</th>
                                        <th style="height:2px;"><h4 id="totalimp">' . $total . '</h4><input type="hidden" name="total_importe" id="total_importe" value="' . $total . '"></th>
                                       </tr>
                                    </tfoot>';
        }
        ;

        break;
}
