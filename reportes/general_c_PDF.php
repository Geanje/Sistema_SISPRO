<!DOCTYPE html>
<html>

<head>
    <title>Reporte Kardex</title>
    <style type="text/css">
        table {
            color: black;
            widows: 100%;
            border: none;
            border-collapse: collapse;

        }

        .cliente {
            padding-left: 10px;
            padding-right: 10px;
            font-size: 12px;
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
                <td style="width: 25%"> <img src="../files/perfil/<?php echo $logo; ?>" style="width: 200px;"></td>
                <td style="width: 45%; text-align: center"> <br>
                    <h4 align="center">HISTORIA PRODUCTOS VENDIDOS</h4>
                </td>
                <td style="width: 30%; text-align: center; font-size: 12px;"><br>Fecha de impresión <br><?php
                                                                                                        setlocale(LC_ALL, "es_ES");
                                                                                                        echo $dia = date('d') . '-' . date('m') . '-' . date('Y'); ?></td>
            </tr>
        </table>
    </div>
    <br>

    <table border="1" cellpadding="1" cellspacing="1" style="width: 100%; padding-left: 5px; font-size: 10px;">
        <tr style="text-align: center">
            <th style=" width: 55; height: 30">FECHA</th>
            <th width="180">PROVEEDOR</th>
            <th width="90">COMPROBANTE</th>
            <th width="170">ARTICULO</th>
            <th width="70">CANTIDAD</th>
            <th width="70">SERIE</th>
            <th width="60">PRECIO</th>
        </tr>
        <?php
        $json_data = file_get_contents('../reportes/temp_rptjson/consulta-compras-general.json');
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
            echo '<td width="55" height="10">' . $item['0'] . '</td>';
            echo '<td width="180" align="center" >' . $item['1'] . '</td>';
            echo '<td width="80" align="center">' . $item['3'] . '</td>';
            echo '<td width="170" align="center">' . $item['4'] . '</td>';
            echo '<td width="65" align="center">' . $item['5'] . '</td>';
            echo '<td width="60" align="center">' . $item['6'] . '</td>';
            echo '<td width="70" align="center">' . strip_html_tags($item['7']) . '</td>';
            echo '</tr>';
        }
        ?>
    </table>
    <br>
    <!-- <div align="center" class="t2" >
				Fecha: <?php
                        setlocale(LC_ALL, "es_ES");
                        echo $dia = date('d') . '-' . date('M') . '-' . date('Y'); ?>
			</div> -->
</body>

</html>