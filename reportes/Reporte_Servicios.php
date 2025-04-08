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
  <style type="text/css">
   table {color:black; 
     border: none;
     width: 100%;}


     .header{
      /*display: inline-block;*/
      padding-left: 20px; 
      padding-right: 20px; 

      
    }
    .text{
      padding-left: 40px; 
      padding-right: 40px;
      /*padding-bottom: : 10px;*/
      text-align:justify-all;
      line-height: 120%;
    }
    .text1{
      padding-left: 40px; 
      padding-right: 40px;
      padding-bottom:  10px;
      text-align:justify-all;
      line-height: 130%;
    }
    .text2{
      padding-left: 50px; 
      padding-right: 40px;
      padding-bottom:  10px;
      text-align:justify-all;
      line-height: 170%;
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
      padding-left: 40px; 
      padding-right: 40px;

    }
    .articulos{
      padding-left: 40px; 
      padding-right: 40px;
      font-size:11px;
    }
    .cabecera{
      background:#087DA2;
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
    .contenido{
      font-size:12px;
      padding-left: 20px; 
      padding-right: 20px; 

    } 
    .costo{     
      background:#087DA2;
      color:white;
      font-size:12px;
      padding-left: 1px; 
      padding-right: 1px;


    }   
    .silver{
      background:white;
      padding: 3px 4px 3px;
    }
    .clouds{
      background:#ecf0f1;
      padding: 3px 4px 3px;
    }
    /*#encabezado {padding:10px 0; border-top: 1px solid; border-bottom: 1px solid; width:100%;}*/
    #encabezado .fila #col_1 {width: 15%}
    #encabezado .fila #col_2 {text-align:center; width: 55%}
    #encabezado .fila #col_3 {width: 15%}
    #encabezado .fila #col_4 {width: 15%}

    #encabezado .fila td img {width:50%}
    #encabezado .fila #col_2 #span1{font-size: 15px;}
    #encabezado .fila #col_2 #span2{font-size: 12px; color: #4d9;}
  </style>
</head>
<body>
  <?php 
  require_once "../modelos/servicios-soporte.php";
  $soporte=new Soporte();
  $data= $soporte->mostrar($_GET["idsoporte"]); 
  


  

  $datad= $soporte->pagoSoporte($_GET["idsoporte"]);   
  
  

  ?>
  <!-- Cabercera -->
  <page_header>
  <table id="encabezado">
    <tr class="fila">
      <td id="col_1" style="width: 96%" >
        <img style="width: 760px;height: 80px;" src="../files/perfil/cabecera.png">
      </td>
    </tr>
  </table>
  </page_header>
  <!-- Pie de Pagina -->
  <page_footer>
  <table>
    <tr class="">
      <td style=""><img style="width: 760px;height: 140px;" src="../files/perfil/footer.png"></td>
    </tr>
  </table>
  </page_footer>
  <br><br><br><br><br><br><br>
  <!-- Codigo de Soporte -->
  <div class="header">
    <table style="width: 100%;">
      <tr>

        <td style="width:100%; text-align:center"><b>INFORME  TECNICO DEL SERVICIO N° <?php echo $codigo_soporte = $data['codigo_soporte'] ?> - SIJB - <?=date('Y')?></b></td>
        
      </tr>              
    </table>
  </div>
  <!-- Fecha -->
  <div class="linea"><hr></div><br>

  <!-- Datos del Cliente -->
  <div class="cliente"> 
         
    <table>
      <tr style="width: 100%;">
        <td style="width: 18%; padding-left: 0px;"><b>Fecha de Ingreso:</b></td>        
        <td style="width: 30%; padding-left: 5px;"><?php  echo strftime("%d de %B del %Y", strtotime($fecha_ingreso = $data['fecha_ingreso']));?></td>
        <!--<td style="width: 30%;">: <?php setlocale(LC_ALL,"es_ES"); echo strftime("%d de %B del %Y"); ?></td>-->            
        <td style="width: 18%; padding-left: -10px;"><b>Fecha de Entrega:</b></td> 
        <td style="width: 30%%; padding-left: 4px;"><?php  echo strftime("%d de %B del %Y", strtotime($fecha_salida = $data['fecha_salida']));?></td>

      </tr>
    </table>
                   
  </div>
  <div class="contenido">
    <b>DATOS CLIENTES:</b><br>
     <table>

      <tr style="width: 100%;">
        <td style="width: 12%;"><b>Nombre</b></td>
        <td style="width: 88%">: <?php echo $nombre_cliente =  $data['cliente']; ?></td>                                    
      </tr>
      <tr style="width: 100%;">
        <td style="width: 12%;"><b>Dirección</b></td>
        <td style="width: 88%">: <?php echo $direccion =  $data['direccion']; ?></td>  
                                           
      </tr>
  
      <tr style="width: 100%;">
        <td style="width: 13.5%;"><b>Teléfono </b></td>
        <td style="width: 30%"> : <?php echo $telefono =  $data['telefono']; ?></td>   
      </tr>  

      <tr>
        <td style="width: 13.5%;"><b>Garantia </b></td>
        <td style="width: 30%">:   <?php echo $telefono =  $data['garantia']; ?></td>   
      </tr>
     
    </table> 
    </div> 
  <!-- <div class="cliente">      
                     
  </div> -->
  <!-- Datos del Equipo -->
  <br>
  <div  class="contenido">
    <b> DATOS DEL EQUIPO</b><br><br>
    <table cellspacing="0" cellpadding="0" border="0.2" bordercolor="#087DA2">  
      <tr  class="cabecera"  style="width: 98%">
        <th style="width: 33%;height: 15px; text-align:center">TIPO</th>
        <th style="width: 33%; text-align:center">MARCA/MODELO</th>
        <th style="width: 33%; text-align:center">ACCESORIOS</th> 
      </tr>
      <tr style="width: 98%">
        <td style="width:33%; padding-left:7px; height:60px"><?php echo strtoupper ( $tipo_equipo =  $data['tipo_equipo']); ?></td>
        <td style="width:33%; padding-left:7px"><?php echo strtoupper ( $marca = $data['marca']); ?></td>                                
        <td style="width:33%; padding-left:7px;"><?php echo ucfirst( $accesorio =  $data['accesorio']); ?></td>                                                          
      </tr>
    </table>
  </div>
  <!-- Diaggnostico/Solucón -->
  <br>  
  <div  class="contenido">
    <b> DIAGNOSTICO Y SOLUCION</b><br><br>
    <table cellspacing="0" cellpadding="0" border="0.2" bordercolor="#087DA2">  
      <tr  class="cabecera"  style="width: 98%">
        <th style="width: 33%; height: 15px; text-align:center">PROBLEMA</th>
        <th style="width: 33%; text-align:center">SOLUCION APLICADA</th>
        <th style="width: 33%; text-align:center">RECOMENDACION TECNICA</th> 
      </tr>
      <tr style="width: 98%">
        <td style="width: 33%; padding-left:7px; height: 100px"><?php echo ucfirst( $problema =  $data['problema']); ?></td>
        <td style="width: 33%; padding-left:7px"><?php echo ucfirst( $solucion =  $data['solucion']); ?></td>                                    
        <td style="width: 33%; padding-left:7px"><?php echo ucfirst($recomendacion =  $data['recomendacion']); ?></td>                                                          
      </tr>
    </table>
  </div>
  <!-- Garantia/Tecnico Responsable -->
  <br>
  <table>
    <tr>
      <td style="width:2%"></td>
      <td>
        <!-- <table cellspacing="0" cellpadding="0" border="0.2" bordercolor="#087DA2" >  
          <tr class="cabecera"   style="width: 98%">      
            <th class="costo" style="width: 20%; height: 15px; text-align:center"> COSTO DEL SERVICIO</th>  
            <th class="costo" style="width: 19%; text-align:center"> A CUENTA </th>  
            <th class="costo" style="width: 20%; text-align:center"> TOTAL</th>     
            <th class="costo" style="width: 20%; text-align:center"> GARANTIA DEL SERVICIO</th>
            <th class="costo" style="width: 20%; text-align:center"> FECHA DE ENTREGA</th>
          </tr>
          <tr  style="width: 98%">   
            <td style="width: 20%; text-align:center">S/ <?php echo number_format($total =  $data['total'],2,'.',','); ?></td>
            <td style="width: 19%; text-align:center"><?php echo  number_format($cuota =  $data['cuota'],2,'.',','); ?></td>
            <td style="width: 20%; text-align:center"><?php echo  number_format($saldo =  $data['saldo'],2,'.',','); ?></td>
            <td style="width: 20%; text-align:center"><?php echo  ucfirst($garantia =  $data['garantia']); ?> </td>
            <td style="width: 20%; text-align:center"><?php  echo strftime("%d de %B del %Y", strtotime($fecha_salida = $data['fecha_salida']));?></td>
                
          </tr>        
        </table> -->
        <br>
        <b>HISTORIAL DE PAGOS</b><br><br>
        <table cellspacing="0" cellpadding="0" border="0.2" bordercolor="#087DA2" >  
          <tr class="cabecera"   style="width: 98%">      
            <th class="costo" style="width: 20%; height: 15px; text-align:center"> COSTO DEL SERVICIO</th>  
            <th class="costo" style="width: 19%; text-align:center"> MONTO PAGADO </th>  
            <th class="costo" style="width: 20%; text-align:center"> SALDO</th>     
            <th class="costo" style="width: 20%; text-align:center"> TIPO DE PAGO</th>
            <th class="costo" style="width: 20%; text-align:center"> FECHA DE PAGO</th>
          </tr>
          <?php 
                    $cantidad=0;
                        while($regs=$datad->fetch_object()){
                          $cuotas=$regs->cuota;
                          $fecha_pago=$regs->fecha_pago;
                          $tipo_pago=$regs->tipo_pago;
                          $saldos=$regs->saldo;
                          ?>
          <tr  style="width: 98%">   
            <td style="width: 20%; text-align:center">S/ <?php echo number_format($total =  $data['total'],2,'.',','); ?></td>
            <td style="width: 19%; text-align:center"><?php echo number_format($cuotas,2,'.',',');?></td>
            <td style="width: 20%; text-align:center"><?php echo  number_format($saldos ,2,'.',','); ?></td>
            <td style="width: 20%; text-align:center"><?php echo $tipo_pago ?> </td>
            <td style="width: 20%; text-align:center"><?php  echo strftime("%d de %B del %Y", strtotime($fecha_pago));?></td>
                
          </tr> 
          <?php }?>       
        </table>
      </td>    
    </tr>
  </table>  

  <!-- Fecha/Firma-->
  <br><br><br>
  <div class="cliente">      
    <table style="width: 98%;">
      <tr style="width: 100%;">  
        <td style="width: 5%"></td>     
        <td style="width: 28%; text-align:center"><img  src="firma/responsable.png"></td>
        <td style="width: 25%"></td>
        <td style="width: 30%; text-align:center"><img  src="firma/tecnico.png"></td>                
      </tr>
      <tr style="width: 100%;"> 
      <td style="width: 5%"></td>       
        <td style="width: 28%; height: 5px; text-align:center"><hr style="border: 0.3px dotted #087DA2; width:300px" /></td>
        <td style="width: 25%"></td>
        <td style="width: 30%;text-align:center"><hr style="border: 0.3px dotted #087DA2; width:300px" /></td>                
      </tr>
      <tr style="width: 100%;"> 
      <td style="width: 5%"></td>       
        <td style="width: 28%; height: 5px; text-align:center">Responsable del Area  </td>
        <td style="width: 25%"></td>
        <td style="width: 30%; text-align:center">Técnico Responsable </td>                                        
      </tr>
      <tr  style="width: 100%"> 
      <td style="width: 5%"></td>   
        <td style="width: 28%; height: 5px; text-align:center">Soporte Técnico</td>
        <td style="width: 25%; text-align:center"></td> 
        <td style="width: 30%; text-align:center"> <?php echo  $tecnico_respon =  $data['tecnico']; ?></td>  

      </tr>
    </table>                  
  </div>
  <?php 
      //echo '<br>' . $fecha_ingreso =  $data['fecha_ingreso'];
      //echo '<br>' . $nombre_cliente =  $data['nombre_cliente'];
      //echo '<br>' . $marca =  $data['marca'];
      //echo '<br>' . $estado_servicio =  $data['estado_servicio'];
      //echo '<br>' . $estado_entrega =  $data['estado_entrega'];
      //echo '<br>' . $estado_pago =  $data['estado_pago'];
      //echo '<br>' . $tecnico_respon =  $data['tecnico_respon'];
      //echo '<br>' . $solucion =  $data['solucion'];
      //echo '<br>' . $tipo_equipo =  $data['tipo_equipo'];
      //echo '<br>' . $telefono =  $data['telefono'];
      //echo '<br>' . $problema =  $data['problema'];
      //echo '<br>' . $total =  $data['total'];
      //echo '<br>' . $cuota =  $data['cuota'];
      //echo '<br>' . $saldo =  $data['saldo'];
      //echo '<br>' . $fecha_ingreso =  $data['fecha_ingreso'];
      //echo '<br>' . $direccion =  $data['direccion'];
      //echo '<br>' . $accesorio =  $data['accesorio'];
      //echo '<br>' . $recomendacion =  $data['recomendacion'];
      //echo '<br>' . $garantia =  $data['garantia'];
 

      ?>


    </body>
    </html>



