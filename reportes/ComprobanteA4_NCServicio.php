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
			font-size: 15px;
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
			width: 98%;
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
			height: 560px;
			width: 98.8%;
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
	</style>
</head>

<body>
	<?php
	require_once "../modelos/Perfil.php";
	$perfil = new Perfil();
	$rspta = $perfil->cabecera_perfil();
	// $rspta= Perfil::cabecera_perfil();
	$reg = $rspta->fetch_assoc();
	$rucp = $reg['ruc'];
	$razon_social = $reg['razon_social'];
	$direccion = $reg['direccion'];
	$distrito = $reg['distrito'];
	$provincia = $reg['provincia'];
	$departamento = $reg['departamento'];
	$fecha_inicio = $reg['fecha_inicio'];
	$telefono = $reg['telefono'];
	$email = $reg['email'];
	$direccion2 = $reg['direccion2'];
	$web = $reg['web'];
	$rubro = $reg['rubro'];
	$logo = $reg['logo'];

	// $conexion->close() 
	require_once "../modelos/NCServicio.php";
	$venta = new NServicio();
	$rsptac = $venta->ventacabecera($_GET["id"]);
	$regc = $rsptac->fetch_object();
	$cliente = $regc->cliente;
	$ruc = $regc->num_documento;
	if ($regc->codigotipo_comprobante == '1') {
		$codigotipo_comprobante = 'FACTURA  ELECTRÓNICA';
	} else  if ($regc->codigotipo_comprobante == '3') {
		$codigotipo_comprobante = 'BOLETA DE VENTA ELECTRÓNICA';
	} else  if ($regc->codigotipo_comprobante == '12') {
		$codigotipo_comprobante = 'TICKET DE VENTA';
	}
	$direccioncliente = $regc->direccion;
	$serie = $regc->serie;
	$correlativo = $regc->correlativo;
	$moneda = $regc->descmoneda;
	$fecha = $regc->fecha;
	$op_gravadas = $regc->op_gravadas;
	$op_inafectas = $regc->op_inafectas;
	$op_exoneradas = $regc->op_exoneradas;
	$op_gratuitas = $regc->op_gratuitas;
	$isc = $regc->isc;
	$motivo = $regc->motivo;
	$sustento = $regc->sustento;
	$doc_relacionado = $regc->doc_relacionado;
	$serie_rela = $regc->serie_rela;
	$correlativo_rela = $regc->correlativo_rela;

	$total_venta = $regc->total_venta;
	// close();
	// $doc_rela= $venta->ventacabecera($doc_relacionado);
	// $regdoc=$doc_rela->fetch_object();
	// $correlativo_docrela=$regdoc->correlativo;
	// $serie_docrela=$regdoc->serie;
	// $correlativo_docrela=$regdoc['correlativo'];
	$rsptad = $venta->ventadetalle($_GET["id"]);
	$item = 0;
	$sumdescuento = 0.00;
	$sumigv = 0.00;
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
				<th style="width: 55%; text-align: center; ">
					<!-- <img  style="height: 80px" src="../files/perfil/logo.jpg" alt="Logo"> -->
					<img style="width: 90%;" src="../files/perfil/<?php echo $logo; ?>" alt="Logo">
					<p class="razon-social"><?php echo $razon_social; ?></p>
					<p class="info-empresa"><!-- <?php echo $rubro; ?> --></p>
				</th>
				<th style="width: 40%; text-align: center; padding-top: 5px " class="factura">
					<p>
						RUC: <?php echo $rucp; ?><br><br>
						<b>NOTA DE CRÉDITO ELECTRÓNICA </b><br><br>
						<?php echo $serie . '-' . $correlativo; ?><br><br>
					</p>
				</th>
				<th style="width: 3%; text-align: center; padding-top: 5px "></th>
			</tr>
		</table>
	</div>
	<br>
	<div class="direcion-empresa">
		<table style="width: 100%">
			<tr>
				<td style="width: 90%">
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
	<!--  Fin Header -->
	<!--  Cliente-->
	<div class="cliente">
		<table class="cuadro-cliente">
			<tr>
				<td style="width: 13%"><b>FECHA</b></td>
				<td style="width: 87%">
					: <?php echo strftime("%d de %B del %Y", strtotime($fecha)); ?></td>
			</tr>
		</table>
	</div>
	<br>
	<div class="cliente">
		<table style="width: 100%">
			<tr>
				<td style="width: 5%"></td>
				<td style="width: 95%"><b>DOCUMENTO QUE MODIFICA:</b></td>
			</tr>
		</table>
	</div>
	<br>
	<div class="cliente">
		<table class="cuadro-cliente">
			<tr>
				<td>
					<table style="width: 99.4%">
						<tr>
							<td style="width: 30%"><b>COMPROBANTE ELECTRÓNICO</b></td>
							<td style="width:70%">: <?php echo $serie_rela . '-' . $correlativo_rela; ?></td>
						</tr>
					</table>
					<table style="width: 99.4%">
						<tr>
							<td style="width: 20%"><b>TIPO NOTA CRÉDITO</b></td>
							<td style="widt: 80%">: <?php echo $motivo; ?></td>
						</tr>
						<tr>
							<td style="width: 20%"><b>CLIENTE</b></td>
							<td style="width: 80%">: <?php echo $cliente; ?></td>
						</tr>
						<tr>
							<td style="width: 20%"><b>R.U.C.</b></td>
							<td style="width: 80%">: <?php echo $ruc; ?> </td>
						</tr>
						<tr>
							<td style="width: 20%"><b>MONEDA</b></td>
							<td style="width: 80%">: <?php echo $moneda; ?> </td>
						</tr>
						<tr>
							<td style="width: 20%"><b>SUSTENTO</b></td>
							<td style="width: 80%">: <?php echo strtoupper($sustento); ?> </td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
	<!-- Fin Cliente-->
	<!--  Descripcion del Comprobante -->
	<br>
	<div class="contenido">
		<table class="cabecera">
			<tr>
				<th style="width: 67%; text-align: center; height: 12px; padding-top: 5px ">DESCRIPCIÓN</th>
				<th style="width: 7%; text-align: center; padding-top: 5px ">CAT.</th>
				<th style="width: 13%; text-align: center; padding-top: 5px ">P. UNIT.</th>
				<th style="width: 13%; text-align: center; padding-top: 5px ">MPORTE</th>
			</tr>
		</table>
		<table class="cuadro-contenido">
			<tr>
				<td class="borde-contenido">
					<table class="articulo" border="0.1" cellpadding="0" cellspacing="1" bordercolor="black" style="border-collapse:collapse;">
						<?php while ($regd = $rsptad->fetch_object()) {
							$item += 1;
							if ($regd->unidad_medida == 'otros') {
								$medida = $regd->descripcion_otros;
							} else {
								$medida = $regd->unidad_medida;
							}

							$sumdescuento += $regd->descuento;
							// $sumigv+=(($regd->precio_venta/1.18)*$regd->cantidad)*0.18;


							if ($regd->afectacion == 'Exonerado') {
								$newValorU = $regd->precio_venta;
							} else if ($regd->afectacion == '') {
								$newValoU = $regd->precio_venta / 1.18;
								$newValorU = round($newValoU, 2);
								$newigv=  ($regd->cantidad*$regd->precio_venta/1.18-$regd->descuento)*0.18;
							}
							$sumigv+=$newigv;



							if ($item % 2 == 0) {
								$estilo = 'silver';
							} else {
								$estilo = 'clouds';
							}
						?>

							<tr style="text-align:left">
								<td style="width:67.8%; height: 1px; padding-top: 5px; text-align: justify; padding: 5px" rowspan="&"><?php echo $regd->articulo . ' ' . $regd->serie; ?> </td>
								<td style="width:7%; padding-top: 5px; text-align: center;"><?php echo $regd->cantidad; ?></td>
								<td style="width:13%; padding-top: 5px; text-align: right;"><?php echo $regd->precio_venta; ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
								<td style="width:13%; padding-top: 5px; text-align: right;"><?php $subTotal =  $regd->precio_venta * $regd->cantidad - $regd->descuento;
																							echo number_format($subTotal, 2); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>

							</tr>
						<?php } ?>
						<br>
					</table>
				</td>
			</tr>
		</table>
	</div>
	<div class="total">
		<?php
		require_once "numeroALetras.php";
		$letras = NumeroALetras::convertir($total_venta);
		list($num, $cen) = explode('.', $total_venta);
		?>
		<br><br>
		<table cellspacing="0" cellpadding="0" border="0.2">
			<tr class="articulos" style="width: 100%; text-align: center">
				<b>
					<td style="text-align: center; width:12%">OP. <br>GRABADA</td>
					<td style="text-align: center; width:12%">OP. <br>GRATUITA</td>
					<td style="text-align: center; width:12%">OP.<br> EXONERADA</td>
					<td style="text-align: center; width:12%">OP. <br>INAFECTA</td>
					<td style="text-align: center; width:12%">DESCTO <br>TOTAL </td>
					<td style="text-align: center; width:12%">IGV <br>(18%)</td>
					<td style="text-align: center; width:12%">PRECIO <br>TOTAL</td>
				</b>
			</tr>
			<tr style="width: 100%; text-align: center;">
				<b>
					<td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; <?php echo number_format($op_gravadas, 2, '.', ','); ?></td>
					<td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; 00.00</td>
					<td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; 00.00</td>
					<td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; 00.00</td>
					<td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; 00.00</td>
					<td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; <?php echo round($sumigv, 2); ?></td>
					<td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $total_venta; ?></td>
				</b>
			</tr>
		</table>
		<br>
		<table style="border: solid 0.2px black; ">
			<tr style="width: 80%;">
				<td style=" width:92%; height: 14px;">SON: <b><?php echo $letras . ' Y ' . $cen; ?>/100 SOLES</b></td>
			</tr>
		</table>
	</div>
	<page_footer>
		<div class="foot">
			<table cellspacing="0" cellpadding="0" border="0.2">
				<tr class="cuadro-footer">
					<td style="width: 100%; padding-top: 5px">
						<br>
						Representación impresa de la FACTURA ELECTRONICA,mitida del sistema del contribuyente autorizado con fecha
						<b><?php echo strftime("%d de %B del %Y", strtotime($fecha_inicio)); ?></b><br>
						Puede consultar su comprobante electrónico utilizando su clave SOL, en la plataforma de SUNAT. <?php echo $web; ?>
						<br>
					</td>

				</tr>
			</table>
		</div>
	</page_footer>
</body>

</html>