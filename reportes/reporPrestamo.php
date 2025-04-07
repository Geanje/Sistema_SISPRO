<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Formulario Contacto</title>
	<!-- <link rel="stylesheet" type="text/css" href="normalize.css"> -->
	<style type="text/css">
		 table {color:black;
		 border: none;
            width: 100%;
		 }
		

		 .header{
		 	display: inline-block;
		 	padding-left: 20px; 
		 	padding-right: 20px; 

		 	
		 }
		 .text{
		 	padding-left: 20px; 
		 	padding-right: 20px;
		 	/*padding-bottom: : 10px;*/
		 	text-align:justify-all;
			line-height: 120%;

		 }
		 .text2{
		 	padding-left: 50px; 
		 	padding-right: 40px;
		 	padding-bottom: : 10px;
		 	text-align:justify-all;
			line-height: 170%;
		 }
		 .info{
		 	width: 100%; 
		 	color: red;
		 	font-size:14px;
		 	text-align:justify-all;
		 }
		 .factura{
		 	color: red;
		 	font-size: 16px;
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
		 	padding-left: 40px; 
		 	padding-right: 40px;
		 	font-size:11px;
		 }
		  .productos{
		 	font-size:12px;
		 	border-collapse: collapse;
    		padding-left: 20px; 
		 	padding-right: 20px;	 	
		 }
		 .cabecera{
		 	background:red;
			color:white;
			font-size:12px;
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
	require_once "../modelos/Prestamo.php";
	$venta=new Prestamo();
	$rsptac= $venta->ventacabecera($_GET["id"]);
	$regc=$rsptac->fetch_object();
	$cliente=$regc->cliente;
	$ruc=$regc->num_documento;
	$direccioncliente=$regc->direccion;
	$serie=$regc->serie;
	$correlativo=$regc->correlativo;
	// $moneda=$regc->descmoneda;
	$fechaCompleta= substr($regc->fechaCompleta,11,5);

	$fecha=$regc->fecha;
	// $op_gravadas=$regc->op_gravadas;
	$op_gravadas=0.00;
	$op_inafectas=$regc->op_inafectas;
	$op_exoneradas=$regc->op_exoneradas;
	$op_gratuitas=$regc->op_gratuitas;
	$isc=$regc->isc;
	$total_venta=$regc->total_venta;
	// $total_venta=0.00;
	

	$rsptad= $venta->ventadetalle($_GET["id"]);
	$item=0;
	$sumdescuento=0.00;
	$sumigv=0.00;
 ?>
   <!--<page_header>
		     <table id="encabezado">
		            <tr class="fila">
		                <td id="col_1" style="width: 96%" >
		                    <img style="width: 760px;height: 80px;" src="../files/perfil/cabecera.png">
		                </td>
		            </tr>
		        </table>
		    </page_header>
		 	
		<page_footer>
		    <hr style="border-color:red;">
		    <table style="width: 100%;">
	        	<tr class="info">
	        		<td style="text-align: center; width: 100%">
		               Telef.: <?php echo $telefono; ?> -Email.: <?php echo $email; ?>
		            </td>   
	        	</tr>
	        </table>
    	</page_footer>-->
    	<page_footer>
		        <table id="">
		            <tr class="">
		                <td style="">
		                    <img style="width: 100%;" src="../files/perfil/footer.png">
		                    <!-- 240 -->
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
		<div class="header">
			 <table style="width: 100%;">
	        	<tr>
		            <th style="width: 50%">
		                <img style="height: 80px" src="../files/perfil/logo.png"> <br><br>
		                <span style="color:red; font-size:16px;font-weight:bold"><!--<?php echo $razon_social; ?>--></span>
		            </th>
					<th style="width: 5%"></th>
					<th class="factura" style="width:35%">
						<p>PRÉSTAMO DE PRODUCTOS<br><br>
						RUC: <?php echo $rucp; ?> <br><br>
						<?php echo $serie.'-'.$correlativo; ?><br></p>
					</th>
	        	</tr>
	        </table>
	        <hr style="border-color:red;">
	   	</div>
	   	<div class="cliente">
			<table style="width: 100%;">
				<tr style="">
					<?php 
						list($anno,$mes,$dia)=explode('-',$fecha)
						 ?>
						<td style="width: 13%">Sucursal</td>
						<td style="width: 80%">: <?php echo $cliente; ?></td>
						
				</tr>
				<tr>
					<td style="width: 13%">R.U.C.</td>
					<td style="width: 80%">: <?php echo $ruc; ?> </td>
				</tr>
				<tr>
					<td style="width: 13%">Dirección</td>
					<td style="width: 80%">: <?php echo $direccioncliente; ?></td>
				</tr>
				<tr>
					<td style="width: 13%">Fecha Emisión</td>
					<td style="width: 80%">: <?php echo $dia.'-'.$mes.'-'.$anno."   ".$fechaCompleta;?></td>
					<!-- <td style="text-align: left; width: 20%"> <?php echo $fechaCompleta; ?></td> -->

				</tr>
			</table>	
		</div>
		<p class="text">
			Lista de los artículos prestados a las otras sucursales:
		</p>
		<div  class="productos" style="width: 100%">
			<table style="border: solid 0.3px red; ">
				<tr class="cabecera" style="width: 100%; text-align: center">
		            <th style="width: 7%;height: 15px; text-align:center">CANT.</th>
		            <th style="width: 60%; text-align:center">DESCRIPCION</th>
		            
		            <th style="width: 11%; text-align: center;">PRECIO</th>
		            <th style="width: 14%; text-align: center;">SUB TOTAL</th>   
				</tr>

        		<?php 
			    	$sumcant=0;
        		
        		while($regd=$rsptad->fetch_object()){	
        			$item+=1;
        			if($regd->unidad_medida=='otros'){
        				$medida=$regd->descripcion_otros;
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

			    	$sumcant += $regd->cantidad;

        			if($item%2==0){
        				$estilo='silver';
        			}else{
        				$estilo='clouds';
        			}
        			?>
					<tr  style="text-align:center">
					<td class="<?php echo $estilo; ?>" style="width: 7%"><?php echo $regd->cantidad; ?></td>
					<td class="<?php echo $estilo; ?>" style="text-align:left; width: 60%"><?php echo $regd->articulo.' '.$regd->serie; ?></td>
					<td class="<?php echo $estilo; ?>" style="width: 15%"><?php echo $regd->precio_venta; ?></td>
					<td class="<?php echo $estilo; ?>" style="width: 15%">
				<?php $subTotal =  $regd->precio_venta*$regd->cantidad-$regd->descuento; echo number_format($subTotal,2);?></td>
				</tr>

        		 <?php }?>
				
				
			</table>

			<br>
			<table cellspacing="0" cellpadding="0" border="0.2"  align="center">
				<tr style="width: 100%; text-align: left; border='0.2'">
		            <td style="text-align: center; width:25%">CANTIDAD TOTAL DE PRODUCTOS PRESTADOS</td>            
		            <td style="text-align: center; width:10%;"><p><?php echo $sumcant; ?></p></td>            
        		</tr>
        		
			</table>
			<br>
		
			 <table style="width: 100%;">
	        	<tr >
	        		<td style="width: 97%">
		               <!-- SON: <?php echo $letras.' Y '.$cen; ?>/100 SOLES<br> -->
						<hr style="border-color:red;">
		            </td>   
	        	</tr>
	        </table>	
		</div>

	<!-- 	<P class="text2"  style="color:red;"><b>CONDICIONES GENERALES:</b><br>

		<div >
			<table style="width: 100%;" style="color:red;">
				<tr ><td><b>Validez de la oferta</b></td> <td>: 30 dias</td><br></tr>
				<tr ><td><b>Cuenta Corriente BCP </b></td> <td>: 191-2288026-0-72</td><br></tr>
				<tr ><td><b>Cuenta del titular</b></td> <td>: Gamer Vision E.I.R.L.</td><br></tr>
			</table>
			
		</div>
		</p> -->
 
   

</body>
</html>