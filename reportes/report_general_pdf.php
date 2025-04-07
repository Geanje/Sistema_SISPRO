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
                    <h4 align="center">HISTORIAL PRODUCTOS VENDIDOS</h4>
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
            <th style=" width: 180; height: 20">CLIENTE</th>
            <th width="90">COMPROBANTE</th>
            <th width="120">ARTICULO</th>
            <th width="150">SERIE </th>
            <th width="50">UNI. </th>
            <th width="60">PRECIO </th>
        </tr>
        <?php
        $json_data = file_get_contents('../reportes/temp_rptjson/consulta-ventas-genereral.json');
        $data = json_decode($json_data, true);
        if ($data === null) {
            die('Error al decodificar el archivo JSON');
        }
        foreach ($data as $item) {
            echo '<tr>';
            echo '<td width="180" height="10">' . $item[1] . '</td>';
            echo '<td width="90" align="center" >' . $item[2] . '</td>';
            echo '<td width="160">' . $item[3] . '</td>';
            echo '<td width="150">' . $item[4] . '</td>';
            echo '<td width="50" align="center">' . $item[5] . '</td>';
            echo '<td width="60" align="center">' . $item[6] . '</td>';
            echo '</tr>';
        }
        ?>
    </table>
    <br>
    <?php
    setlocale(LC_ALL, "es_ES");
    echo $dia = date('d') . '-' . date('M') . '-' . date('Y');
    ?>
</body>

</html>