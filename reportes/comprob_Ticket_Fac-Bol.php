<style type="text/css">
    <!--
    div.zone {
        border: none;
        border-radius: 6mm;
        background: #FFFFFF;
        border-collapse: collapse;
        padding: 3mm;
        font-size: 2.7mm;
    }

    h1 {
        padding: 0;
        margin: 0;
        color: #DD0000;
        font-size: 7mm;
    }

    h2 {
        padding: 0;
        margin: 0;
        color: #222222;
        font-size: 5mm;
        position: relative;
    }
    -->

</style>
<style type="text/css">
    .body {
        margin: 10px;
        padding: 0;
        /*background-image: url(../img/fondo.png);*/
        /*background-repeat: repeat;*/
        /*padding-bottom: 1px;*/
        font-size: 11px;

    }

    .silver {
        background: white;
        padding: 2px 1px 2px;
    }

    .clouds {
        background: #ecf0f1;
        padding: 2px 1px 2px;
    }

    .cuerpoM {
        font-size: 9px;
        width: 100%;
    }

    .razon_social {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 18px;
        font-weight: bold;
        padding-left: 10px;
        margin-right: 10px;
        text-align: center;
        width: 98%;
    }

    .ruc {
        font-size: 14px;
        font-weight: bold;
        padding-left: 10px;
        margin-right: 10px;
        text-align: center;
        width: 98%;
    }

    .direccion {
        padding-left: 10px;
        margin-right: 10px;
        text-align: center;
        width: 100%;
        font-size: 9px;
    }

    .linea {
        width: 100%;
        margin-top: -10px;
    }

    .body,
    td,
    th {
        /*font-family: Arial, Helvetica, sans-serif;*/
        /*font-size:12px;*/
        font-family: Helvetica;

    }

    .articulos {
        font-size: 9px;
        padding-left: 10px;
        padding-right: 10px;
    }

    .direction {
        padding-left: 10px;
        margin-right: 80px;
        text-align: center;
        font-size: 10px;
    }

    .cliente {
        font-size: 9px;
        width: 100%;
        padding-left: 10px;
        padding-right: 10px;

    }
</style>
<page format="200x80" orientation="P" style="font: arial;" class="body">
    <?php
    require_once "../modelos/Perfil.php";
    $perfil = new Perfil();
    $rspta = $perfil->cabecera_perfil();
    // $rspta= Perfil::cabecera_perfil();
    $reg = $rspta->fetch_assoc();
    $rucp = $reg['ruc'];
    $razon_social = $reg['razon_social'];
    $direccion = $reg['direccion'];
    $direccion2 = $reg['direccion2'];
    $distrito = $reg['distrito'];
    $provincia = $reg['provincia'];
    $departamento = $reg['departamento'];
    $fecha_inicio = $reg['fecha_inicio'];
    $telefono = $reg['telefono'];
    $email = $reg['email'];
    $web = $reg['web'];
    $rubro = $reg['rubro'];
    $logo = $reg['logo'];

    $id = $_GET["id"];
    require_once "../modelos/Factura.php";
    $factura = new Factura();
    $rsptac = $factura->ventacabecera($id);
    $regc = $rsptac->fetch_object();
    $cliente = $regc->cliente;
    $ruc = $regc->num_documento;
    $igv_asig = $regc->igv_asig;
    

    if ($regc->codigotipo_comprobante == '1') {
        $codigotipo_comprobante = 'FACTURA  ELECTRÓNICA';
    } else  if ($regc->codigotipo_comprobante == '3') {
        $codigotipo_comprobante = 'BOLETA DE VENTA ELECTRÓNICA';
    } else  if ($regc->codigotipo_comprobante == '8') {
        $codigotipo_comprobante = 'GUIA DE REMISION ELECTRÓNICA REMITENTE';
    } else  if ($regc->codigotipo_comprobante == '12') {
        $codigotipo_comprobante = 'NOTA DE VENTA';
    } else  if ($regc->codigotipo_comprobante == '10') {
        $codigotipo_comprobante = 'PROFORMA';
    } else  if ($regc->codigotipo_comprobante == '22') {
        $codigotipo_comprobante = 'HOJA DE SALIDA';
    }
    $direccioncliente = $regc->direccion;
    $serie = $regc->serie;
    $correlativo = $regc->correlativo;
    $moneda = $regc->descmoneda;
    $fecha = $regc->fecha;
    $fecha_ven = $regc->fecha_ven;
    $codigotipo_pago = $regc->codigo_pago;
    $fechaCompleta = $regc->fechaCompleta;
    list($anno, $mes, $dia) = explode('-', $fecha);

    $horas = substr($fechaCompleta, -9);
    $op_gravadas = $regc->op_gravadas;
    $total_igv = $regc->igv_total;
    $op_inafectas = $regc->op_inafectas;
    $op_exoneradas = $regc->op_exoneradas;
    $op_gratuitas = $regc->op_gratuitas;
    $isc = $regc->isc;
    $total_venta = $regc->total_venta;

    $rsptad = $factura->ventadetalle($id);
    $item = 0;
    ?>
    <!-- <Datos Empresa> -->
    <br><br>
    <div>
        <table class="razon_social">
            <!--  <tr><td> <img  style="width: 90%;" src="../files/perfil/<?php echo $logo; ?>" alt="Logo"></td></tr>  -->
            <tr>
                <td style="width: 95%"><?php echo $razon_social ?></td>
            </tr>
        </table>
        <table class="ruc">
            <tr>
                <td style="width: 95%">R.U.C.: <?php echo $rucp ?></td>
            </tr>
        </table>
        <table class="direccion">
            <tr>
                <td style="width: 95%;"><?php echo $direccion ?> <?php echo $distrito ?> - <?php echo $provincia ?> - <?php echo $departamento ?></td>
            </tr>
            <tr>
                <td style="width: 95%">Telef.: <?php echo $telefono; ?> Email.: <?php echo $email; ?></td>
            </tr>
        </table>
    </div>
    <table class="linea">
        <tr>
            <td style="padding-bottom: 7px">_______________________________________________________</td>
        </tr>
    </table>
    <!-- <Fin Datos Empresa> -->

    <!-- <Datos Comprobante> -->
    <!-- <Datos Comprobante> -->
    <table align="center" border="none" style="width: 100%;">
        <tr>
            <td align="center" style="font-weight:bold;"><?php echo $codigotipo_comprobante; ?></td>
        </tr>
        <tr>
            <td align="center"><?php echo $serie . ' - ' . $correlativo ?></td>
        </tr>
    </table>
    <table class="cliente">
        <tr>
            <td style="width: 15%;">FECHA</td>
            <td style="width: 1%;">:</td>
            <td style="text-align: left; width: 84%;"><?php echo $dia . ' - ' . $mes . ' - ' . $anno; ?> </td>
        </tr>
        <tr>
            <td style="width: 15%;">CLIENTE</td>
            <td style="width: 1%;">:</td>
            <td style="text-align: left; width: 84%;"><?php echo $cliente; ?></td>
        </tr>
        <tr>
            <td style="width: 15%;">R.U.C.</td>
            <td style="width: 1%;">:</td>
            <td style="text-align: left; width: 84%;"><?php echo $ruc; ?></td>
        </tr>
    </table>
    <table class="cliente">
        <tr>
            <td style="width:95%">CONDICION DE PAGO:&nbsp;<?php echo $codigotipo_pago; ?></td>

        </tr>
    </table>
    <table align="center" border="none" width="95%">
        <tr>
            <td>---------------------------------------------------------------------------------</td>
        </tr>
    </table>
    <!-- <Fin Datos Comprobante> -->
    <!-- <Articulos> -->
    <div class="articulos">
        <table style="width: 95%;">
            <tbody class="cuerpoM">
                <tr>
                    <td style="width: 57%; text-align:center">Descripción</td>
                    <td style="width: 10%; text-align:center">Cant.</td>
                    <td style="width: 15%; text-align: center;">P. Unit.</td>
                    <td style="width: 20%; text-align: center;">Importe</td>
                </tr>
            </tbody>
        </table>
    </div>
    <table align="center" border="none" width="95%">
        <tr>
            <td>---------------------------------------------------------------------------------</td>
        </tr>
    </table>
    <div class="articulos">
        <table style="width: 95%;">
            <tbody class="cuerpoM">
                <?php
                $cantidad = 0;
                while ($regd = $rsptad->fetch_object()) {
                    $item += 1;
                    if ($item % 2 == 0) {
                        $estilo = 'silver';
                    } else {
                        $estilo = 'clouds';
                    }
                    $newValoU = $regd->precio_venta / 1.18;
                    $newValorU = round($newValoU, 2);
                    $preciov = $regd->precio_venta;
                    $totalpv = $regd->precio_venta * $regd->cantidad;
                ?>
                    <tr>
                        <td style="width:57%;"><?php echo $regd->servicio . " " . $regd->serie; ?></td>
                        <td style="width:10%; text-align: center;"><?php echo $regd->cantidad;  ?></td>
                        <td style="width:15%; text-align: right;"><?php echo number_format($preciov, 2, '.', ','); ?></td>
                        <td style="width:20%;  text-align: right;"><?php echo number_format($totalpv, 2, '.', ','); ?>&nbsp;&nbsp;</td>
                    </tr>
            </tbody>
        </table>
        <table style="width: 95%;">
            <tbody class="cuerpoM">
            <?php
                    $cantidad += $regd->cantidad;
                } ?>
            <tr style="text-align: right">
                <td style="width: 48%;"></td>
                <td style="width: 25%;">Op. gravada:</td>
                <td style="width: 2%">S/</td>
                <td style="width: 25%"><?php echo number_format($op_gravadas, 2, '.', ','); ?></td>
            </tr>
            <tr style="text-align: right">
                <td style="width: 48%;"></td>
                <td style="width: 25%;">IGV (<?php echo $regc->igv_asig; ?>%):</td>
                <td style="width: 2%;">S/</td>
                <td style="width: 25%"><?php echo number_format($total_igv, 2, '.', ','); ?></td>
            </tr>
            <tr style="text-align: right">
                <td style="width: 48%;"></td>
                <td style="width: 25%;">Importe:</td>
                <td style="width: 2%;">S/</td>
                <td style="width: 25%"><?php echo number_format($total_venta, 2, '.', ','); ?></td>
            </tr>
            </tbody>
        </table>
        <table style="width: 90%;">
            <?php
            require_once "numeroALetras.php";
            $letras = NumeroALetras::convertir($total_venta);
            list($num, $cen) = explode('.', $total_venta);
            ?>
            <tr>
                <td style="width: 5%; text-align:center"></td>
                <td style="font-size: 9px; width: 100%;">SON: <?php echo $letras . ' Y ' . $cen; ?>/100 SOLES</td>
            </tr>
        </table>
    </div>
    <!-- <Fin Articulos> -->
    <!-- <Codigo> -->
    <table align="center" border="none" width="100%">
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>*******************************************************************</td>
        </tr>
        <tr>
            <td align="center">
                <!--    <?php
                        require "phpqrcode/qrlib.php";
                        $dir = 'temp_qrcode/';
                        if (!file_exists($dir)) {
                            mkdir($dir);
                        }
                        $filename = $dir . $rucp . '-0' . $regc->codigotipo_comprobante . '-' . $serie . '-' . $correlativo;
                        $tamaño = 2; //Tamaño de Pixel
                        $level = 'Q'; //Precisión Baja
                        // L = Baja
                        // M = Mediana
                        // Q = Alta
                        // H= Máxima
                        $framSize = 1; //Tamaño en blanco
                        $contenido = $rucp . '|0' . $regc->codigotipo_comprobante . '|' . $serie . '|' . $correlativo . '|' . $total_igv . '|' . $total_venta . '|' . $fecha . '|' . $tipo_documento_cliente . '|' . $ruc . '|'; //Texto
                        //Enviamos los parametros a la Función para generar código QR 
                        QRcode::png($contenido, $filename, $level, $tamaño, $framSize);
                        //Mostramos la imagen generada
                        echo '<img src="' . $dir . basename($filename) . '" />';
                        ?> -->
            </td>
        </tr>
        <tr>
            <td align="center">¡GRACIAS POR SU COMPRA</td>
        </tr>
        <tr>
            <td align="center">¡¡¡ VUELVA PRONTO !!!</td>
        </tr>
    </table>
    <!-- <Fin Codigo> -->

</page>