<?php
if (strlen(session_id()) < 1)
	session_start();
date_default_timezone_set('America/Lima');
// En Windows
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
			padding-left: 20px;
			padding-right: 20px;
		}

		.text {
			padding-left: 40px;
			padding-right: 40px;
			text-align: justify-all;
			line-height: 120%;
		}

		.text1 {
			padding-left: 40px;
			padding-right: 40px;
			padding-bottom: 10px;
			text-align: justify-all;
			line-height: 130%;
		}

		.text2 {
			padding-left: 50px;
			padding-right: 40px;
			padding-bottom: 10px;
			text-align: justify-all;
			line-height: 170%;
		}

		.info {
			width: 30%;
			color: #34495e;
			font-size: 12px;
			text-align: justify-all;
		}

		.factura {
			width: 80%;
			font-size: 23px;
			text-align: center;
		}

		.linea {
			padding-left: 20px;
			padding-right: 20px;
		}

		.cliente {
			padding-left: 40px;
			padding-right: 40px;
		}

		.articulos {
			padding-left: 40px;
			padding-right: 40px;
			font-size: 11px;
		}

		.cabecera {
			background: #000;
			color: white;
			font-size: 12px;
			padding-left: 20px;
			padding-right: 20px;
		}

		.foot {
			padding-left: 20px;
			padding-right: 20px;
			font-size: 8pt;
		}

		.title {
			font-size: 15px;
			text-align: center;
		}

		.productos {
			font-size: 12px;
			border-collapse: collapse;
			padding-left: 20px;
			padding-right: 20px;
		}

		.silver {
			background: white;
			padding: 3px 4px 3px;
		}

		.clouds {
			background: #ecf0f1;
			padding: 3px 4px 3px;
		}

		.borde {
			border: solid 0.3px #000;
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
	$rucp = $reg['ruc'];
	$razon_social = $reg['razon_social'];
	$direccion = $reg['direccion'];
	$distrito = $reg['distrito'];
	$provincia = $reg['provincia'];
	$departamento = $reg['departamento'];
	$telefono = $reg['telefono'];
	$email = $reg['email'];
	$logo = $reg['logo'];

	require_once "../modelos/Ingreso.php";
	$id = $_GET["idingreso"];
	$ingreso = new Ingreso();
	$rspta_head = $ingreso->mostrar($id);
	$rspta_det = $ingreso->listarDetalle($id);
	$total = 0;
	?>

	<!-- Footer -->
	<page_footer>
		<table>
			<tr>
				<td>
					<img style="width: 100%;" src="../files/perfil/footer.png">
				</td>
			</tr>
		</table>
	</page_footer>

	<form action>
		<input type="hidden" name="rucempresa">
		<input type="hidden" name="seriecompro">
		<input type="hidden" name="correlativocompro">
	</form>
	<br>
	<!-- Header-->
	<div class="header">
		<table style="width: 100%;">
			<tr>
				<th style="width: 35%">
					<img style="height: 75px" src="../files/perfil/cabecera.png"> <br><br>
					<span style="color:black; font-size:16px;font-weight:bold"></span>
				</th>
				<th class="factura" style="width:60%">
					<?php echo $razon_social; ?><br>
					<p class="title">
						Dirección: <?php echo $direccion; ?> <br>
						Telef.: <?php echo $telefono; ?> <br>
						Email: <?php echo $email; ?>
					</p>
				</th>
			</tr>
		</table>
	</div>
	<div class="linea">
		<hr>
	</div>

	<!-- Datos -->
	<?php
	// Se obtiene la cabecera de ingreso
	$rspta_head = $ingreso->mostrar($id);
	?>
	<p style="text-align:center"><b> REPORTE DE INGRESO</b></p>
	<div class="cliente">
		<table style="width: 100%;">
			<tr>
				<td><b>Registro</b> </td>
				<td style="width: 60%">: N° <?php echo $rspta_head["idingreso"]; ?></td>
			</tr>
			<tr>
				<td><b>Proveedor</b></td>
				<td style="width: 60%">: <?php echo $rspta_head["proveedor"]; ?></td>
			</tr>
			<tr>
				<td><b>Fecha Emisión</b></td>
				<td>: <?php echo strftime("%d de %B del %Y", strtotime($rspta_head["fecha"])); ?></td>
			</tr>
		</table>
	</div>
	<br>
	<!-- Contenido -->
	<div class="productos" style="width: 100%">
		<table style="border: solid 0.3px #000;">
			<tr class="cabecera" style="width: 100%; text-align: center">
				<th style="width: 10%; text-align:center">CODIGO</th>
				<th style="width: 55%; text-align:center">DESCRIPCION DE LOS PRODUCTOS</th>
				<th style="width: 10%; text-align:center">CANTI.</th>
				<th style="width: 13%; text-align: center;">P. UNIT</th>
				<th style="width: 13%; text-align: center;">P. PARCIAL</th>
			</tr>
		</table>
		<table style="BORDER CELLPADDING=10 CELLSPACING=0">
			<?php
			while ($reg_det = $rspta_det->fetch_object()) {
				$subtotal = $reg_det->precio_compra * $reg_det->cantidad;
				$total += $subtotal;
				?>
				<tr class="productos" style="width: 100%; text-align: center;">
					<th style="width: 10%; text-align:center; border: solid 0.3px #000;"><?php echo $reg_det->codigo; ?></th>
					<td style="width: 55%; text-align:left; border: solid 0.3px #000;"><?php echo $reg_det->nombre . ' - ' . $reg_det->serie; ?></td>
					<td style="width: 10%; height: 15px; text-align:center; border: solid 0.3px #000;"><?php echo $reg_det->cantidad; ?></td>
					<td style="width: 13%; text-align: center; border: solid 0.3px #000;"><?php echo $reg_det->precio_compra; ?></td>
					<td style="width: 13%; text-align: center; border: solid 0.3px #000;"><?php echo number_format($subtotal, 2, '.', ','); ?></td>
				</tr>
			<?php } ?>
		</table>

		<!-- Total -->
		<br><br>
		<table style="border: solid 0.3px #000; width: 100%;">
			<tr class="productos" style="width: 100%; text-align: center;">
				<!-- Columna 1: Total a Pagar -->
				<td style="width: 88%; text-align: left; border: none">
					<strong>Total a Pagar:</strong>
				</td>

				<!-- Columna 2: Monto total -->
				<td style="width: 13%; text-align: center; border: none">
					<strong>S/. <?php echo number_format($total, 2, '.', ','); ?></strong>
				</td>
			</tr>
		</table>
	</div>

</body>

</html>