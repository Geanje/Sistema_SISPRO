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
	<title>Comprobante de servicio</title>
	<!-- <link rel="stylesheet" type="text/css" href="normalize.css"> -->
	<style type="text/css">
		table {
			color: black;
			border: none;
			width: 100%;
		}

		.header {
			padding-left: 15px;
			padding-right: 15px;
		}

		.text {
			padding-left: 20px;
			padding-right: 20px;
			font-size: 10px;
			/*padding-bottom: : 10px;*/
			text-align: justify-all;
			line-height: 120%;
			margin-top: -2px;
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
			width: 28%;
			height: 10px;
			border: 1px solid red;
			text-align: center;
			border-collapse: separate;
			border-spacing: 10;
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
			margin-top: 10px;
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
			padding-left: 10px;
			padding-right: 10px;
			font-size: 11px;
			margin-top: -10px;

		}

		.cuadro-cliente {
			border-collapse: separate;
			border-spacing: 10;
			border: 1px solid black;
			border-radius: 6px;
			-moz-border-radius: 20px;
			padding: 3px;
			width: 88.5%;
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
			font-size: 9px;
			height: 50px;
			margin-top: -10px;
			width: 98%;
			margin-left: -10px;
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
			width: 96%;
			margin-top: 10px;
		}

		.cuadro-contenido {
			border: 1px #1D1B1B;
			margin-top: -1px;
			width: 100%;
			padding-left: 0px;
			margin-left: 0px;
		}

		.borde-contenido {
			height: 80px;
			width: 95.5%;
			padding-left: 1px;
			padding-right: -1px;
		}

		.articulo {
			border-collapse: separate;
			margin-top: 2px;
			width: 100%;
			margin-left: -3px;
			margin-right: -10px;
		}

		.total {
			padding-left: 35px;
			padding-right: 50px;
			font-size: 9px;
			font-weight: bold;
			padding-top: -5px;
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
			padding-top: -35px;
		}

		.cuadro-footer {
			width: 96%;
			text-align: center;
			padding-top: -5px;
		}

		.aviso {
			font-size: 10pt;
			margin-left: 10px;
			margin-right: 10px;
			text-align: justify;
			padding: 20px;
			padding-top: 10px;
			padding-bottom: 10px;
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
			border-color: #087DA2;
		}

		.fechas {
			padding-bottom: -5px;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			/* padding-left: 45px; */
			padding-left: -20px;
			padding-right: 20px;

			width: 75%;
		}

		.header {
			font-size: 10px;
		}
	</style>
</head>

<body>
	<?php
	require_once "../modelos/Perfil.php";
	$perfilp = new Perfil();
	$rspta = $perfilp->cabecera_perfil();
	// $rspta= Perfil::cabecera_perfil();
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

	require_once "../modelos/Pago_servicios.php";
	$pagop = new Pagos();
	$rsptap = $pagop->comprobantePDF($idcomprobante);
	$regp = $rsptap->fetch_object();
	$cliente = $regp->cliente;
	$fecha_emision = $regp->fecha_emision;
	$fecha_termino = $regp->fecha_termino;
	$fecha_corte = $regp->fecha_corte;
	$periodo = $regp->periodo;
	$serie = $regp->serie;
	$correlativo = $regp->correlativo;
	$costo = $regp->costo;
	$estado = $regp->estado;
	$direccioncliente = $regp->direccion;
	$telefono = $regp->telefono;
	$num_documento = $regp->num_documento;
	$tipo_documento = $regp->tipo_documento;
	$correo = $regp->correo;
	$usuario = $regp->usuario;

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
				<th style="width: 56%; text-align: center; padding-bottom: -5px; padding-top: 20px; padding-left: -35px;">
					<img style="width: 80%;" src="../files/perfil/logo.png" alt="Logo">
				</th>
				<th style="width: 40%; text-align: center; padding-top: 5px; font-size: 11px;" class="factura">
					<span>
						<p>R.U.C. <?php echo $rucp; ?></p>
						<?php echo $serie . ' - ' . $correlativo ?><br><br>
					</span>
				</th>
				<th style="width: 3%; text-align: center; padding-top: 5px "></th>
			</tr>
		</table>
	</div>
	<br>
	<div class="direcion-empresa">
		<table style="width: 100%">
			<tr>
				<td style="width: 90%; font-size: 10px;">
					Dirección: <?php echo $direccion; ?> - <?php echo $distrito; ?> - <?php echo $provincia; ?><br>
					Telef: <?php echo $telefono; ?> Email: <?php echo $email; ?><br>
					Dirección: <?php echo $direccion; ?><br>
					<!-- Sucursal:  <?php echo $direccion2; ?><br>
	        	Web: <?php echo $web; ?>  &nbsp;&nbsp; -->
				</td>
			</tr>
		</table>
	</div>
	<br>
	<!--  Fin Header -->
	<!--  Cliente-->
	<div class="cliente">
		<table class="cuadro-cliente" style="font-size: 8px;">
			<tr>
				<td style="width: 20%"><b>CLIENTE</b></td>
				<td style="width: 70%">: <?php echo $cliente; ?></td>
			</tr>
			<!-- <tr><td style="width: 10%"><b>R.U.C./ D.N.I :</b></td><td style="width: 85%">: <?php echo $ruc; ?></td></tr> -->
			<tr>
				<td style="width: 20%"><b>DIRECCIÓN</b></td>
				<td style="width: 70%">:<?php echo $direccioncliente; ?> </td>
			</tr>
			<tr>
				<td style="width: 10%"><b>FECHA</b></td>
				<td style="width: 90%">
					: <?php echo strtotime("%d de %B del %Y", strtotime($fecha_emision)); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<b>Moneda</b> &nbsp;: Soles &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<b>Vendedor</b> &nbsp;: <?php echo $usuario; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</td>


			</tr>
			<!-- <tr><td><b>Forma de pago</b></td> &nbsp;: Efectivo</tr> -->
		</table>
	</div>
	<!-- Fin Cliente-->
	<!--  Descripcion del Comprobante -->
	<br>
	<div class="contenido">
		<table class="cabecera" style="font-size: 10px;">
			<tr>
				<!-- <th style="width: 9.05%; text-align: center; padding-top: 5px ">COIGO</th> -->
				<th style="width: 80%; text-align: center; height: 8px; padding-top: 4.8px ">CONCEPTO</th>
				<!-- <th style="width: 10%; text-align: center; padding-top: 5px ">MONTO.</th>
		    <th style="width: 13%; text-align: center; padding-top: 5px ">PERIODO.</th> -->
				<th style="width: 20%; text-align: center; height: 8px; padding-top: 4.8px ">MONTO</th>
			</tr>
		</table>
		<table class="cuadro-contenido">
			<tr>
				<td class="borde-contenido">
					<table class="articulo" border="0.1" cellpadding="0" cellspacing="1" bordercolor="black" style="border-collapse:collapse;">

						<?php
						$item = 1;
						if ($item % 2 == 0) {
							$estilo = '#B9EDDD';
						} else {
							$estilo = '#F0F0F0';
						}
						?>
						<tr style="text-align:left">
							<td style="background-color:<?php echo $estilo; ?>;; width:80%; height: 1px; padding-top: 5px; text-align: justify; padding: 5px" rowspan="&"><?php echo $regp->concepto . " " . $regp->serie; ?></td>
							<td style="background-color: <?php echo $estilo; ?>;; width:21.3%; padding-top: 5px; text-align: right;"><?php echo number_format($regp->costo, 2, '.', ','); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>

						</tr>


						<br>
					</table>
				</td>
			</tr>
		</table>
	</div>
	<div class="total">
		<br>
		<div class="fechas" id="fechas">
			<div class="fecha">
				<table cellspacing="0" cellpadding="0" border="0.2">
					<tr style="width: 90%; text-align: center">
						<td style="text-align: center; width:40%">FECHA EMISION</td>
						<td style="text-align: center; width:40%">FECHA VENCIMIENTO</td>
						<td style="text-align: center; width:40%">FECHA CORTE</td>
					</tr>
					<tr style="width: 90%; text-align: center;">
						<td style="width:40%">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $fecha_emision ?></td>
						<td style="width:25%">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $fecha_termino ?></td>
						<td style="width:25%">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $fecha_corte ?></td>
					</tr>
				</table>
			</div>

		</div>

		<br><br><br>
	</div>
	<div class="foot">
		<table cellspacing="0" cellpadding="0" border="0.2">
			<tr class="cuadro-footer">
				<td style="width: 100%; padding-top: 5px; font-size: 10px;">
					TOTAL DE VENTA: <b>&nbsp;&nbsp;&nbsp;S/&nbsp; <?php echo number_format($regp->costo, 2, '.', ','); ?></b>
					
					_________________________________________________________
					<p style="text-align: center;"> <b>¡¡¡ GRACIAS POR ELEGIRNOS !!!</b> <br>
						Representación impresa de la FACTURA ELECTRONICA<br>
						Emitida del sistema del contribuyente autorizado con fecha
						<b><?php echo strtotime("%d de %B del %Y", strtotime($fecha_emision)); ?></b><br>
						Puede consultar su comprobante electrónico utilizando su clave SOL, en la plataforma de SUNAT.<?php echo $web; ?>
					</p>
				</td>
				
			</tr>
		</table>
	</div>

</body>

</html>