<!DOCTYPE html>
<html>

<head>
    <title>Reporte Kardex</title>
    <style type="text/css">
        body {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin: 0 auto;
        }

        th,
        td {
            padding: 3px;
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
    <div>
        <table>
            <tr>
                <td style="width: 25%">
                    <img src="../files/perfil/<?php echo $logo; ?>" style="max-width: 200px;">
                </td>
                
                <td style="width: 45%;"><br>
                    <h4 align="center">PROFORMAS</h4>
                </td>
                <td style="width: 30%;font-size:12px;"><br>
                    Fecha de impresión: <?php echo date('d-m-Y'); ?>
                </td>
            </tr>
        </table>
    </div>
    <br>

    <div>
        <table border="1" cellpadding="1" cellspacing="1" style="width: 100%; padding-left: 10px; font-size:10px;">
            <tr style="text-align: center">
                <th style=" width: 50; height: 30px">FECHA</th>
                <th width="160">CLIENTE</th>
                <th width="60">USUARIO</th>
                <th width="60">NUMERO</th>
                <th width="60">TOTAL VENTA</th>
                <th width="60">ESTADO</th>
            </tr>
            <?php
            require_once "../modelos/Proforma.php";
            $proforma = new Proforma();
            $rpta = $proforma->listar();

            while ($reg = $rpta->fetch_assoc()) {
            ?>
                <tr style=" margin: 20px; padding: 20px; font-size:12px;">
                    <td><?php echo $reg['fecha']; ?></td>
                    <td width="240"><?php echo $reg['cliente']; ?></td>
                    <td><?php echo $reg['usuario']; ?></td>
                    <td><?php echo $reg['serie'] . '-' . $reg['correlativo']; ?></td>
                    <td><?php echo $reg['total_venta']; ?></td>
                    <td><?php echo $reg['estado']; ?></td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
    <br>
</body>

</html>