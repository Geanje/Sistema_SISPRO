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
                <td style="width: 30%"> <img src="../files/perfil/<?php echo $logo; ?>" style="max-width: 200px;"></td>
                <td style="width: 40%; text-align: center"> <br><br>
                    <h4 align="center">VENTAS POR BOLETA Y FACTURA</h4>
                </td>
                <td style="width: 20%; text-align: center"><br>Fecha de impresión <br><?php
                                                                                        setlocale(LC_ALL, "es_ES");
                                                                                        echo $dia = date('d') . '-' . date('m') . '-' . date('Y'); ?></td>
            </tr>
        </table>
    </div>
    <br>

    <table border="1" cellpadding="1" cellspacing="1" style="width: 100%; padding-left: 10px;">
        <tr style="text-align: center">
            <th style="width: 70px">FECHA</th>
            <th width="200">CLIENTE</th>
            <th width="70">USUARIO</th>
            <th width="70">DOCUMENTO</th>
            <th width="60">NUMERO</th>
            <th width="60">TOTAL VENTA</th>
            <th width="60">ESTADO</th>
        </tr>
        <?php
        // Conecta a la base de datos y ejecuta la consulta SQL
        require_once "../modelos/Venta2.php";
        $venta = new Venta2();
        $rpta = $venta->listar();

        while ($reg = $rpta->fetch_assoc()) {
        ?>
            <tr style="margin: 20px; padding: 20px;">
                <td align="center" style="width: 70px"><?php echo $reg['fecha']; ?></td>
                <td align="center" width="200"><?php echo $reg['cliente']; ?></td>
                <td align="center" width="70"><?php echo $reg['usuario']; ?></td>
                <td align="center" width="12%"><?php echo $reg['codigotipo_comprobante']; ?></td>
                <td align="center" width="12%"><?php echo $reg['serie'] . '-' . $reg['correlativo']; ?></td>
                <td align="center" width="12%"><?php echo $reg['total_venta']; ?></td>
                <td align="center" width="12%"><?php echo $reg['estado']; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
    <br>
</body>

</html>