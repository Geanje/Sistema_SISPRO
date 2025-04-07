<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Formulario Contacto</title>
	<!-- <link rel="stylesheet" type="text/css" href="normalize.css"> -->
	<style type="text/css">
		 table {color:black;
		 width: 100%;
		  }
		

		 .header{
		 	display: inline-block;
		 	padding-left: 20px; 
		 	padding-right: 20px; 

		 	
		 }
		 .info{
		 	width: 100%; 
		 	color: red;
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
		 	width: 100%;
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
	require_once "../modelos/NotaCredito.php";
	$venta=new NotaCredito();
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
		$motivo=$regc->motivo;
		$sustento=$regc->sustento;
		$doc_relacionado=$regc->doc_relacionado;
		$serie_rela=$regc->serie_rela;
		$correlativo_rela=$regc->correlativo_rela;

		$total_venta=$regc->total_venta;
		// close();
	// $doc_rela= $venta->ventacabecera($doc_relacionado);
	// $regdoc=$doc_rela->fetch_object();
		// $correlativo_docrela=$regdoc->correlativo;
		// $serie_docrela=$regdoc->serie;
		// $correlativo_docrela=$regdoc['correlativo'];
	$rsptad= $venta->ventadetalle($_GET["id"]);
	$item=0;
	$sumdescuento=0.00;
	$sumigv=0.00;
 ?>

	
		<br>
		<div class="header">
			 <table style="width: 100%;">
	        	<tr>
	        		<td style="width: 5%"></td>
		            <td style="width: 50%">
		                <img style="width: 60%;height: 70px" src="../files/perfil/<?php echo $logo;?>" alt="Logo"><br>
		                <span style="color:red; font-size:16px;font-weight:bold"><?php echo $razon_social; ?></span>
		            </td>
					<td style="width: 5%"></td>
					<td class="factura" style="width:30%">
						<p>NOTA DE CRÉDITO ELECTRÓNICA <br>
						RUC: <?php echo $rucp; ?> <br>
						<?php echo $serie.'-'.$correlativo;?></p>
					</td>
	        	</tr>
	        </table>
	        <table style="width: 100%;">
	        	<tr class="info">
	        		<td style="width: 55%; ">
		                <?php echo $direccion; ?><?php echo $distrito; ?> - <?php echo $provincia; ?> - <?php echo $departamento; ?>
		            </td>
		            <td style="width: 5%"> </td>
		            <td  style="width: 40%">
		            	Telef.: <?php echo $telefono; ?><br>
		            	Email.: <?php echo $email; ?>
		            </td>
	        	</tr>
	        	<tr >
	        		<?php 
						list($anno,$mes,$dia)=explode('-',$fecha)
					 ?>
					 <td style="width: 55%;"> 
					 	Fecha de emisión : <b><?php echo $dia.'-'.$mes.'-'.$anno; ?></b></td>
				</tr>
				<tr>
					<td style="width: 55%;"><b>DOCUMENTO QUE MODIFICA:</b></td>

				</tr>
			</table>
			<table style="width: 90%;">
				<tr>
					<td style="width: 100%;"><hr style="border-color:black;"></td>

				</tr>
				
   		 	</table>
   		 	
		</div>
	
		<div class="cliente">
			<table style="width: 100%;">
				<tr>
					<td style="width: 18%;">Factura Electrónica:</td>
					<td style="width: 15%"><b><?php echo $serie_rela.'-'.$correlativo_rela; ?></b></td>
					<td style="width: 20%;">Tipo nota crédito:</td>
					<td style="width: 40%;"><b><?php echo $motivo; ?></b></td>
				</tr>
			</table>
			<table style="width: 100%;">
				<tr >
					<td style="width: 10%;">Cliente</td>
					<td style="width: 85%;">: <?php echo $cliente; ?></td>
				</tr>
				<tr>
					<td style="width: 10%">RUC</td>
					<td style="width: 85%">:<?php echo $ruc; ?></td>
				</tr>
				<tr>
					<td style="width: 10%">Dirección</td>
					<td style="width: 85%">: <?php echo $direccioncliente; ?></td>
				</tr>
				<tr>
					<td style="width: 10%">Moneda</td>
					<td style="width: 85%" >: <?php echo $moneda; ?></td>
				</tr>
				<tr>
					<td style="width: 10%">Sustento</td>
					<td style="width: 85%">: <?php echo $sustento; ?></td>
				</tr>

			</table>
			
		</div>

		<div class="articulos">
			<table border="" style="width: 100%;">
				<tr class="cabecera" style="text-align: center">
					<th style="width: 7%;height: 15px; text-align:center">CANT.</th>
		            <th style="width: 60%; text-align:center">DESCRIPCION</th>
		           	<th style="width: 11%; text-align: center;">PRECIO</th>
		            <th style="width: 14%; text-align: center;">SUB TOTAL</th>  
        		</tr>
        	</table>
				<table border="" style="width: 100%;">

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
				<tr  style="font-size: 11">
					<td class="<?php echo $estilo; ?>" style="width: 7%; text-align:center"><?php echo $regd->cantidad; ?></td>
					<td class="<?php echo $estilo; ?>" style="width: 60%"><?php echo $regd->articulo; ?></td>
					<td class="<?php echo $estilo; ?>" style="width: 11%; text-align: center;"><?php echo $regd->precio_venta; ?></td>
					<td class="<?php echo $estilo; ?>" style="width: 14%; text-align: center;"><?php $subTotal =  $regd->precio_venta*$regd->cantidad-$regd->descuento; echo number_format($subTotal,2);?></td>
				</tr>

        		 <?php }?>
			</table>
				
				<!-- <br> -->
			<table style="margin-left: 530px;">
				<tr  style="text-align:center">
					<td style="text-align:left;">Op. gravadas</td>
					<td style="text-align: right;"><?php echo $op_gravadas; ?></td>
				</tr>
				<tr  style="text-align:center">
					<td style="text-align:left;">Op. gratuitas</td>
					<td style="text-align: right;"><?php echo $op_gratuitas; ?></td>
				</tr>
				<tr  style="text-align:center">
					<td style="text-align:left;">Op. exoneradas</td>
					<td style="text-align: right;"><?php echo $op_exoneradas; ?></td>
				</tr>
				<tr  style="text-align:center">
					<td style="text-align:left;">Op. inafectas</td>
					<td style="text-align: right;"><?php echo $op_inafectas; ?></td>
				</tr>
				<!-- <tr  style="text-align:center">
					<td style="text-align:left;">Descuentos</td>
					<td><?php echo $sumdescuento; ?></td>
				</tr> -->
				<!-- <tr  style="text-align:center">
					<td style="text-align:left;">ISC</td>
					<td><?php echo $isc; ?></td>
				</tr> -->
				<tr  style="text-align:center">
					<td style="text-align:left;">IGV(18%)</td>
					<td style="text-align: right;"><?php echo round($sumigv,2); ?></td>
				</tr>
				
				<tr  style="text-align:center">
					<td style="text-align:left;">Importe Total</td>
					<td style="text-align: right;"><?php echo $total_venta; ?></td>
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
			<p>Autorizados a emitir Comprobantes Electrónicos desde el 03/08/2018 por SUNAT <br>
			Representación Impresa de la Nota de crédito Electrónica, puede verificarla ingresando al portal de la SUNAT utilizando su clave SOL</p>

		</div>

		
		

   
 
   

</body>
</html>