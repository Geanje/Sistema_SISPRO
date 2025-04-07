<?php
if (strlen(session_id()) < 1)
	session_start();
date_default_timezone_set('America/Lima');
// En windows
setlocale(LC_TIME, 'spanish');
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<title>Formulario Contacto</title>
	<style type="text/css">
		table {
			color: black;
			border: none;
			width: 100%;
		}

		.header {
			padding-left: 15px;
			padding-right: 15px;
			margin-top: -10px
		}

		.text {
			padding-left: 20px;
			padding-right: 20px;
			font-size: 15px;
			/*padding-bottom: : 10px;*/
			text-align: justify-all;
			line-height: 120%;
			margin-top: -2px
		}

		.text2 {
			padding-left: 50px;
			padding-right: 40px;
			padding-bottom: 10px;
			text-align: justify-all;
			line-height: 170%;
		}

		.factura {
			font-size: 16px;
			width: 20%;
			/* Modifica el ancho del cuadro */
			height: 10px;
			border: 1px solid red;
			margin: 0 auto;
			/* Centra horizontalmente */
			border-collapse: separate;
			border-spacing: 10px;
			border: 1px solid black;
			border-radius: 15px;
			-moz-border-radius: 20px;
			padding: 2px;
		}

		.razon-social {
			color: red;
			font-size: 13px;
			font-weight: bold;
			text-transform: uppercase;
			margin-top: 0px;
			padding-left: 20px;
		}

		.info-empresa {
			font-size: 9px;
			text-align: center;
			margin-top: -10px;
			font-weight: normal;
			text-transform: uppercase;
		}

		.direcion-empresa {
			width: 100%;
			font-size: 10px;
			text-align: left;
			padding-left: 30px;
			margin-top: 0px;
		}

		.rubro {
			color: black;
			font-size: 18px;
			font-weight: bold;
			text-transform: uppercase;
		}

		.linea {
			padding-left: 20px;
			padding-right: 20px;
		}

		.cliente {
			padding-left: 15px;
			padding-right: 15px;
			font-size: 10px;
			margin-top: -15px;
			border: 1px solid black;
			/* Agregando borde de 1px de ancho y color negro */
		}

		.cliente {
			padding-left: 15px;
			padding-right: 15px;
			font-size: 10px;
			margin-top: -15px;
			border-collapse: separate;
			border-spacing: 10;
			border: 1px solid black;
			border-radius: 6px;

		}

		.cuadro-cliente {
			border-collapse: separate;
			border-spacing: 10;
			border: 1px solid black;
			border-radius: 6px;
			-moz-border-radius: 20px;
			padding: 3px;
			width: 90%;
		}

		.pagos {
			text-align: center;
			display: table-cell;
			border: solid;
			border-width: thin;
			margin-top: -10px;
			width: 98%;

		}

		.contenido {
			padding-left: 25px;
			padding-right: 25px;
			margin-left: -10px;
			font-size: 9px;
			height: 50px;
			width: 98%;
			margin-top: -7px;
		}

		.cabecera {
			background: #1D1B1B;
			color: white;
			line-height: 65px;
			font-size: 12px;
			line-height: 65px;
			border-top-left-radius: 5px;
			border-top-right-radius: 10px;
			margin-bottom: -5px;
			width: 99.2%;
		}

		.cuadro-contenido {
			border: 1px #1D1B1B;
			margin-top: -1px;
			width: 100%;
			padding-left: 0px;
			margin-left: 0px;
		}

		.borde-contenido {
			height: 600px;
			width: 98.7%;
			overflow: auto;
			/* Agrega barras de desplazamiento */
		}

		.articulo {
			border-collapse: separate;
			margin-top: 2px;
			width: 100%;
			margin-left: -3px;
		}

		.total {
			padding-left: 35px;
			padding-right: 20px;
			font-size: 9px;
			font-weight: bold;

		}

		.precio {
			width: 40%;
			height: 10px;
			text-align: right;
		}

		.cuadro-precio {
			margin-left: 451.3px;
			margin-top: -1px;
		}

		.foot {
			padding-left: 20px;
			padding-right: 20px;
			font-size: 8pt;
			width: 98%;
		}

		.cuadro-footer {
			width: 98%;
			text-align: center;
		}

		.aviso {
			font-size: 9pt;
			margin-left: 10px;
			margin-right: 10px;
			text-align: justify;
			padding: 10px;
			padding-top: 5px;
			padding-bottom: 5px;
			border: solid 0.3px #000;
		}

		.nota {
			font-size: 10pt;
			margin-left: 10px;
			margin-right: 10px;
			text-align: justify;
			padding: 20px;
			padding-top: 10px;
			padding-bottom: 10px;
		}

		.silver {
			background: white;
			padding: 3px 4px 3px;
		}

		.clouds {
			background: #ecf0f1;
			padding: 3px 4px 3px;
		}

		.boder {

			border-collapse: collapse;
			border-color: black;
		}
	</style>
</head>

<body>
	<?php
	require_once "../modelos/Perfil.php";
	$perfil = new Perfil();
	$rspta = $perfil->cabecera_perfil();
	$reg = $rspta->fetch_assoc();
	$rucp = $reg['ruc'];
	$razon_social = $reg['razon_social'];
	$direccion = $reg['direccion'];
	$direccion2 = $reg['direccion2'];
	$distrito = $reg['distrito'];
	$provincia = $reg['provincia'];
	$departamento = $reg['departamento'];
	$fecha_inicio = $reg['fecha_inicio'];
	$telefono = $reg['telefono'];
	$email = $reg['email'];
	$web = $reg['web'];
	$rubro = $reg['rubro'];
	$logo = $reg['logo'];

	// $conexion->close() 
	require_once "../modelos/Cotizacion.php";
	$cotizacion = new Cotizacion();
	$rsptac = $cotizacion->ventacabecera($_GET["id"]);
	$regc = $rsptac->fetch_object();
	$idcotizacion = $regc->idcotizacion;
	$cliente = $regc->cliente;
	$tipoDoc = $regc->tipo_documento;
	$correlativo = $regc->correlativo;
	$numDoc = $regc->num_documento;
	$direccioncliente = $regc->direccion;
	$fecha = $regc->fecha;
	$referencia = $regc->referencia;
	$validez = $regc->validez;
	$igv_total = $regc->igv_total;
	$igv_asig = $regc->igv_asig;
	$total_venta = $regc->total_venta;
	$rsptad = $cotizacion->ventadetalle($_GET["id"]);
	$item = 0;
	?>
	<form action>
		<input type="hidden" name="rucempresa">
		<input type="hidden" name="seriecompro">
		<input type="hidden" name="correlativocompro">
	</form>

	<!--  Header -->
	<div class="header">
		<table style="width: 100%">
			<tr>
				<th style="width: 60%; text-align: center; ">
					<!-- <img  style="height: 80px" src="../files/perfil/logo.jpg" alt="Logo"> -->
					<img style="width: 80%;" src="../files/perfil/<?php echo $logo; ?>" alt="Logo">
					<p class="razon-social"><?php echo $razon_social; ?></p>
					<!-- <p class="info-empresa"><?php echo $rubro; ?></p> -->
				</th>
				<th style="width: 35%; text-align: center; padding-top: 1px " class="factura">
					<p>R.U.C. 10410697551<br><br>
                        COTIZACION<br><br>
						<!-- N° <?php echo $idcotizacion; ?><br><br>-->
						<?php echo 'C001 - ' . $correlativo ?><br><br>
					</p>
				</th>
				<th style="width: 3%; text-align: center; padding-top: 5px "></th>
			</tr>
		</table>
	</div>
	<br>
	<div class="direcion-empresa">
		<table style="width: 150%">
			<tr>
				<td style="width: 100%">
					Dirección: <?php echo $direccion; ?> - <?php echo $distrito; ?> - <?php echo $provincia; ?><br>
					Telef.: <?php echo $telefono; ?> Email.: <?php echo $email; ?><br>
					<!--  Dirección: <?php echo $direccion; ?><br> -->
					<!-- Sucursal:  <?php echo $direccion2; ?><br> -->
					<!-- Web: <?php echo $web; ?>  &nbsp;&nbsp;-->
				</td>
			</tr>
		</table>
	</div>
	<br>
	<br>

	<!--  Fin Header -->


	<!-- Datos del cliente -->
	<div class="cliente">
		<table style="width: 100%;">
			<tr>
				<td style="width: 13%;"><b>CLIENTE</b></td>
				<td style="width: 80%">: <?php echo strtoupper($cliente); ?></td>
			</tr>
			<tr>
				<td style="width: 13%;"><b>DIRECCIÓN</b></td>
				<td style="width: 80%;">: <?php echo strtoupper($direccioncliente); ?></td>
			</tr>
			<tr>
				<td style="width: 13%;"><b>REFERENCIA</b></td>
				<td style="width: 80%;">: <?php echo strtoupper($referencia); ?></td>
			</tr>
		</table>
		<table style="width: 100%;">
			<tr>
				<td style="width: 13%"><b>FECHA</b></td>
				<td style="width: 26%">: <?php echo strftime("%d de %B del %Y", strtotime($fecha)); ?></td>
				<td style="width: 16%"><b>Validez de la Oferta</b></td>
				<td style="width: 10%">: <?php echo $regc->validez; ?> días</td>
				<td style="width: 15%"><b>Tipo Cotización</b> </td>
				<td style="width: 10%">: <?php echo $regc->tipo_proforma; ?></td>
			</tr>
		</table>

	</div>
	<!-- Fin Datos del cliente -->
	<table style="width: 100%;">
		<tr>
			<td style="width: 97%">
				<hr style="border: solid 0.3px #000;">
			</td>


		</tr>
	</table>




	<!-- Presentenacion -->
	<p class="text">
		Por la presente reciban un cordial saludo de nuestra empresa <b> SOLUCIONES INTEGRALES JB SAC,</b> con N° <b>RUC.10410697551</b>, así como nuestro agradecimiento, con la finalidad de ponernos a su disposición y ser una alternativa de solución a los requerimientos de su representada.
	</p>
	<!-- Contenido -->
	<div class="productos">
		<table style="border: solid 0.3px #087DA2 ">
			<tr class="cabecera" style="width: 100%; text-align: center ">
				<th style="width: 5%;height: 15px; text-align:center">ITEM</th>
				<th style="width: 60%; text-align:center">DESCRIPCION</th>
				<th style="width: 5%; text-align:center">UND.</th>
				<th style="width: 11%; text-align: center;">PRECIO</th>
				<th style="width: 14%; text-align: center;">SUB TOTAL</th>
			</tr>
			<?php
			while ($regd = $rsptad->fetch_object()) {
				$item += 1;
				if ($item % 2 == 0) {
					$estilo = 'silver';
				} else {
					$estilo = 'clouds';
				}
			?>
				<tr style="text-align:center">
					<td class="<?php echo $estilo; ?>" style="width: 5%"><?php echo $item; ?></td>
					<td class="<?php echo $estilo; ?>" style="text-align:justify; width: 60%"><?php echo $regd->descripcion; ?></td>
					<td class="<?php echo $estilo; ?>" style="width: 5%"><?php echo $regd->cantidad; ?></td>
					<td class="<?php echo $estilo; ?>" style="text-align:right; width: 11%"><?php echo $regd->precio; ?></td>
					<td class="<?php echo $estilo; ?>" style="text-align:right; width: 14%"><?php echo $regd->subtotal; ?></td>
				</tr>
			<?php } ?>
		</table>
		<br><br>
		<table cellspacing="0" cellpadding="0" border="0.2" align="center">
			<tr style="width: 100%; text-align: left; border:0.2">
				<b>
					<td style="text-align: center; width:20%">SUB, TOTAL</td>
					<td style="text-align: center; width:20%">IGV (<?php echo $igv_asig ?>%)</td>
					<td style="text-align: center; width:20%">IMPORTE TOTAL</td>
				</b>
			</tr>
			<tr style="width: 100%; text-align: center;">
				<b>
					<td style="width:20%">S/&nbsp;&nbsp;&nbsp;&nbsp; <?php echo number_format(($total_venta - $igv_total), 2, '.', ','); ?></td>
					<td style="width:20%">S/&nbsp;&nbsp;&nbsp;&nbsp; <?php echo number_format($igv_total, 2, '.', ','); ?></td>
					<td style="width:20%">S/&nbsp;&nbsp;&nbsp;&nbsp; <?php echo number_format(($total_venta), 2, '.', ','); ?></td>
				</b>
			</tr>
		</table>
		<br>
		<?php
		require_once "numeroALetras.php";
		$letras = NumeroALetras::convertir($total_venta);
		list($num, $cen) = explode('.', $total_venta);
		?>
		<table>
			<tr style="width: 95%;">
				<td style=" width:90%; height: 14px;">SON:<b> <?php echo $letras . ' Y ' . $cen; ?>/100 SOLES</b></td><br>
				<hr style="border-color:#000;">
			</tr>
		</table>
	</div>
	<br>
	<!--  Medio de Pago-->
	<div class="aviso">
		<table style="width: 98%;">
			<tr>
				<td style="width: 95%;"><b>INSTRUCCIONES PARA PAGAR</b> </td>
			</tr>
			<tr>
				<td style="width: 98%;">
					Acercándose a una agencia u oficina del Banco, cajero automático, agente o transferencia por internet, (si el pago es de provincia considerar la comisión por plaza).
				</td>
			</tr>
		</table>
		<table style="width: 98%;">
			<tr>
				<td style="width: 28%;"><b>TITULAR DE LA CUENTA</b></td>
				<td style="width: 70%;">: WILDER FLORENTINO JULCA BRONCANO</td>
			</tr>
		</table>
		<table style="width: 98%;">
			<tr>
				<td style="width: 28%;"><b>CUENTA SOLES BCP</b></td>
				<td style="width: 25%;">: 191-34789343-0-48</td>
				<td style="width: 4%;"><b>CCI</b></td>
				<td style="width: 30%;">: 00219113478934304852</td>
			</tr>
			<!--<tr>-->
			<!--	<td style="width: 28%;"><b>CUENTA SOLES BBVA</b></td>-->
			<!--	<td style="width: 25%;">: 0011-0264-02-00083101</td>-->
			<!--	<td style="width: 4%;"><b>CCI</b></td>-->
			<!--	<td style="width: 30%;">: 011-264-000200083101-92</td>-->
			<!--</tr>-->
			<tr>
				<td style="width:28%;"><b>CUENTA DETRACCIÓN BN</b></td>
				<td style="width: 25%;">: 00363002463</td>
				<td style="width: 4%;"><b></b></td>
				<td style="width: 30%;">: </td>
			</tr>
		</table>
	</div>
	<!-- Fin Medio de Pago-->
</body>

</html>