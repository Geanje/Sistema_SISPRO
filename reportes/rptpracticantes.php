<!DOCTYPE html>
<html>

<head>
    <title>Reporte Kardex</title>
    <style type="text/css">
        table {
            color: black;
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            /* Tamaño de fuente un poco más pequeño */
        }

        th,
        td {
            padding: 3px;
            /* Padding un poco más pequeño */
        }

        .logo-cell {
            width: 10%;
            /* Ancho de celda del logo reducido */
        }

        .title-cell {
            width: 50%;
            /* Ancho de celda del título reducido */
            text-align: center;
        }

        .date-cell {
            width: 40%;
            /* Ancho de celda de la fecha reducido */
            text-align: center;
        }

        img {
            max-width: 80px;
            /* Tamaño máximo del logo reducido */
        }

        /* Aumentar el ancho de las celdas de ESPECIALIDAD e INSTITUCION */
        .especialidad-cell {
            width: 60px;
        }

        .institucion-cell {
            width: 60px;
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
                <td style="width: 25%">
                    <img src="../files/perfil/<?php echo $logo; ?>" style="max-width: 200px;">
                </td>
                <td style="width: 45%;"><br>
                    <h4 align="center">Practicantes</h4>
                </td>
                <td style="width: 35%;font-size:12px;"><br>
                    Fecha de impresión<br>
                    <?php
                    setlocale(LC_ALL, "es_ES");
                    echo $dia = date('d') . '-' . date('m') . '-' . date('Y');
                    ?>
                </td>
            </tr>
        </table>
    </div>
    <br>

    <table border="1" cellpadding="1" cellspacing="1" style="width: 100%; padding-left: 5px; font-size:10px;">
        <tr style="text-align: center">
            <th width="100 height: 30px">NOMBRE Y APELLIDO</th>
            <th width="60" class="institucion-cell">INSTITUCION</th>
            <th width="30">DNI</th>
            <th width="40">SEDE</th>
            <th width="65" class="especialidad-cell">ESPECIALIDAD</th>
            <th width="80">MODALIDAD</th>
            <th width="40">CELULAR</th>
            <th width="40">CORREO</th>
            <th width="45">FECHA INICIO</th>
            <th width="45">FECHA TERMINO</th>
            <th width="40">ESTADO</th>
        </tr>
    </table>
    <br>
</body>

</html>