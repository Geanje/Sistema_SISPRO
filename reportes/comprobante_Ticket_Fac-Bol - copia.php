<?php

     
?>
<style type="text/css">
<!--
    div.zone { border: none; border-radius: 6mm; background: #FFFFFF; border-collapse: collapse; padding:3mm; font-size: 2.7mm;}
    h1 { padding: 0; margin: 0; color: #DD0000; font-size: 7mm; }
    h2 { padding: 0; margin: 0; color: #222222; font-size: 5mm; position: relative; }
-->

.body {
    margin: 10px; padding: 0;
    /*background-image: url(../img/fondo.png);*/
    /*background-repeat: repeat;*/
    /*padding-bottom: 1px;*/
    font-size: 11px;
    
}

.silver{
            background:white;
            padding: 2px 1px 2px;
}
.clouds{
    background:#ecf0f1;
    padding: 2px 1px 2px;
}
.cuerpoM{
    font-size: 9px;
     width: 100%; 
}
.razon_social{    
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
    font-size: 20px;
    font-weight: bold;
}
.linea{
    width:100%;
    margin-top: -10px;
    }
.body, td, th {
    /*font-family: Arial, Helvetica, sans-serif;*/
    /*font-size:12px;*/
    font-family: Helvetica;

}
    .articulos{
    font-size: 9px;
    padding-left: 10px; 
    padding-right: 10px;
}
.encabezado{
font-size: 9px;
}
.cliente{
    font-size: 9px;
    width: 100%; 
    padding-left: 10px; 
    padding-right: 10px;

}

</style>
<page format="200x80" orientation="P" style="font: arial;" class="body">
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
        $ruc=$regc->num_documento;
        $igv_asig=$regc->igv_asig;
        if($regc->codigotipo_comprobante =='1'){
            $codigotipo_comprobante='FACTURA DE VENTA ELECTRÓNICA';
        }else  if($regc->codigotipo_comprobante =='3'){
            $codigotipo_comprobante='BOLETA DE VENTA ELECTRÓNICA';
        }
        else  if($regc->codigotipo_comprobante =='12'){
            $codigotipo_comprobante='TICKET DE VENTA';
        }



            if($regc->codigotipo_comprobante =='1'){
            $codigotipo_comprobante='FACTURA  ELECTRÓNICA';
        }else  if($regc->codigotipo_comprobante =='3'){
            $codigotipo_comprobante='BOLETA DE VENTA ELECTRÓNICA';
        }else  if($regc->codigotipo_comprobante =='12'){
            $codigotipo_comprobante='TICKET DE VENTA';
        }else  if($regc->codigotipo_comprobante =='10'){
            $codigotipo_comprobante='PROFORMA';
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
 <!-- <Datos Empresa> -->
<br><br>
<div class="encabezado">
    <table id="encabezado" align="center" width="100%" >

      <!--  <tr><td> <img  style="width: 90%;" src="../files/perfil/<?php echo $logo;?>" alt="Logo"></td></tr>  -->
        <tr><td style="text-align: center; font-size: 18px; font-weight:bold;"><?php echo $razon_social ?></td></tr>
    </table>
    <table id="encabezado" align="center" width="100%">
        <tr>
            <td></td>
            <td style="text-align: center; width: 23%; ">&nbsp;</td>
            <td style="text-align: center; font-size: 14px; font-weight:bold;">R.U.C.: <?php echo $rucp ?></td>
        </tr>                
        <tr>
            <td></td>
            <td style="text-align: center; width: 23%; ">&nbsp;</td>
            <td style="text-align: center;"><?php echo $direccion ?> <?php echo $distrito.' - '.$provincia.' - '.$departamento ?></td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center; width: 23%; ">&nbsp;</td>
            <td style="text-align: center;">Email.: <?php echo $email; ?> Telf.: <?php echo $telefono ?></td>
        </tr>
    </table>
</div>
<table class="linea">
    <tr><td style="padding-bottom: 7px">_______________________________________________________</td></tr>
</table> 
<!-- <Fin Datos Empresa> -->

 <!-- <page_header> -->
            <table id="encabezado" align="center" width="100%">
                <tr class="fila">
                    <td style="height: 15px"></td>
                    <td></td><td></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: left; width: 23%; ">&nbsp;</td>
                    <td style="text-align: center; font-size: 18px;font-weight:bold;"><?php echo $razon_social ?></td>
                </tr>
                 <tr>
                    <td></td>
                    <td style="text-align: center; width: 23%; ">&nbsp;</td>
                    <td style="text-align: center;">R.U.C.: <?php echo $rucp ?></td>
                </tr>
                 <tr>
                    <td></td>
                    <td style="text-align: center; width: 23%; ">&nbsp;</td>
                    <td style="text-align: center;"><?php echo $direccion ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center; width: 23%; ">&nbsp;</td>
                    <td style="text-align: center;"><?php echo $distrito.' - '.$provincia.' - '.$departamento ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center; width: 23%; ">&nbsp;</td>
                    <td style="text-align: center;">Telf.: <?php echo $telefono ?></td>
                </tr>
            </table>
        <!-- </page_header> -->
        <!-- <page_footer>
        </page_footer> -->
        <table align="center" border="none"  width="100%">
                
                <tr>
                  <td style="padding-bottom: 0px">________________________________________________________</td>
                </tr>   
                <tr>
                  <td align="center" style="font-weight:bold;"><?php echo $codigotipo_comprobante; ?></td>
                </tr>  
                <tr>
                  <td align="center"><?php echo $serie.' - '.$correlativo ?></td>
                </tr>   
        </table>
        <table align="left" border="none"  width="100%" style="margin-left: 18px;">
                 
                <tr>
                  <td  align="left">Fecha: <?php echo $dia.' - '.$mes.' - '.$anno; ?></td>
                  <td></td>
                  <td align="right"></td>
                  <td>&nbsp;</td>
                </tr>  
                <tr>
                    <td>Cliente: <?php echo $cliente; ?></td>
                    <td ></td>
                    <td></td>
                    <td></td>
                </tr> 
                <tr>
                    <td>R.U.C.: <?php echo $ruc; ?></td>
                    <td ></td>
                    <td></td>
                    <td></td>
                </tr> 
                
        </table>
        <table align="center" border="none"  width="100%">
                 
                <tr>
                  <td>_______________________________________________</td>
                </tr>
        </table>   
        <div ">
            
           
            <table align="center" border="none" style="width: 100%"  class="cuerpo">
                <thead>
                    <tr>
                        <td style="text-align: left; width: 5%"></td>
                        <td style="width:55%;" >DESCRIPCIÓN</td>
                        <td style="width:15%;">CANT.</td>
                        <td style="width:10%;">PRECIO U.</td>
                        <td style="width:15%;">SUBTOTAL</td>
                        
                    </tr>
                </thead>
                <tbody class="cuerpoM">
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
                        <td style="text-align: left; width: 5%"></td>
                        <td  style="width:55%;" class="<?php echo $estilo; ?>" ><?php echo $regd->articulo; ?></td>
                        <td style="width:15%; text-align: center;" class="<?php echo $estilo; ?>"><?php echo $regd->cantidad;  ?></td>
                        <td style="width:10%;text-align: center;" class="<?php echo $estilo; ?>"><?php echo number_format($preciov,2,'.',','); ?></td>
                        <td style="width:15%;" align="right" class="<?php echo $estilo; ?>"><?php echo number_format($totalpv,2,'.',','); ?></td>
                    </tr>

                    <?php 
                    $cantidad+=$regd->cantidad;

                } ?>
                    <tr  align="right">
                        <td colspan="3"></td>
                        <td style="font-size: 10px;">Op. gravada:</td>
                        <td >S/.<?php echo number_format($op_gravadas,2,'.',','); ?></td>
                    </tr>
                    <tr style="font-size: 10px;" align="right">
                        <td colspan="3"></td>
                        <td style="font-size: 10px;">IGV:</td>
                        <td >S/. <?php echo number_format($total_igv,2,'.',','); ?></td>

                    </tr>
                    <tr style="font-size: 10px;" align="right">
                        <td colspan="3"></td>
                        <td style="font-size: 10px;">Importe:</td>
                        <td >S/. <?php echo number_format($total_venta,2,'.',','); ?></td>
                    </tr>
                  
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">Cantidad de artículos: <?php echo $cantidad; ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <table align="center" border="none"  width="100%">
                <tr>
                  <td>&nbsp;</td>
                </tr>  
                <tr>
                  <td>*************************************************************</td>
                </tr>     
                <tr>
                  <td align="center">¡Gracias por su compra!</td>
                </tr>
                <tr>
                  <td align="center"><?php echo $razon_social; ?></td>
                </tr>
                <tr>
                  <td align="center">¡¡¡ VUELVA PRONTO !!!</td>
                </tr>
            </table>
            
        </div>
            <!-- <table >
                <tr>
                    <td style="text-align: left; width: 8%; height: 15">&nbsp;</td>
                    <td style="text-align: left; width: 65%"> <?php  ?></td>
                    <td style="width: 22%"> <?php echo $rucp; ?></td>
                    <td style="width: 5%"></td>
                </tr>
            </table> -->
            

        <!-- </div> -->

</page>