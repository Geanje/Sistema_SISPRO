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
		 	padding-left: 20px; 
		 	padding-right: 20px;
		 	font-size:14px;
		 }
      </style>
		
</head>
<body>
	<?php
date_default_timezone_set('America/Los_Angeles');

// $script_tz = date_default_timezone_get();

// if (strcmp($script_tz, ini_get('date.timezone'))){
//     echo ' ';
// } else {
//     echo ' ';
// }
?>
	<?php 
	require_once "../modelos/Perfil.php";
	$perfil=new Perfil();
	$rspta=$perfil->cabecera_perfil();
	$reg=$rspta->fetch_assoc();
	$logo=$reg['logo'];
	 ?>
	<br>
	<div class="cliente">
			<table >
				<tr>
					<td style="width: 30%"> <img src="../files/perfil/<?php echo $logo;?>" style="width: 250px;"></td>
					<td style="width: 40%; text-align: center"> <br><h4 align="center">REPORTE KARDEX</h4></td>
					<td style="width: 20%; text-align: center"><br>Fecha de impresión <br><?php 
				setlocale(LC_ALL,"es_ES");
				echo $dia=date('d').'-'.date('m').'-'.date('Y').'   '.date('H:i');?></td>
				</tr>
			</table>
	</div>
	<br>

	<table border="1" cellpadding="1" cellspacing="1" style="width: 100%; padding-left: 30px;" >
		<tr style="text-align: center" >
			<th style=" width: 60; height: 30">CODIGO</th>
			<th width="400">ARTICULO</th>
			<th width="65">INGRESO</th>
			<th width="65">SALIDA</th>
			<th width="80">STOCK </th>
		</tr>
		<?php 
			require_once "../modelos/Consultas.php";
			$consulta=new Consultas();
			$rpta=$consulta->kardex();
			while ($reg=$rpta->fetch_object()) {?>
			
			
		<tr style=" margin: 20px; padding: 20px;">
			<td align="center" ><?php echo $reg->codigo; ?></td>
			<td width="400"><?php echo $reg->nombre ?></td>
			<td align="center"><?php echo $reg->stock_ingreso; ?></td>
			<td align="center"><?php echo $reg->stock_salida; ?></td>
			<td  align="center"><?php echo $reg->stock; ?></td>
			
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