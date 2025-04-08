<!DOCTYPE html>
<html>
<head>
    <title>Reporte Kardex</title>
    <style type="text/css">
        table {
            color: black;
            width: 100%;
            border-collapse: collapse;
            font-size: 8px; /* Tamaño de fuente reducido */
        }

        th, td {
            padding: 4px; /* Reducción del padding */
        }

        .logo-cell {
            width: 20%; /* Reducción del ancho de la celda del logo */
        }

        .title-cell {
            width: 60%; /* Reducción del ancho de la celda del título */
            text-align: center;
        }

        .date-cell {
            width: 20%; /* Reducción del ancho de la celda de la fecha */
            text-align: center;
        }

        img {
            max-width: 80px; /* Reducción del tamaño del logo */
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
            <td class="logo-cell">
                <img src="../files/perfil/<?php echo $logo; ?>" style="max-width: 200px;">
            </td>
            <td style="width: 45%; text-align: center"><br>
                <h4>USUARIO</h4>
            </td>
            <td style="width: 30%; text-align: center; font-size:12px;">
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

<table border="1" cellpadding="1" cellspacing="1"style="width: 100%; padding-left: 5px; font-size:10px">
    <tr style="text-align: center">
        <th style=" width: 140px; height: 15px">NOMBRE</th>
        <th width="50">DOC.</th>
        <th width="50">NUMERO</th>
        <th width="50">TELEFONO</th>
        <th width="150">EMAIL</th>
        <th width="50">LOGIN</th>
        <th width="50">INCENTIVO</th>
        <th width="50">ESTADO</th>
    </tr>
</table>
<br>
</body>
</html>
