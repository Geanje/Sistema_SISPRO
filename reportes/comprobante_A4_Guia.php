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
	<--! HOJA DE ESTILOS PARA EL 'comprobante_A4_Guia.php'->
	<link rel="stylesheet" type="text/css" href="../css/style.css"> 
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
        /*$cliente=$regc->cliente;
        $tipo_doc_c=$regc->tipo_documento;
        if($tipo_doc_c == 'RUC'){
            $tipo_documento_cliente = '6';
        }else{
            $tipo_documento_cliente = '1';
        }
        $ruc=$regc->num_documento;
        if($regc->codigotipo_comprobante =='8'){
            $codigotipo_comprobante='GUIA DE REMISION ELECTRÓNICA';
        }else  if($regc->codigotipo_comprobante =='3'){
            $codigotipo_comprobante='BOLETA DE VENTA ELECTRÓNICA';
        }else  if($regc->codigotipo_comprobante =='12'){
            $codigotipo_comprobante='TICKET DE VENTA';
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
		$total_vemta=$regc->total_venta;*/
    

    require_once "../modelos/Guia.php";
    $guia=new Guia();
    $rsptag= $guia->ventacabecera($_GET["id"]);
    $regg=$rsptag->fetch_object();


      $rsptad= $guia->ventadetalle($_GET["id"]);
      $item = 0;
      //$regd=$rsptad->fetch_object();
    
 ?>

<form action>
	<input type="hidden" name="rucempresa">
	<input type="hidden" name="seriecompro">
	<input type="hidden" name="correlativocompro">
</form>

<!--  Header -->
<div class="header">
	<table style="width: 100%" >
		<tr>
		    <th style="width: 30%; text-align: center; ">
		    	<img  style="height: 85px" src="../files/perfil/logo.png" alt="Logo">
		    	<!-- <img  style="width: 90%;" src="../files/perfil/</?php echo $logo;?>" alt="Logo"> -->
		    	<p class="razon-social"> <?php echo $razon_social; ?></p>
		    	<p class="info-empresa"><!-- <?php echo $rubro; ?> --></p>
		    </th>
			<th style="width: 5%; text-align: center; "> 	
		    </th>
		    <th style="width: 36.7%; text-align: center; padding-top: 5px"  class="factura">
		    	<p>
		    		R.U.C. <?php echo $rucp; ?><br><br>
					GUIA DE REMISION ELECTRONICA<br>REMITENTE
				</p>
        <p><?php echo $regg->serie_correlativo_guia; ?></p>
		    </th>
        
		</tr>
	</table>
</div>

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
<!--  Fin Header -->				 	
<!--  Datos de traslado -->
<div class="Dtraslado" style="border: 1px solid #19376D; padding: 5px;">
<table width="50%">
  <tr>
    <th>DATOS DE TRASLADO</th>
  </tr>
  <tr>
    <td><b>Fecha de Emisión</b>&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;<?php echo $regg->fecha_emision; ?></td>
	<td style="padding-left: -110px;"><b>Motivo de Traslado</b>&nbsp;&nbsp;&nbsp;: &nbsp;<?php echo $regg->id_moti_translado; ?></td>
  <td style="padding-left: 60px;"><b>Referencia</b> : &nbsp; <?php echo $regg->serie_correlativo; ?></td>
  </tr>
  <tr>
    <td><b>Fecha de Traslado</b>&nbsp;&nbsp;&nbsp;: &nbsp;<?php echo $regg->fecha_translado ; ?></td>
    <td style="padding-left: -110px;"><b>Modalidad de Transporte</b>&nbsp;&nbsp;&nbsp;: &nbsp;<?php echo $regg->moda_transportista ; ?></td>
  </tr>
  <tr>
    <td style="padding-left: 380px;"></td>
  </tr>
</table>
<br>
  <!-- linea <hr> aquí 👍 -->
  <div class="hr-1"></div>
	<table>
		<tr> 
			<th>DATOS DEL DESTINATARIO</th>
				</tr>
			<tr>
				<td style="padding-right: 15px;"><b>Apellidos y Nombres, Denominacion o Razon Social</b>&nbsp;&nbsp;&nbsp; : &nbsp; <?php echo $regg->nombre ; ?></td>
			</tr>
			<tr>
				<td style="padding-right: 15px;"><b>Doc. Identidad</b>&nbsp;&nbsp;&nbsp; : &nbsp;<?php echo $regg->num_documento ; ?></td>
			</tr>
	</table>

</div>

<!-- Fin Cliente-->

<!--  Descripcion del Comprobante -->
<br>
<div class="contenido" >
	<table class="cabecera">
		<tr>
		    <th style="width: 66.8%; text-align: center; height: 12px; padding-top: 5px; color: black; ">DESCRIPCIÓN</th>
		    <th style=" border-left: 0.1px solid black;width: 17.4%; text-align: center; padding-top: 5px; color: black; ">CANTIDAD</th>
		    <th style=" border-left: 0.1px solid black;width: 17.6%; text-align: center; padding-top: 5px; color: black; ">UNIDAD</th>
		</tr>
	</table>
	<table class="cuadro-contenido" style="border-spacing: 0;">
  <tr>
    <td class="borde-contenido">
      <table class="articulo" border="0.1" cellpadding="0" cellspacing="1" bordercolor="black" style="border-collapse:collapse;">
      <?php
          $total_filas = 27;
          for ($i = 1; $i <= $total_filas; $i++) {
            $estilo = $i % 2 == 0 ? '#B0DAFF' : '#F0F0F0';
            $regd = $rsptad->fetch_object();
            ?>
            <tr>
              <td style="background-color: <?php echo $estilo; ?>; width: 2467%; padding: 4px">
              
              <?php echo isset($regd->nombre) ? $regd->nombre : ''; ?></td>
              <td style="background-color: <?php echo $estilo; ?>; width: 635%; padding: 4px; text-align: center;"><?php echo isset($regd->cantidad) ? $regd->cantidad : ''; ?></td>
              <td style="background-color:<?php echo $estilo; ?>; width: 635%; padding: 4px; text-align: center;"> <?php echo isset($regd->unidad_medida) ? $regd->unidad_medida : ''; ?></td>
            </tr>
            <?php
          }
        ?>
    
      </table>


    </td>
  </tr>
</table>
<table  class="cuadro-contenido2" style="border-spacing: 0; margin-top:0">
<tr>
          <td style="background-color:#DAF5FF; height:10px; width: 200px; border-left: none; border-right: none; border-bottom:none; text-align: left; font-size: 13px;">&nbsp;&nbsp;&nbsp;<strong> PESO BRUTO</strong></td>
          <td style=" background-color: #DAF5FF; width: 635%; padding: 5px; text-align: center;"></td>
          <td style=" background-color:#DAF5FF; width: 635%; padding: 5px; text-align: right;"><?php echo $regg->peso_bruto; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    </tr>
</table>

</div>
      
        	
<!--<div class="total">
			</?php 
			require_once "numeroALetras.php";
			$letras = NumeroALetras::convertir($total_venta);
			list($num,$cen)=explode('.',$total_venta);
			 ?>
	<br>
	
	<br> 
	
</div>-->
<br>
<div class="Datos">
<table class="cuadro-datos">
   <tr>
      <td colspan="2"><b>DATOS DEL PUNTO DE PARTIDA Y LLEGADA:</b></td>
   </tr>
   <tr>
      <td style="width: 20%"><b>Direccion del punto de partida</b>:</td>
      <td style="width: 77.7%"><?php echo $regg->dire_partida; ?><?php echo $regg->dire_partida; ?></td>
   </tr>
   <tr>
      <td><b>Direccion del punto de llegada:</b></td>
      <td><?php echo $regg->dire_llegada; ?></td>
   </tr>
</table>
<br>
</div>
<div class="Datos">
	<table  class="cuadro-datos">
	   	<tr><td style="width: 100%"><b>DATOS DEL TRANSPORTISTA:</b></td></tr>
	   	 <tr><td style="width: 90%"><b>Apellidos y nombres, denominacion o razon social</b>&nbsp;&nbsp;&nbsp; : &nbsp; <?php echo $regg->nombret ; ?>  </td></tr>
	   	<tr><td style="width: 90%"><b>Documento de identidad</b>&nbsp;&nbsp;&nbsp; : &nbsp;<?php echo $regg->num_documentot ; ?></td></tr>	
	</table>
	<br>
</div>

<div class="Datos1">
	<table>
	   	<tr>
        <td style="width: 22%" class="rectangulo-pequeno"><img class="qr" src="../consulta/img/qr_guia1.png" alt="qr"></td>
        <td style="width: 78%" class="rectangulo-largo">
        <p>Representación impresa de GUIA DE REMISION ELECTRONICA REMITENTE</p>
        <p>Puede consultarlo y/o descargarlo:</p>
        <h4>¡GRACIAS POR SU COMPRA, VUELVA PRONTO!</h4></td>
    </tr>
	</table>
	<br>
</div>


<!--<div class="mensaje">
	<h4>¡GRACIAS POR SU COMPRA, VUELVA PRONTO!</h4>
</div>-->
	
</body>
</html> 

