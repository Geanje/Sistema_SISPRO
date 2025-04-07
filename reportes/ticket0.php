
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
table tbody.cuerpoM{
    font-size: 7px;

}


.body, td, th {
    /*font-family: Arial, Helvetica, sans-serif;*/
    /*font-size:12px;*/
    font-family: Helvetica;

}
.articulos{
font-size: 9px;


}
.tab-c{
    font-size: 9px;

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
        $distrito=$reg['distrito'];
        $provincia=$reg['provincia'];
        $departamento=$reg['departamento'];
        $telefono=$reg['telefono'];
        $email=$reg['email'];

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
            $codigotipo_comprobante='FACTURA DE VENTA ELECTRÓNICA';
        }else  if($regc->codigotipo_comprobante =='3'){
            $codigotipo_comprobante='BOLETA DE VENTA ELECTRÓNICA';
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
                <!-- <tr>
                  <td>&nbsp;</td>
                </tr>   -->
                <tr>
                  <td style="padding-bottom: 7px">_______________________________________________</td>
                </tr>   
                <tr>
                  <td align="center" style="font-weight:bold;"><?php echo $codigotipo_comprobante; ?></td>
                </tr>  
                <tr>
                  <td align="center"><?php echo $serie.' - '.$correlativo ?></td>
                </tr>   
        </table>
        <table align="left" border="none"  width="100%" class="tab-c" style="margin-left: 8px;">
                 
                <tr  style="text-align:left; width: 80%">
                  <td  align="left">FECHA: </td>
                  <td><?php echo $dia.' - '.$mes.' - '.$anno; ?></td>
                  <td align="right"></td>
                  <td>&nbsp;</td>
                </tr>  
                <tr>
                    <td style="width: 60%;">CLIENTE: </td>
                    <td ><?php echo $cliente; ?></td>
                    <td></td>
                    <td></td>
                </tr> 
                <tr>
                    <td>R.U.C.: </td>
                    <td ><?php echo $ruc; ?></td>
                    <td></td>
                    <td></td>
                </tr> 
                
        </table>
        <table align="center" border="none"  width="100%">
                 
                <tr>
                  <td>---------------------------------------------------------------------------------</td>
                </tr>
        </table> 
        <div class="articulos">
            <table border="" style="width: 100%;">
                <tr >
                    <th style="width: 1%; text-align:center"></th>
                    <th style="width: 48%; text-align:center">Descripción</th>
                    <th style="width: 14%; text-align:center">Cant.</th>
                    <th style="width: 12%; text-align: center;">P. Unit.</th>
                    <th style="width: 20%; text-align: center;">Importe</th>
                     <th style="width: 1%; text-align:center"></th>
                 </tr>
               </table>
            <table border="" style="width: 100%;">
                <tr>
                 <th style="width: 100%; text-align:center ">--------------------------------------------------------------------------------------------------</th>
             </tr>
            </table>
        </div>
       
        <div class="articulos">
            
           
            <table border="" style="width: 80%;">
                
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
                        <td style="text-align: left; width: 1%"> </td>
                        <td style="width:60%;" ><?php echo $regd->articulo." ".$regd->serie; ?></td>
                        <td style="width:4%; text-align: center;" >  &nbsp;&nbsp;&nbsp;<?php echo $regd->cantidad;  ?></td>
                        <td style="width:5%; text-align: right;" ><?php echo number_format($preciov,2,'.',','); ?></td>
                        <td style="width:5%;  text-align: right;"  ><?php echo number_format($totalpv,2,'.',','); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td style="width:1%"> &nbsp;&nbsp;</td>
                    </tr>


                    <?php 
                    $cantidad+=$regd->cantidad;

                } ?>
                    <tr  style="font-size: 9px;" align="right">
                        <td colspan="3"></td>
                        <td style="font-size: 9px;">Op. gravada:</td>
                        <td >S/.<?php echo number_format($op_gravadas,2,'.',','); ?></td>
                    </tr>
                    <tr style="font-size: 9px;" align="right">
                        <td colspan="3"></td>
                        <td style="font-size: 9px;">IGV:</td>
                        <td >S/. <?php echo number_format($total_igv,2,'.',','); ?></td>

                    </tr>
                    <tr style="font-size: 9px;" align="right">
                        <td colspan="3"></td>
                        <td style="font-size: 9px;">Importe:</td>
                        <td >S/. <?php echo number_format($total_venta,2,'.',','); ?></td>
                    </tr>
                  
                </tbody>

            </table>

             <table border="" style="width: 100%;">
                <?php 
            require_once "numeroALetras.php";
            $letras = NumeroALetras::convertir($total_venta);
            list($num,$cen)=explode('.',$total_venta);
             ?>
                <tr>
                    <th style="width: 5%; text-align:center"></th>
                    <td style="font-size: 7px; width: 100%;">SON: <?php echo $letras.' Y '.$cen; ?>/100 SOLES</td>
                 </tr>
               </table>
            <table border="" style="width: 100%;">
                <tr>
                 <!-- <th style="width: 100%; text-align:center ">--------------------------------------------------------------------------------------------</th> -->
             </tr>
            </table>
        </div>
            <table border="" style="width: 100%;">
                <tr>
                 <!-- <th style="width: 100%; text-align:center ">--------------------------------------------------------------------------------------------</th> -->
             </tr>
            </table>
            <table align="center" border="none"  width="100%">
                <tr>
                  <td>&nbsp;</td>
                </tr>  
                <tr>
                  <td>*******************************************************************</td>
                </tr> 
                 <tr>
                  <td align="center">
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

                <tr>
                  <td align="center">¡GRACIAS POR SU COMPRA, LES DESEA!</td>
                </tr>
               
                <tr>
                  <td align="center">¡¡¡ VUELVA PRONTO !!!</td>
                </tr>
            </table>
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