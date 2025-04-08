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
		 }
		 .text{
		 	padding-left: 20px; 
		 	padding-right: 20px;
		 	font-size: 15px;
		 	/*padding-bottom: : 10px;*/
		 	text-align:justify-all;
			line-height: 120%;
			margin-top: -2px;
		 }
		 .text2{
		 	padding-left: 50px; 
		 	padding-right: 40px;
		 	padding-bottom: : 10px;
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
		 	font-size:9px;
		 	text-align:left;
		 	padding-left:30px;
		 	margin-top: -20px;
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
		 	font-size:11px;
		 	margin-top: -10px;

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
		 	font-size:9px;
		 	height: 50px;
		 	margin-top: -10px;
		 	width: 98%;
		 	margin-left: -10px;
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
			height: 550px; 
			width: 98.8%;						
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
		 	font-size: 10pt;	
		 	margin-left: 10px;
		 	margin-right: 10px;
		 	text-align: justify;
		 	padding: 20px;
		 	padding-top: 10px;
		 	padding-bottom: 10px;
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
		 	border-color:#087DA2;    
		}

	</style>
</head>
<body>
<?php 
require_once "../modelos/Perfil.php";
        $perfil=new Perfil();
        $rspta=$perfil->cabecera_perfil();
        // $rspta= Perfil::cabecera_perfil();
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
        require_once "../modelos/Venta2.php";
        $venta=new Venta2();
        $rsptac= $venta->ventacabecera($_GET["id"]);
        $regc=$rsptac->fetch_object();
		$cliente=$regc->cliente;		
        $tipo_doc_c=$regc->tipo_documento; 
        if($tipo_doc_c == 'RUC'){
            $tipo_documento_cliente = '6';
        }else{
            $tipo_documento_cliente = '1';
        }
        $ruc=$regc->num_documento;
        if($regc->codigotipo_comprobante =='1'){
            $codigotipo_comprobante='FACTURA  ELECTRÓNICA';
        }else  if($regc->codigotipo_comprobante =='3'){
            $codigotipo_comprobante='BOLETA DE VENTA ELECTRÓNICA';
        }

        $direccioncliente=$regc->direccion;
        $serie=$regc->serie;
        $correlativo=$regc->correlativo;
        $usuario=$regc->usuario;
        $moneda=$regc->descmoneda;
        $fecha=$regc->fecha;
        $fechaCompleta=$regc->fechaCompleta;
        list($anno,$mes,$dia)=explode('-',$fecha);

        $horas = substr($fechaCompleta, -9);
        $op_gravadas=$regc->op_gravadas;
		$total_igv=$regc->total_igv;
		
		//$total_descuentos=$regc->total_descuentos;
        $op_inafectas=$regc->op_inafectas;
        $op_exoneradas=$regc->op_exoneradas;
        $op_gratuitas=$regc->op_gratuitas;
        $isc=$regc->isc;
        $total_venta=$regc->total_venta;

        $rsptad= $venta->ventadetalle($_GET["id"]);
	$item=0;
	$sumdescuento=0.00;
 ?>
<form action>
	<input type="hidden" name="rucempresa">
	<input type="hidden" name="seriecompro">
	<input type="hidden" name="correlativocompro">
</form>
<!-- Fin Footer -->
<!--  Header -->
<div class="header">
	<table style="width: 100%"  >
		<tr>
		    <th style="width: 55%; text-align: center; ">
		    	<!-- <img  style="height: 80px" src="../files/perfil/logo.jpg" alt="Logo"> -->
		    	<img  style="width: 90%;" src="../files/perfil/<?php echo $logo;?>" alt="Logo">
		    	<p class="razon-social"><?php echo $razon_social; ?></p>
		    	<!-- <p class="info-empresa"><?php echo $rubro; ?></p> -->
		    </th>
		    <th style="width: 40%; text-align: center; padding-top: 5px "  class="factura">
		    	<p>
		    		R.U.C. <?php echo $rucp; ?><br><br>
					<b>ORDEN DE PAGO</b><br><br>
					<?php echo $serie.' - '.$correlativo ?><br><br>
				</p>
		    </th>
		    <th style="width: 3%; text-align: center; padding-top: 5px "></th>
		</tr>
	</table>
</div>
<div class="direcion-empresa">
	<table style="width: 100%">
	    <tr>
		    <td  style="width: 55%">
		    	<!--<?php echo $direccion; ?> - <?php echo $distrito; ?> - <?php echo $provincia; ?>
		    	 &nbsp;&nbsp; Telef.: <?php echo $telefono; ?><br> -->
		        Oficina Principal: <?php echo $direccion; ?><br>
	        	Sucursal:  <?php echo $direccion2; ?><br>
	        	Web: <?php echo $web; ?> &nbsp;&nbsp;Email.: <?php echo $email; ?>
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
	   	<!-- <tr><td style="width: 10%"><b>R.U.C./ D.N.I :</b></td><td style="width: 85%">: <?php echo $ruc; ?></td></tr> -->
	   	<tr><td style="width: 10%"><b>DIRECCIÓN</b></td><td style="width: 90%">: <?php echo $direccioncliente; ?> </td></tr>
	   	<tr>
	   		<td style="width: 10%"><b>FECHA</b></td>
	   		<td style="width: 90%">
	   			: <?php echo strftime("%d de %B del %Y", strtotime($fecha)); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	   			<b>Moneda</b> &nbsp;&nbsp;&nbsp;: <?php echo $moneda; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	   			<b>VENDEDOR</b> &nbsp;&nbsp;&nbsp;: <?php echo $usuario; ?>
	   		</td>
	   	</tr>
	</table>
</div>	 
<!-- Fin Cliente-->
<!--  Aviso -->
<div class="nota">
	<table style="width: 96%;" >
		<tr >
			<td style="width: 99%;">
				Este documento <b>NO ES UN COMPROBANTE DE PAGO,</b> una vez cancelada puede solicitar un comprobante electrónico; revisar los datos razón social y R.U.C. de esta orden.
			</td>
		</tr>
	</table>
</div>
<!--  Fin Aviso -->
<!--  Descripcion del Comprovante -->
<div class="contenido" >
	<table class="cabecera">
		<tr>
		    <th style="width: 67%; text-align: center; height: 12px; padding-top: 5px ">DESCRIPCIÓN</th>
		    <th style="width: 7%; text-align: center; padding-top: 5px ">CAT.</th>
		    <th style="width: 13%; text-align: center; padding-top: 5px ">P. UNIT.</th>
		    <th style="width: 13%; text-align: center; padding-top: 5px ">IMPORTE</th>
		</tr>
	</table>
	<table class="cuadro-contenido" >
		<tr>
			<td class="borde-contenido">
				<table  class="articulo" border="0.1" cellpadding="0" cellspacing="1" bordercolor="black" style="border-collapse:collapse;">
	        		<?php 
	                    $cantidad=0;
	                        while($regd=$rsptad->fetch_object()){
	                        $item+=1;
	                        if($item%2==0){
	                        $estilo='silver';
	                        }else{
	                            $estilo='clouds';
							}
							$descuento=$regd->descuento;
	                        $sumdescuento+=$regd->descuento;
	                        $newValoU=$regd->precio_venta/1.18;
	                        $newValorU=round($newValoU,2);
							$preciov = $regd->precio_venta;
							$valorVentaT=($newValorU*$regd->cantidad - $regd->descuento);
	                        $totalpv= $regd->precio_venta*$regd->cantidad;
	                     ?>
			          <tr  style="text-align:left" >                        
			            <td  style="width:67.8%; height: 1px; padding-top: 5px; text-align: justify; padding: 5px" rowspan="&"><?php echo $regd->articulo." ".$regd->serie; ?>
			           	</td>
			           	<td style="width:7%; padding-top: 5px; text-align: center;"><?php echo $regd->cantidad;  ?></td>
			             <td style="width:13%; padding-top: 5px; text-align: right;">
			            	<?php echo number_format($preciov,2,'.',','); ?>&nbsp;&nbsp;&nbsp;&nbsp;
						</td>			
			             <td style="width:13%; padding-top: 5px; text-align: right;">
			            	<?php echo number_format($totalpv,2,'.',','); ?>&nbsp;&nbsp;&nbsp;&nbsp;
			            </td>
			                         
			        </tr>
			       <?php }?>
			       <br>
				</table>
			</td>
		</tr>
	</table>
</div>
<div class="total">
	<table class="cuadro-precio"cellspacing="0" cellpadding="0" border="0.5">
		<tr>
		    <td style="width:15%" class="precio">SUB TOTAL &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;S/&nbsp;&nbsp;&nbsp; </td>
		     <td style="text-align: right; width:20%"><?php echo number_format($op_gravadas,2,'.',','); ?>&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr>
		      <td style="width:15%" class="precio">IGV (18%) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;S/&nbsp;&nbsp;&nbsp; </td>
		      <td style="text-align: right; width:20%"><?php echo number_format($total_igv,2,'.',','); ?>&nbsp;&nbsp;&nbsp;</td>
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
			<br><br>	
	<table style="width: 100%;">
	   <tr >
	        <td style="width: 97%"> SON: <b><?php echo $letras.' Y '.$cen; ?>/100 SOLES</b>			
		    </td>   
	    </tr>
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
	<table style="width: 90%;">
		<tr>
			<td style="width: 28%;"><b>TITULAR DE LA CUENTA</b></td>
			<td style="width: 70%;">: WILDER FLORENTINO JULCA BRONCANO</td>					
		</tr>
	</table>
	<table style="width: 95%;">
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
			<td style="width: 4%;"></td>
			<td style="width: 30%;"></td>
		</tr>		
	</table>
	<!-- Fin Medio de Pago-->
</div>
		
</body>
</html>
