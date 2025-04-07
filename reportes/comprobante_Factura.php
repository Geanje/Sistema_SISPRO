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
		 	display: inline-block;
		 	padding-left: 20px; 
		 	padding-right: 20px; 

		 	
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
		 	padding-bottom: : 10px;
		 	text-align:justify-all;
			line-height: 170%;
		 }
		 .info{
		 	width: 100%; 
		 	font-size:15px;
		 	text-align:justify;
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
		 	padding-left: 30px; 
		 	padding-right: 20px;

		 }
		 .articulos{
		 	padding-left: 40px; 
		 	padding-right: 40px;
		 	font-size:11px;
		 	margin-top: -15px
		 }
		  .productos{
		 	font-size:12px;
		 	border-collapse: collapse;
    		padding-left: 20px; 
		 	padding-right: 20px;

		 }
		 .cabecera{
		 	background:#087DA2;
			color:white;
			line-height: 65px;
			font-size:12px;
			padding-left: 20px; 
		 	padding-right: 20px;
		 	line-height: 65px;
		 }		
		 .foot{
		 	padding-left: 20px; 
		 	padding-right: 20px;
		 	font-size: 8pt;
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
		 .razon-social{
		 	color: red;
		 	font-size:18px;
		 	font-weight:bold;
		 	text-transform: uppercase;
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
        // $rspta= Perfil::cabecera_perfil();
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
        }else  if($regc->codigotipo_comprobante =='12'){
            $codigotipo_comprobante='TICKET DE VENTA';
        }

        $direccioncliente=$regc->direccion;
        $serie=$regc->serie;
        $correlativo=$regc->correlativo;
        $moneda=$regc->descmoneda;
        $fecha=$regc->fecha;
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
<br>

<form action>
	<input type="hidden" name="rucempresa">
	<input type="hidden" name="seriecompro">
	<input type="hidden" name="correlativocompro">
</form>
<!--  Header -->
<div class="header">
	<table style="width: 100%;">
	    <tr> 
		    <td style="width: 35%">
			    <img style="height: 80px" src="../files/perfil/<?php echo $logo;?>" alt="Logo"><br><br>
			    <span style="height: 1px" class="razon-social">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $razon_social; ?></span>
		    </td>
		        <td style="width: 3%"></td>
		        <td class="factura" style="width:38%">
					<p>R.U.C. <?php echo $rucp; ?><br><br><b>
						<?php echo $codigotipo_comprobante; ?></b><br><br>
						<?php echo $serie.' - '.$correlativo ?><br><br></p>
					</td><td style="width: 3%"></td>
				</tr>	
	 </table>
</div>
<br>
<div class="articulos">
	    	<table style="width: 100%;" class="info" >
	        	<tr>
	        		<td style="width: 55%">
	        		   <?php echo $direccion; ?> - <?php echo $distrito; ?>  <?php echo $provincia; ?>  - <?php echo $departamento; ?> <br>	        			
	        		</td>
	        	</tr>
	        </table>
	        <table style="width: 100%;" class="info" >
	        	<tr>
		            <td  style="width: 50%">Telef.: <?php echo $telefono; ?></td>
		            <td  style="width: 50%">Email.: <?php echo $email; ?></td>
	        	</tr>
		 	</table>
		</div>
		<hr style="border: solid 0.3px #000;">
<!--  Fin Header -->				 	
<!--  Cliente-->
	   	<div class="cliente">
			<table style="width: 100%;">
				<tr style="">
					<?php 
						list($anno,$mes,$dia)=explode('-',$fecha)
						 ?>
						<td style="width: 13%"><b>CLIENTE</b></td>
						<td style="width: 80%">: <?php echo $cliente; ?></td>
						
				</tr>
				<tr>
					<td style="width: 13%"><b>DNI / RUC.</b></td>
					<td style="width: 80%">: <?php echo $ruc; ?> </td>
				</tr>
				<tr>
					<td style="width: 13%"><b>DIRECCIÓN</b></td>
					<td style="width: 80%">: <?php echo $direccioncliente; ?></td>
				</tr>
			</table>
			<table style="width: 100%;">
				<tr>
					<td style="width: 13%"><b>FECHA</b></td>
					<td style="width: 30%">: <?php echo strftime("%d de %B del %Y", strtotime($fecha)); ?></td>
					<td style="width: 7%"><b>Moneda</b></td>
					<td style="width: 13%">: <?php echo $moneda; ?></td>
	
				</tr>
			</table>	
		</div>
		<hr style="border: solid 0.3px #000;">
<!-- Fin Cliente-->
<!--  Descripcion del Comprovante -->
<br>
<div class="articulos">
	<table style="width: 100%" cellspacing="0" cellpadding="0" border="0.2"  >
		<tr class="cabecera">
		    <th style="width: 60%; text-align: center; height: 12px; padding-top: 5px ">DESCRIPCIÓN</th>
		    <th style="width: 7%; text-align: center; padding-top: 5px ">CAT.</th>
		    <th style="width: 13%; text-align: center; padding-top: 5px ">P. UNIT.</th>
		    <th style="width: 13%; text-align: center; padding-top: 5px ">MPORTE</th>
		</tr>
        <?php 
                    $cantidad=0;
                        while($regd=$rsptad->fetch_object()){
                        $item+=1;
                        if($item%2==0){
                        $estilo='silver';
                        }else{
                            $estilo='clouds';
                        }
                        $newValoU=$regd->precio_venta/1.18;
                        $newValorU=round($newValoU,2);
                        $preciov = $regd->precio_venta;
                        $totalpv= $regd->precio_venta*$regd->cantidad;
                     ?>

         <tr  style="text-align:left">
                        
                        <td style="width:60%; height: 1px; padding-top: 5px; text-align: justify; padding: 5px" rowspan="&"><?php echo $regd->articulo." ".$regd->serie; ?></td>
                        <td style="width:7%; padding-top: 5px; text-align: center;"><?php echo $regd->cantidad;  ?></td>
                        <td style="width:13%; padding-top: 5px; text-align: right;"><?php echo number_format($preciov,2,'.',','); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td style="width:13%; padding-top: 5px; text-align: right;"><?php echo number_format($totalpv,2,'.',','); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                         
        </tr>

        		 <?php }?>
				
				<br>
	</table>
               	

			<?php 
			require_once "numeroALetras.php";
			$letras = NumeroALetras::convertir($total_venta);
			list($num,$cen)=explode('.',$total_venta);
			 ?>
			
			
			<br><br>
			<table cellspacing="0" cellpadding="0" border="0.2"  >
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
						<td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; <?php echo number_format($op_gravadas,2,'.',','); ?></td>
						<td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; 00.00</td>
						<td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; 00.00</td>
						<td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; 00.00</td>
						<td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; 00.00</td>
						<td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; <?php echo number_format($total_igv,2,'.',','); ?></td>
						<td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; <?php echo number_format($total_venta,2,'.',','); ?></td>
					</b>	
				</tr>
			</table>
			<br>
			
	
			
		<table  style="border: solid 0.2px black; ">
			<tr style="width: 80%;">
				<td style=" width:92%; height: 14px;">SON: <b><?php echo $letras.' Y '.$cen; ?>/100 SOLES</b></td>	
			</tr>	
		</table>
		<br>
	
			</div>	


<page_footer>
	    	
			     <div class="foot">
				 <table cellspacing="0" cellpadding="0" border="0.2"  >
						<tr class="articulos" style="width: 100%; text-align: center">
				            <td style="width: 94%; padding-top: 5px">

				            	¡¡¡ GRACIAS POR SU COMPRA VUELVA PRONTO !!! <br><br>
				            	<!-- <b>Tipo de cambio: <?php echo ' S/ '.$tipo_cambio_dolar ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				            	Monto a pagar en: S/ &nbsp;&nbsp;<?php echo $tipo_cambio_dolar*$total_venta ?> nuevo sol<br></b> -->
				            	________________________________________________________________________________________________________<br><br>
				            	Representación impresa de la FACTURA ELECTRONICA emitida del sistema del contribuyente autirazado con fecha 02/01/2021<br>	           
				            </td>		            	
							<td style="text-align: center; width: 12%">
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
	        <br><br>
</page_footer> 
 		
		

   
 
   

</body>
</html>