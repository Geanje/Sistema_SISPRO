<?php
if(strlen(session_id()) < 1)
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
		 table {color:black;
		 border: none;
            width: 100%;
		 }
		 .header{		 	
		 	padding-left: 15px; 
		 	padding-right: 15px; 
		 	margin-top: -10px		 	
		 }
		 .text{
		 	padding-left: 20px; 
		 	padding-right: 20px;
		 	font-size: 15px;
		 	/*padding-bottom: : 10px;*/
		 	text-align:justify-all;
			line-height: 120%;
			margin-top: -2px
		 }
		 .text2{
		 	padding-left: 50px; 
		 	padding-right: 40px;
		 	padding-bottom: 10px;
		 	text-align:justify-all;
			line-height: 170%;
		 }

		 .factura{		 
		 	font-size: 16px;
		 	width: 28%;
		 	height:10px;
		 	border: 1px solid red;
		 	text-align: center;
		 	border-collapse: separate;
	        border-spacing: 10;
	        border: 1px solid black;
	        border-radius: 15px;
	        -moz-border-radius: 20px;
	        padding: 2px;
		 }
		.razon-social{
		 	color: red;
		 	font-size:13px;
		 	font-weight:bold;
		 	text-transform: uppercase;
		 	margin-top: 0px;
		 	padding-left:20px;
		 }
		.info-empresa{		 	
		 	font-size:9px;
		 	text-align:center;		 	
		 	margin-top: -10px;
		 	font-weight: normal;
		 	text-transform: uppercase;
		 }
		.direcion-empresa{
		 	width: 100%; 
		 	font-size:10px;
		 	text-align:left;
		 	padding-left:30px;
		 	margin-top: 0px;
		 }		 
		.rubro{
			color: black;
		 	font-size:18px;
		 	font-weight:bold;
		 	text-transform: uppercase;
		}		 
		 .linea{
		 	padding-left: 20px; 
		 	padding-right: 20px; 
		 }
		 .cliente{
		 	padding-left: 15px; 
		 	padding-right: 15px;
		 	font-size:10px;
		 	margin-top: -15px;

		 }
		.cuadro-cliente{	
		 	border-collapse: separate;
	    border-spacing: 10;
	    border: 1px solid black;
	    border-radius: 6px;
	    -moz-border-radius: 20px;
	    padding: 3px;
	    width: 98%;
		}
		.pagos{
			text-align:center;
			display: table-cell;
			border: solid;
			border-width: thin;			
			margin-top: -10px;
	    width: 98%;

		}
		 .contenido{
		 	padding-left: 25px; 
		 	padding-right: 25px;
		 	margin-left: -10px;
		 	font-size:9px;
		 	height: 50px;
		 	width: 98%;
		 	margin-top: -7px; 
		 }		
		 .cabecera{
		 	background:#1D1B1B;
			color:white;
			line-height: 65px;
			font-size:12px;
		 	line-height: 65px;
		 	border-top-left-radius: 5px;
		 	border-top-right-radius: 10px;	
		 	margin-bottom: -5px;
		 	width: 99.2%;
		 }
		 .cuadro-contenido{
			border: 1px #1D1B1B; 
			margin-top: -1px;			
			width: 100%;
			padding-left: 0px;
			margin-left: 0px;			
		}
		.borde-contenido{			
			height: 600px; 
			width: 98.7%;
			overflow: auto; /* Agrega barras de desplazamiento */				
		}
		 .articulo{	
		 	border-collapse: separate;  
	        margin-top: 2px;
	        width: 100%;
	        margin-left: -3px;
		}

		 .total{
		 	padding-left: 35px; 
		 	padding-right: 20px;
		 	font-size:9px;
		 	font-weight: bold;
		 	
		 }	
		  .precio{
		  	width:40%; 
		  	height:10px;
		  	text-align: right;
		   }
		   .cuadro-precio{
		  	margin-left: 451.3px;
		  	margin-top: -1px;	
		   }
		 .foot{
		 	padding-left: 20px; 
		 	padding-right: 20px;
		 	font-size: 8pt;
		 	width: 98%;
		 }

		.cuadro-footer{		
	        width: 98%;		
	        text-align: center;	
		}

		.aviso{		 	
		 	font-size: 9pt;	
		 	margin-left: 10px;
		 	margin-right: 10px;
		 	text-align: justify;
		 	padding: 10px;
		 	padding-top: 5px;
		 	padding-bottom: 5px;
		 	border: solid 0.3px #000;
		 }
		.nota{		 	
		 	font-size: 10pt;	
		 	margin-left: 10px;
		 	margin-right: 10px;
		 	text-align: justify;
		 	padding: 20px;
		 	padding-top: 10px;
		 	padding-bottom: 10px;
		 }
		 .silver{
			background:white;
			padding: 3px 4px 3px;
		}
		.clouds{
			background:#ecf0f1;
			padding: 3px 4px 3px;
		}

		.boder{	
		 	
		 	border-collapse:collapse;
		 	border-color:black;    
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
  $direccion2=$reg['direccion2'];
  $distrito=$reg['distrito'];
  $provincia=$reg['provincia'];
  $departamento=$reg['departamento'];
  $fecha_inicio=$reg['fecha_inicio'];
  $telefono=$reg['telefono'];
  $email=$reg['email'];
  $web=$reg['web'];
  $rubro=$reg['rubro'];
  $logo=$reg['logo'];
	// $conexion->close() 
	require_once "../modelos/Proforma.php";
	$proforma=new Proforma();
	$rsptap= $proforma->ventacabecera($_GET["id"]);
	$regp=$rsptap->fetch_object();
	$cliente=$regp->cliente;
	$validez=$regp->validez;
	$tiempoEntrega=$regp->tiempoEntrega;
	$ruc=$regp->num_documento;
	$direccioncliente=$regp->direccion;
	$serie=$regp->serie;
	$correlativo=$regp->correlativo;
	$usuario=$regp->usuario;
	$igv_total=$regp->igv_total;
	$igv_asig=$regp->igv_asig;
	//$moneda=$regp->descmoneda;
	$fecha=$regp->fecha;
	//$op_gravadas=$regp->op_gravadas;
	//$op_inafectas=$regp->op_inafectas;
	//$op_exoneradas=$regp->op_exoneradas;
	//$op_gratuitas=$regp->op_gratuitas;
	//$isc=$regp->isc;
	$total_venta=$regp->total_venta;
	

	$rsptad= $proforma->ventadetalle($_GET["id"]);
	$item=0;
	$sumdescuento=0.00;
	$sumigv=0.00;
 ?>
<form action>
	<input type="hidden" name="rucempresa">
	<input type="hidden" name="seriecompro">
	<input type="hidden" name="correlativocompro">
</form>
<!--  Header -->
<div class="header">
	<table style="width: 100%"  >
		<tr>
		    <th style="width: 60%; text-align: center; ">
		    	<!-- <img  style="height: 80px" src="../files/perfil/logo.jpg" alt="Logo"> -->
		    	<img  style="width: 90%;" src="../files/perfil/<?php echo $logo;?>" alt="Logo">
		    	<p class="razon-social"><?php echo $razon_social; ?></p>
		    	<!-- <p class="info-empresa"><?php echo $rubro; ?></p> -->
		    </th>
		    <th style="width: 35%; text-align: center; padding-top: 1px "  class="factura">
		    	<p>
		    		R.U.C. <?php echo $rucp; ?><br><br>
					<b>PROFORMA</b><br><br>
					<?php echo $serie.'P001 - '.$correlativo ?><br><br>
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
		    <td  style="width: 90%">
		    	Dirección: <?php echo $direccion; ?> - <?php echo $distrito; ?> - <?php echo $provincia; ?><br>
		    	Telef.: <?php echo $telefono; ?>  Email.: <?php echo $email; ?><br>
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
	<table  class="cuadro-cliente">
	   	<tr><td style="width: 10%"><b>CLIENTE</b></td><td style="width: 90%">: <?php echo $cliente; ?></td></tr>
	   	<tr><td style="width: 10%"><b>R.U.C.</b></td><td style="width: 90%">: <?php echo $ruc; ?></td></tr>
	   	<tr><td style="width: 10%"><b>DIRECCIÓN</b></td><td style="width: 90%">: <?php echo $direccioncliente; ?> </td></tr>
	   	<tr>
	   		<td style="width: 10%"><b>FEHCA</b></td>
	   		<td style="width: 90%">: <?php echo strftime("%d de %B del %Y", strtotime($fecha)); ?>
	   		 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Validez de la Oferta</b> : <?php echo $validez;?> días
	   		 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Tiempo de entrega</b> : <?php echo $tiempoEntrega;?> días
	   		 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Vendedor</b> : <?php echo $usuario; ?>
	   		</td>
	   	</tr>    
	</table>

	<br>
</div>	
<!-- Fin Cliente-->
<!--  Descripcion del Comprobante -->
<div class="contenido" >
	<table class="cabecera">
				<tr >
					<th style="width: 74%; text-align: center; height: 12px; padding-top: 5px ">DESCRIPCIÓN</th>
					<th style="width: 7%; text-align: center; padding-top: 5px ">CAT.</th>
					<th style="width: 9%; text-align: center; padding-top: 5px ">P. UNIT.</th>
					<th style="width: 10%; text-align: center; padding-top: 5px ">IMPORTE</th>
				</tr>
	</table>
	<table class="cuadro-contenido" >
		<tr>
			<td  class="borde-contenido">
				<table  class="articulo" border="0.4" cellpadding="0" cellspacing="1" bordercolor="black" style="border-collapse:collapse;">
			      		<?php while($regd=$rsptad->fetch_object()){	
	        			$item+=1;
	        			/*if($regd->unidad_medida=='otros'){
	        				$medida=$regd->descripcion_otros;
	        			}
	        			$sumdescuento+=$regd->descuento;*/
	        			// $sumigv+=(($regd->precio_venta/1.18)*$regd->cantidad)*0.18;
	        			if($regd->afectacion=='Exonerado'){
	    					$newValorU=$regd->precio_venta;
	    					$newigv=  0;
	    				}else if($regd->afectacion=='Gravado'){
				    		$newValoU=$regd->precio_venta/1.18;
				    		$newValorU=round($newValoU,2);
				    		$newigv=  ($regd->cantidad*$regd->precio_venta/1.18)*0.18;
				    	}         
				    	$sumigv+=$newigv;
	        			if($item%2==0){
	        				$estilo='silver';
	        			}else{
	        				$estilo='clouds';
	        			}
	        			?>
						<tr>					
							<td style="width:74.9%; height:6px; padding-top: 6px; padding-left: 5px;  text-align: justify;">
								<?php echo $regd->nombre.' '.$regd->serie; ?></td>
							<td style="width:7%; height:6px; padding-top: 6px; text-align: center;"><?php echo $regd->cantidad; ?></td>
							<td style="width:9%; height:6px; padding-top: 6px; text-align: right;"><?php echo $regd->precio_venta; ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td style="width:10%;height:6px; padding-top: 6px; text-align: right;"><?php $subTotal =  $regd->precio_venta*$regd->cantidad; echo number_format($subTotal,2);?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
					</tr>
	        		 <?php }?>	
				</table>
			</td>
		</tr>
	</table>
</div>
<div class="total">
			<table class="cuadro-precio"cellspacing="0" cellpadding="0" border="0.5">
				<tr>
		      <td style="width:15%" class="precio">SUB TOTAL &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;S/&nbsp;&nbsp;&nbsp; </td>
		      <td style="text-align: right; width:20%"><?php echo number_format($total_venta-$igv_total,2,'.',','); ?>&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr>
		      <td style="width:15%" class="precio">IGV (<?php echo $igv_asig; ?>%) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;S/&nbsp;&nbsp;&nbsp; </td>
		      <td style="text-align: right; width:20%"><?php echo number_format($igv_total,2,'.',','); ?>&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr >
		      <td style="width:15%" class="precio">TOTAL &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;S/&nbsp;&nbsp;&nbsp; </td>
		      <td style="text-align: right; width:20%"><?php echo number_format($total_venta,2,'.',','); ?>&nbsp;&nbsp;&nbsp;</td>
        </tr>
			</table>
			<?php 
			require_once "numeroALetras.php";
			$letras = NumeroALetras::convertir($total_venta);
			list($num,$cen)=explode('.',$total_venta);
			 ?>
			<table style="width: 100%;">
	        	<tr ><td style="width: 97%"> SON: <b><?php echo $letras.' Y '.$cen; ?>/100 SOLES</b></td></tr>
	    </table>
</div>
		<!--  Medio de Pago-->
		<div class="aviso">
			<table style="width: 98%;">
				<tr>
					<td style="width: 95%;"><b>RECARGO DEL 5% POR PAGOS CON TARJETA DE CREDITO O DEBITO</b> </td>
				</tr>
				<!-- <tr>
					<td style="width: 98%;">
						Acercándose a una agencia u oficina del Banco, cajero automático, agente o transferencia por internet, (si el pago es de provincia considerar la comisión por plaza).  
					</td>				
				</tr> -->
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
				<tr>
					<td style="width: 28%;"><b>CUENTA SOLES BBVA</b></td>
					<td style="width: 25%;">: 0011-0264-02-00083101</td>
					<td style="width: 4%;"><b>CCI</b></td>
					<td style="width: 30%;">: 011-264-000200083101-92</td>
				</tr>
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