<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Formulario Contacto</title>
	<!-- <link rel="stylesheet" type="text/css" href="normalize.css"> -->
	<style type="text/css">
		 table {color:black; }
		

		 .header{
		 	display: inline-block;
		 	padding-left: 20px; 
		 	padding-right: 20px; 

		 	
		 }
		 .info{
		 	width: 30%; 
		 	color: #34495e;
		 	font-size:12px;
		 	text-align:justify-all;
		 }
		 .factura{
		 	color: red;
		 	width: 28%;
		 	height:10px;
		 	border: 1px solid red;
		 	text-align: center;
		 }
		 .linea{
		 	padding-left: 20px; 
		 	padding-right: 20px; 

		 }
		 .cliente{
		 	padding-left: 20px; 
		 	padding-right: 20px;

		 }
		 .articulos{
		 	padding-left: 20px; 
		 	padding-right: 20px;
		 }
		 .foot{
		 	padding-left: 20px; 
		 	padding-right: 20px;
		 	font-size: 8pt;
		 }
		 .silver{
			background:white;
			padding: 3px 4px 3px;
		}
		.clouds{
			background:#ecf0f1;
			padding: 3px 4px 3px;
		}
	</style>
</head>
<body>
<?php 
	require_once "../modelos/Perfil.php";
	$perfil=new Perfil();
	$rspta=$perfil->cabecera_perfil();
	$reg=$rspta->fetch_assoc();
	$rucp=$reg['ruc'];
	$razon_social=$reg['razon_social'];
	$direccion=$reg['direccion'];
	$distrito=$reg['distrito'];
	$provincia=$reg['provincia'];
	$departamento=$reg['departamento'];
	$telefono=$reg['telefono'];
	$email=$reg['email'];
	$logo=$reg['logo'];
	// $conexion->close() 
	require_once "../modelos/Cotizacion.php";
	$venta=new Cotizacion();
	$rsptac= $venta->ventacabecera($_GET["id"]);
	$regc=$rsptac->fetch_object();
	$cliente=$regc->cliente;
	$ruc=$regc->num_documento;
	$direccioncliente=$regc->direccion;
	$serie=$regc->serie;
	$correlativo=$regc->correlativo;
	$moneda=$regc->descmoneda;
	$fecha=$regc->fecha;
	$op_gravadas=$regc->op_gravadas;
	$op_inafectas=$regc->op_inafectas;
	$op_exoneradas=$regc->op_exoneradas;
	$op_gratuitas=$regc->op_gratuitas;
	$isc=$regc->isc;
	$total_venta=$regc->total_venta;

	$rsptad= $venta->ventadetalle($_GET["id"]);
	$item=0;
	$sumdescuento=0.00;
	$sumigv=0.00;
 ?>

		<form action>
			<input type="hidden" name="rucempresa">
			<input type="hidden" name="seriecompro">
			<input type="hidden" name="correlativocompro">
		</form>
		<br>
		<div class="header">
			 <table style="width: 100%;">
	        	<tr>
		            <td colspan="2" style="width: 50%">
		                <img style="width: 100%;height: 70px" src="../files/perfil/<?php echo $logo;?>" alt="Logo"><br>
		                
		            </td>
					<td style="width: 13%"></td>
					<td class="factura" style="width:30%">
						<p>PROFORMA <br>
						RUC: <?php echo $rucp; ?> <br>
						<?php echo $serie.'-'.$correlativo; ?></p>
					</td>
	        	</tr>
	        	<tr class="info">
	        		<td >
		                <span style="color: #34495e;font-size:18px;font-weight:bold"><?php echo $razon_social; ?></span>
		            </td>
		            <td  style="width: 20%">
		            	
		            </td>
		            <td>
		            	
		            </td>
	        	</tr>
	        	<tr>
	        		<td><?php echo $direccion; ?></td>
	        		<td></td>
	        		<td></td>
	        		<td style="text-align: right;"></td>
	        	</tr>
	        	<tr>
	        		<td><?php echo $distrito; ?> - <?php echo $provincia; ?> - <?php echo $departamento; ?></td>
	        		<td>Celular: <?php echo $telefono; ?></td>
	        		<td></td>
	        		<td style="text-align: right;"><?php echo $email; ?></td>
	        	</tr>
   		 	</table>
		</div>

		<div class="linea">
			<hr>
		</div>

		<div class="cliente">
			<table style="width: 100%;">
				<tr style="">
					<?php 
						list($anno,$mes,$dia)=explode('-',$fecha)
						 ?>
						<td>Cliente: </td>
						<td style="width: 60%"><?php echo $cliente; ?></td>
						<td >Fecha Emisión: </td>
						<td><?php echo $dia.'-'.$mes.'-'.$anno; ?></td>

				</tr>
				<tr>
					<td>RUC:</td>
					<td><?php echo $ruc; ?></td>
				</tr>
				<tr>
					<td>Dirección:</td>
					<td><?php echo $direccioncliente; ?></td>
				</tr>
				<tr>
					<td>Moneda:</td>
					<td><?php echo $moneda; ?></td>
				</tr>

			</table>
			
		</div>

		<div class="articulos">
			<table border="" style="width: 100%;">
				<tr>
		            <th style="width: 5%; text-align:center">Item</th>
		            <th style="width: 18%; text-align:center">Descripción</th>
		            <th style="width: 10%; text-align:center">U. Medida</th>
		            <th style="width: 10%; text-align:center">Cantidad</th>
		            <th style="width: 10%; text-align:center">Descuento</th>
		            <th style="width: 10%; text-align: center;" >Val. Vta. U.</th>
		            <th style="width: 10%; text-align: center;">Precio Venta</th>
		            <th style="width: 5%; text-align: center;">IGV</th>
		            <th style="width: 10%; text-align: left">Subtotal</th>
		          
            
        		</tr>
        		<?php while($regd=$rsptad->fetch_object()){	
        			$item+=1;
        			if($regd->unidad_medida=='otros'){
        				$medida=$regd->descripcion_otros;
        			}else{
        				$medida=$regd->unidad_medida;
        			}

        			$sumdescuento+=$regd->descuento;
        			// $sumigv+=(($regd->precio_venta/1.18)*$regd->cantidad)*0.18;


        			if($regd->afectacion=='Exonerado'){
    					$newValorU=$regd->precio_venta;
    					$newigv=  0;
    				}else if($regd->afectacion=='Gravado'){
			    		$newValoU=$regd->precio_venta/1.18;
			    		$newValorU=round($newValoU,2);
			    		$newigv=  ($regd->cantidad*$regd->precio_venta/1.18-$regd->descuento)*0.18;
			    	}

			    	$sumigv+=$newigv;

        			if($item%2==0){
        				$estilo='silver';
        			}else{
        				$estilo='clouds';
        			}
        			?>

				<tr  style="text-align:center">

					<td class="<?php echo $estilo; ?>"><?php echo $item; ?></td>
					<td class="<?php echo $estilo; ?>" ><?php echo $regd->articulo; ?></td>
					<td class="<?php echo $estilo; ?>"><?php echo $medida; ?></td>
					<td class="<?php echo $estilo; ?>"><?php echo $regd->cantidad; ?></td>
					<td class="<?php echo $estilo; ?>"><?php echo $regd->descuento; ?></td>
					<td class="<?php echo $estilo; ?>"><?php echo $newValorU; ?></td>
					<td class="<?php echo $estilo; ?>"><?php echo $regd->precio_venta; ?></td>
					<td class="<?php echo $estilo; ?>"><?php echo round($newigv,2); ?></td>
					<td class="<?php echo $estilo; ?>"><?php echo $regd->precio_venta*$regd->cantidad-$regd->descuento; ?></td>
				</tr>

        		 <?php }?>
				
				<br>
			
				<tr  style="text-align:center">
					<td colspan="7"></td>
					<td style="text-align:left;">Op. gravadas</td>
					<td><?php echo $op_gravadas; ?></td>
				</tr>
				<tr  style="text-align:center">
					<td colspan="7"></td>
					<td style="text-align:left;">Op. gratuitas</td>
					<td><?php echo $op_gratuitas; ?></td>
				</tr>
				<tr  style="text-align:center">
					<td colspan="7"></td>
					<td style="text-align:left;">Op. exoneradas</td>
					<td><?php echo $op_exoneradas; ?></td>
				</tr>
				<tr  style="text-align:center">
					<td colspan="7"></td>
					<td style="text-align:left;">Op. inafectas</td>
					<td><?php echo $op_inafectas; ?></td>
				</tr>
				<tr  style="text-align:center">
					<td colspan="7"></td>
					<td style="text-align:left;">Descuentos</td>
					<td><?php echo $sumdescuento; ?></td>
				</tr>
				<tr  style="text-align:center">
					<td colspan="7"></td>
					<td style="text-align:left;">ISC</td>
					<td><?php echo $isc; ?></td>
				</tr>
				<tr  style="text-align:center">
					<td colspan="7"></td>
					<td style="text-align:left;">IGV(18%)</td>
					<td><?php echo round($sumigv,2); ?></td>
				</tr>
				
				<tr  style="text-align:center">
					<td colspan="7"></td>
					<td style="text-align:left;">Importe Total</td>
					<td><?php echo $total_venta; ?></td>
				</tr>
			</table>

			<?php 
			require_once "numeroALetras.php";
			$letras = NumeroALetras::convertir($total_venta);
			list($num,$cen)=explode('.',$total_venta);
			 ?>
			<div>
				<p>SON: <?php echo $letras.' Y '.$cen; ?>/100 SOLES</p>			
			</div>
			
			
		</div>
		<div class="linea">
				<hr>
		</div>
		<br>
		<!-- <table style="width: 50%;" class="foot">
			<tr style="text-align: center;font-size: 8pt; 
		 	 padding-right: 30px; " >
				
				<td style="width: 40%;">
					Autorizados a emitir Comprobantes Electrónicos desde el 01/02/2017, mediante R.I. N° 034-005-0006847/SUNAT
					
				</td>
			</tr>
			<tr style="text-align: center;font-size: 8pt;padding-left: 60px; 
		 	padding-right: 30px; ">
				<td ">Representación Impresa de la Factura Electrónica, puede consultar en https://faraona.ecomprobantes.com</td>
			</tr>
		</table> -->
		<div style="text-align: center;" class="foot">
			<p>Autorizados a emitir Comprobantes Electrónicos desde el 10/03/2018, mediante R.I. N° 034-005-0006847/SUNAT <br>
			Representación Impresa de la Factura Electrónica, puede consultar en https://solucionesintegralesjb.com</p>

		</div>

		
		

   
 
   

</body>
</html>