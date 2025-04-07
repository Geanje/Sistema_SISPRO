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
                    <h4 align="center">PRESTAMOS DE PRODUCTOS</h4>
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
            <th style="width: 85px; height: 15px">FECHA</th>
            <th width="180">SUCURSAL</th>
            <th width="90">USUARIO</th>
            <th width="100">NUMERO</th>
            <th width="75">PRECIO</th>
            <th width="75">ESTADO</th>
        </tr>

    </table>
    <br>
</body>

</html>