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
				<td style="width: 25%"> <img src="../files/perfil/<?php echo $logo; ?>" style="width: 200px;"></td>
				<td style="width: 45%; text-align: center"> <br>
					<h4 align="center">REGISTRO DE PROVEEDOR</h4>
				</td>
				<td style="width: 30%; text-align: center; font-size: 12px"><br>Fecha de impresión <br><?php
																						setlocale(LC_ALL, "es_ES");
																						echo $dia = date('d') . '-' . date('m') . '-' . date('Y'); ?></td>
			</tr>
		</table>
	</div>
	<br>

	<table border="1" cellpadding="1" cellspacing="1" style="width: 100%; padding-left: 5px;font-size: 10px">
		<tr style="text-align: center">			
			<th style="width: 200px; height: 30px">NOMBRE</th>
			<th width="110">DOCUMENTO</th>
			<th width="70">TELEFONO</th>
			<th width="200">DIRECCION</th>
			<th width="130">RAZON SOCIAL </th>
		</tr>
		<?php
		require_once "../modelos/Persona.php";
		$persona = new Persona();
		$rpta = $persona->listarp();
		while ($reg = $rpta->fetch_object()) { ?>


			<tr style=" margin: 20px; padding: 20px; font-size:12px;">				
				<td width="200"><?php echo $reg->nombre; ?></td>
				<td width="110"align="center" ><?php echo $reg->tipo_documento,'-',$reg->num_documento; ?></td>				
				<td width="70" align="center" ><?php echo $reg->telefono; ?> </td>
				<td width="200" align="center" ><?php echo $reg->direccion; ?> </td>		
				<td width="125" align="center" ><?php echo $reg->razon_social; ?> </td>

			</tr>
		<?php }

		?>


	</table>
	<br>
	<!-- <div align="center" class="t2" >
				Fecha: <?php
						setlocale(LC_ALL, "es_ES");
						echo $dia = date('d') . '-' . date('M') . '-' . date('Y'); ?>
			</div> -->
</body>

</html>