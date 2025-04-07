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
                <td style="width: 20%"> <img src="../files/perfil/<?php echo $logo; ?>" style="max-width: 200px;"></td>
                <td style="width: 45%; text-align: center"> <br>
                    <h4 align="center">REGISTRO DE CLIENTES</h4>
                </td>
                <td style="width: 30%; text-align: center; font-size:12px;"><br>FECHA DE IMPRESIÓN <br><?php
                                                                                        setlocale(LC_ALL, "es_ES");
                                                                                        echo $dia = date('d') . '-' . date('m') . '-' . date('Y'); ?></td>
            </tr>
        </table>
    </div>
    <br>

    <table border="1" cellpadding="1" cellspacing="1" style="width: 100%; padding-left: 10px;">
        <tr style="text-align: center">
            <th style=" width: 140; height: 10">CLIENTE</th>
            <th width="53">DOCUMENTO</th>
            <th width="53" >NUMERO DE DOCUMENTO</th>
            <th width="57">TELEFONO</th>
            <th width="175">EMAIL</th>
            <th width="120">RAZON SOCIAL</th>
        </tr>
        <?php
        $sql = "SELECT * FROM persona where tipo_persona in ('Cliente','Cliente Servicio') ORDER BY razon_social ASC";
        require_once "../modelos/Persona.php";
        $cliente = new Persona();
        $rpta = $cliente->listarcs();

        while ($reg = $rpta->fetch_assoc()) {
        ?>
            <tr style="margin: 20px; padding: 20px;">
                <td align="center" width="140"><?php echo $reg['nombre']; ?></td>
                <td align="center" width="53"><?php echo $reg['tipo_documento']; ?></td>
                <td align="center" width="53"><?php echo $reg['num_documento']; ?></td>
                <td align="center" width="57"><?php echo $reg['telefono']; ?></td>
                <td align="center" width="175"><?php echo $reg['email']; ?></td>
                <td align="center" width="120"><?php echo $reg['razon_social']; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
    <br>
</body>

</html>