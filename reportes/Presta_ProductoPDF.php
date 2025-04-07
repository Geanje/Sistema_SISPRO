<!DOCTYPE html>
<html>

<head>
    <title>Reporte Kardex</title>
    <style type="text/css">
        table {
            color: black;
            width: 100%;
            border: none;
            border-collapse: collapse;
            font-size: 10px;
            /* Tamaño de fuente reducido */
        }

        .cliente {
            padding-left: 5px;
            /* Reducción del padding */
            padding-right: 5px;
            /* Reducción del padding */
        }

        th,
        td {
            padding: 5px;
            /* Reducción del padding */
        }
    </style>
</head>

<body>
    <?php
    date_default_timezone_set('America/Lima');
    require_once "../modelos/Perfil.php";
    $perfil = new Perfil();
    $rspta = $perfil->cabecera_perfil();
    $reg = $rspta->fetch_assoc();
    $logo = $reg['logo'];
    ?>
    <br>
    <div class="cliente">
        <table>
            <tr>
                <td style="width: 25%"> <img src="../files/perfil/<?php echo $logo; ?>" style="max-width: 200px;"></td>
                <td style="width: 45%; text-align: center"> <br>
                    <h4 align="center">CONSULTA DE PRESTAMOS</h4>
                </td>
                <td style="width: 30%; text-align: center; font-size:12px;"><br>Fecha de impresión <br><?php
                                                                                                        setlocale(LC_ALL, "es_ES");
                                                                                                        echo $dia = date('d') . '-' . date('m') . '-' . date('Y'); ?></td>
            </tr>
        </table>
    </div>
    <br>

    <table border="1" cellpadding="1" cellspacing="1" style="width: 100%; padding-left: 5px;">
        <tr style="text-align: center">
            <th style="width: 45px; height: 10px">FECHA</th>
            <th width="60">USUARIO</th>
            <th width="170">SUCURSAL</th>
            <th width="80">N° COMPROBANTE</th>
            <th width="65">ARTICULO</th>
            <th width="45">CANTIDAD</th>
            <th width="40">TOTAL VENTA</th>
            <th width="60">ESTADO</th>
        </tr>
        <?php
        $json_data = file_get_contents('../reportes/temp_rptjson/prestamos-productos.json');
        $data = json_decode($json_data, true);
        if ($data === null) {
            die('Error al decodificar el archivo JSON');
        }
        function strip_html_tags($text)
        {
            return strip_tags($text);
        }
        foreach ($data as $item) {
            echo '<tr>';
            echo '<td width="45" height="10">' . $item['0'] . '</td>';
            echo '<td width="60" align="center" >' . $item['1'] . '</td>';
            echo '<td width="170" align="center">' . $item['2'] . '</td>';
            echo '<td width="80" align="center">' . $item['3'] . '</td>';
            echo '<td width="65" align="center">' . $item['4'] . '</td>';
            echo '<td width="45" align="center">' . $item['5'] . '</td>';
            echo '<td width="40" align="center">' . $item['6'] . '</td>';
            echo '<td width="60" align="center">' . strip_html_tags($item['7']) . '</td>';
            echo '</tr>';
        }
        ?>
    </table>
    <br>
</body>

</html>