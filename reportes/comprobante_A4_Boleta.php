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
		 	margin-top: 10px;
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
		 	width: 98%;
		 }
		 .cuadro-contenido{
			border: 1px #1D1B1B;
			margin-left: 0px;
			/*padding-bottom: -3px;*/
			padding-top: 0px;
			float: left;	
		}
		.borde-contenido{		
			height: 630px; 
			width: 98%;
	        margin-left: 0px;
			padding-top: 0px;
							
		}
		.borde-contenido_1{		
			height: 630px; 
			width: 98%;	
			padding-left: 3px;
							
		}
		.cuadro{	
		 	border-collapse: separate;  
	        margin-top: 0px;
	        width: 98%;
	        margin-left: 0px;
			padding-top: -633px;
			
		}
		 .articulo{	
		 	border-collapse: separate;  
			 padding-left: 0px;
			 padding-right: 0px;
	        width: 98%;
	        padding-top: -633px;
		}
		 .total{
		 	padding-left: 35px; 
		 	padding-right: 20px;
		 	font-size:9px;
		 	font-weight: bold;	
			 margin-top: 5x;	 	
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
		$igv_asig=$regc->igv_asig;

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
		}else  if($regc->codigotipo_comprobante =='8'){
            $codigotipo_comprobante='GUIA DE REMISION ELECTRÓNICA REMITENTE';
        }else  if($regc->codigotipo_comprobante =='12'){
            $codigotipo_comprobante='NOTA DE VENTA';
        }

        $direccioncliente=$regc->direccion;
        $serie=$regc->serie;
        $correlativo=$regc->correlativo;
        $usuario=$regc->usuario;
        $moneda=$regc->descmoneda;
        $fecha=$regc->fecha;
        $fecha_ven=$regc->fecha_ven;
		$codigotipo_pago=$regc->codigo_pago;
        $fechaCompleta=$regc->fechaCompleta;
        list($anno,$mes,$dia)=explode('-',$fecha);
        $horas = substr($fechaCompleta, -9);
        $op_gravadas=$regc->op_gravadas;
        $total_igv=$regc->total_igv;
        $op_inafectas=$regc->op_inafectas;
        $op_exoneradas=$regc->op_exoneradas;
        $op_gratuitas=$regc->op_gratuitas;
        $isc=$regc->isc;
        $total_venta=$regc->total_venta;
        $rsptad= $venta->ventadetalle($_GET["id"]);
    $item=0;
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
		    <th style="width: 55%; text-align: center; ">
		    	<!-- <img  style="height: 80px" src="../files/perfil/logo.jpg" alt="Logo"> -->
		    	<img  style="width: 90%;" src="../files/perfil/<?php echo $logo;?>" alt="Logo">
		    	<p class="razon-social"> <?php echo $razon_social; ?></p>
		    	<p class="info-empresa"><!-- <?php echo $rubro; ?> --></p>
		    </th>
		    <th style="width: 40%; text-align: center; padding-top: 5px "  class="factura">
		    	<p>
		    		R.U.C. <?php echo $rucp; ?><br><br>
					<b><?php echo $codigotipo_comprobante; ?></b><br><br>
					<?php echo $serie.' - '.$correlativo ?><br><br>
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
	   	<!-- <tr><td style="width: 10%"><b>R.U.C./ D.N.I :</b></td><td style="width: 85%">: <?php echo $ruc; ?></td></tr> -->
	   	<tr><td style="width: 10%"><b>DIRECCIÓN</b></td><td style="width: 90%">: <?php echo $direccioncliente; ?> </td></tr>
	   	<tr>
	   		<td style="width: 10%"><b>FECHA</b></td>
	   		<td style="width: 90%">
	   			: <?php echo strftime("%d de %B del %Y", strtotime($fecha)); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	   			<b>Moneda</b> &nbsp;: <?php echo $moneda; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	   			<b>Vendedor</b> &nbsp;: <?php echo $usuario; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	   			<b>Forma de pago</b> &nbsp;: <?php echo $codigotipo_pago; ?>
	   		</td>
	   	</tr>
	</table>
</div>	 
<!-- Fin Cliente-->
<!--  Descripcion del Comprobante -->
<br>
<div class="contenido" >
	<table class="cabecera">
		<tr>
			<th style="width: 9.05%; height: 3.2px; text-align: center; padding-top: 5px ">CODIGO</th>
		    <th style="width: 55%; text-align: center; height: 12px; padding-top: 5px ">DESCRIPCIÓN</th>
		    <th style="width: 10%; text-align: center; padding-top: 5px ">CAT.</th>
		    <th style="width: 13%; text-align: center; padding-top: 5px ">P. UNIT.</th>
		    <th style="width: 13%; text-align: center; padding-top: 5px ">IMPORTE</th>
		</tr>
	</table>
	<table class="cuadro-contenido" >
		<tr>
			<td class="borde-contenido">
				
			</td>
		</tr>
	</table>
	<table class="cuadro" border="0.1" cellpadding="0" cellspacing="1" bordercolor="#1D1B1B" style="border-collapse:collapse;">
						<tr>                        
							<td style="width:9.05%; height: 630px"></td>			
							<td style="width:55%; "></td>
							<td style="width:10%; "></td>
							<td style="width:13%; "></td>
							<td style="width:13%; "></td>							
						</tr>
	</table> 
	
	<table  class="articulo" border="0.1" cellpadding="0" cellspacing="1" bordercolor="#1D1B1B" style="border-collapse:collapse;">
			        <?php 
			                    $cantidad=0;
			                        while($regd=$rsptad->fetch_object()){
			                        $item+=1;
			                        if($item%2==0){
			                        $estilo='#DAF9FB';
			                        }else{
			                            $estilo='#F0F0F0';
			                        }
			                        $newValoU=$regd->precio_venta/1.18;
			                        $newValorU=round($newValoU,2);
			                        $preciov = $regd->precio_venta;
			                        $totalpv= $regd->precio_venta*$regd->cantidad;
			                     ?>

			         <tr  style="text-align:left" >                        
					 	<td style="background-color: <?php echo $estilo; ?>;; width:9.05%; padding-top: 5px; text-align: center;"><?php echo $regd->codigo;  ?></td>
			            <td style="background-color:<?php echo $estilo; ?>;; width:55%; height: 3.2px; padding-top: 5px; text-align: justify; padding: 5px" rowspan="&"><?php echo $regd->articulo." ".$regd->serie ; ?></td>
			            <td style="background-color: <?php echo $estilo; ?>;; width:10%; padding-top: 5px; text-align: center;"><?php echo $regd->cantidad;  ?></td>
			            <td style="background-color: <?php echo $estilo; ?>;; width:13%; padding-top: 5px; text-align: right;"><?php echo number_format($preciov,2,'.',','); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			            <td style="background-color: <?php echo $estilo; ?>;; width:13%; padding-top: 5px; text-align: right;"><?php echo number_format($totalpv,2,'.',','); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			                         
			        </tr>

			        <?php }?>
							
					<br>
				</table>

</div>             	
<div class="total">

			<?php 
			require_once "numeroALetras.php";
			$letras = NumeroALetras::convertir($total_venta);
			list($num,$cen)=explode('.',$total_venta);
			 ?>
	<br>	
	<table cellspacing="0" cellpadding="0" border="0.2"  >
		<tr style="width: 100%; text-align: center">
	        <td style="text-align: center; width:25%">OP. GRABADA</td>
	        <!-- <td style="text-align: center; width:12%">OP. <br>GRATUITA</td>
	        <td style="text-align: center; width:12%">OP.<br> EXONERADA</td>
	        <td style="text-align: center; width:12%">OP. <br>INAFECTA</td>
	        <td style="text-align: center; width:12%">DESCTO <br>TOTAL </td> -->
	        <td style="text-align: center; width:25%">IGV (<?php echo $regc->igv_asig; ?>%)</td>
	        <td style="text-align: center; width:25%">PRECIO TOTAL</td> 
        </tr>
       	<tr style="width: 100%; text-align: center;">
			<td style="width:25%">S/ &nbsp;&nbsp;&nbsp;&nbsp; <?php echo number_format($op_gravadas,2,'.',','); ?></td>
			<!-- <td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; 00.00</td>
			<td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; 00.00</td>
			<td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; 00.00</td>
			<td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; 00.00</td> -->
			<td style="width:25%">S/ &nbsp;&nbsp;&nbsp;&nbsp; <?php echo number_format($total_igv,2,'.',','); ?></td>
			<td style="width:25%">S/ &nbsp;&nbsp;&nbsp;&nbsp; <?php echo number_format($total_venta,2,'.',','); ?></td>
		</tr>
	</table>
	<br><br>
</div>	
	<div class="foot">
		<table cellspacing="0" cellpadding="0" border="0.2"  >
			<tr class="cuadro-footer" >
		        <td style="width: 89%; padding-top: 5px">
					TOTAL DE VENTA: <b>&nbsp;&nbsp;&nbsp;S/&nbsp; <?php echo number_format($total_venta,2,'.',','); ?></b>
					&nbsp;&nbsp;&nbsp;&nbsp;SON:<b>&nbsp; <?php echo $letras.' Y '.$cen; ?>/100 SOLES	</b> 
					_____________________________________________________________________________________________________________
					<p style="text-align: center;">	<b>¡¡¡ GRACIAS POR SU COMPRA VUELVA PRONTO !!!</b> <br>
					Representación impresa de la FACTURA ELECTRONICA<br>	
					Emitida del sistema del contribuyente autorizado con fecha
					<b><?php echo strftime("%d de %B del %Y", strtotime($fecha_inicio)); ?></b><br>		
					Puede consultar su comprobante electrónico utilizando su clave SOL, en la plataforma de SUNAT.<?php echo $web; ?>             </p>           
				</td>		            
				<td style="text-align: center; width: 10%">
					<?php 
			        require "phpqrcode/qrlib.php";    
			        $dir = 'temp_qrcode/';
			        if (!file_exists($dir)){
			            mkdir($dir);
			            }
			      	$filename = $dir.$rucp.'-0'.$regc->codigotipo_comprobante.'-'.$serie.'-'.$correlativo;
			       	$tamaño = 2; //Tamaño de Pixel
			      	$level = 'Q'; //Precisión Baja
			      	 // L = Baja
			      	 // M = Mediana
			       	// Q = Alta
			      	 // H= Máxima
			      	$framSize = 1; //Tamaño en blanco
			      	$contenido = $rucp.'|0'.$regc->codigotipo_comprobante.'|'.$serie.'|'.$correlativo.'|'.$total_igv.'|'.$total_venta.'|'.$fecha.'|'.$tipo_documento_cliente.'|'.$ruc.'|'; //Texto
			        //Enviamos los parametros a la Función para generar código QR 
			        QRcode::png($contenido, $filename, $level, $tamaño, $framSize); 
			        //Mostramos la imagen generada
			        echo '<img src="'.$dir.basename($filename).'" />';  
			        ?>
		        </td>
			</tr>
		</table>
	</div>
	
</body>
</html>