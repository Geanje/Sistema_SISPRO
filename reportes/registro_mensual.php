<!DOCTYPE html>
<html>
<head>
	<title>Reporte Kardex</title>
	<style type="text/css">
		  table {color:black; 
		  	widows: 100%;
		 	border: none;
            border-collapse: collapse;
            
          }
          .cliente{
		 	padding-left: 10px; 
		 	padding-right: 10px;
		 	font-size:12px;
		 }
      </style>
</head>
<body>
	<?php
date_default_timezone_set('America/Lima');
	require_once "../modelos/Perfil.php";
	$perfil=new Perfil();
	$rspta=$perfil->cabecera_perfil();
	$reg=$rspta->fetch_assoc();
	//$logo=$reg['logo'];
	 ?>
	<br>
	<div class="cliente">
    <br>Fecha de impresión <br><?php 
				setlocale(LC_ALL,"es_ES");
				echo $dia=date('d').'-'.date('m').'-'.date('Y');?>
			<table >
				<tr>
					<!-- <td style="width: 30%"> <img src="../files/perfil/<?php echo $logo;?>" style="width: 250px;"></td> -->
					<td style="width: 100%; text-align: center"> <br><h4 align="center">LISTA DE SERVICIOS</h4></td>
					<!-- <td style="width: 0%; text-align: center"></td> -->
				</tr>
			</table>

	</div>
	<br>

	<table border="1" cellpadding="1" cellspacing="1" style="width: 100%; padding-left: 10px;" >
		<tr style="text-align: center" >
			<th style=" width: 50; height: 25">CLIENTE</th>
			<th width="150">CONCEPTO</th>
			<!-- <th width="75">USUARIO</th> -->
			<th width="50">Direccion </th>
			<th width="70">MONTO DE PAGO</th>
		</tr>
		<?php 
			require_once "../modelos/Registro_servicios.php";
			$pagos=new Registro();
			$rpta=$pagos->servicioMensual();
			while ($reg=$rpta->fetch_object()) {?>
			
			
		<tr style=" margin: 20px; padding: 20px; font-size:12px;">
			<td align="center" width="10%"><?php echo $reg->cliente; ?></td>
			<td width="150"><?php echo $reg->concepto; ?></td>
			<!-- <td align="center" width="15%"><?php echo $reg->concepto; ?>   </td> -->
			<td align="center"width="379"><?php echo $reg->direccion; ?></td>
			<td align="center" width="10%"><?php echo number_format($reg->monto_pago,2,'.',','); ?>    </td>
			
		</tr>
			<?php }

			 ?>
			
		
	</table>
	<br>
	<!-- <div align="center" class="t2" >
				Fecha: <?php 
				setlocale(LC_ALL,"es_ES");
				echo $dia=date('d').'-'.date('M').'-'.date('Y');?>
			</div> -->
</body>
</html>