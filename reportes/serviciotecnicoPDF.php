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
                <td style="width: 15%"> <img src="../files/perfil/<?php echo $logo; ?>" style="width: 200px;"></td>
                <td style="width: 45%; text-align: center"> <br>
                    <h4 align="center">REGISTRO DE SERVICIOS DE SOPORTE</h4>
                </td>
                <td style="width: 50%; text-align: center, font-size:12px;"><br>Fecha de impresión <br><?php
                                                                                        setlocale(LC_ALL, "es_ES");
                                                                                        echo $dia = date('d') . '-' . date('m') . '-' . date('Y'); ?></td>
            </tr>
        </table>
    </div>
    <br>

    <table border="1" cellpadding="1" cellspacing="1" style="width: 100%; padding-left: 5px; font-size:10px;">
        <tr style="text-align: center">
            <th style=" width: 65px; height: 30px">FECHA</th>
            <th width="260">CLIENTE</th>
            <th width="75">TIPO EQUIPO</th>
            <th width="80">ESTADO SERVICIO</th>
            <th width="80">ESTADO ENTREGA</th>
            <th width="85">ESTADO PAGO</th>
            <th width="80">TECNICO</th>
        </tr>
    </table>
    <br>
    <!-- <div align="center" class="t2" >
				Fecha: <?php
                        setlocale(LC_ALL, "es_ES");
                        echo $dia = date('d') . '-' . date('M') . '-' . date('Y'); ?>
			</div> -->
</body>

</html>