
<style type="text/css">
<!--
    div.zone { border: none; border-radius: 6mm; background: #FFFFFF; border-collapse: collapse; padding:3mm; font-size: 2.7mm;}
    h1 { padding: 0; margin: 0; color: #DD0000; font-size: 7mm; }
    h2 { padding: 0; margin: 0; color: #222222; font-size: 5mm; position: relative; }
-->

.body {
    margin: 8px; padding: 0;
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
    font-size: 9px;

}


.body, td, th {
    /*font-family: Arial, Helvetica, sans-serif;*/
    /*font-size:12px;*/
    font-family: Helvetica;

}
.articulos{
font-size: 10px;


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
    require_once "../modelos/Cotizacion.php";
    $venta=new Cotizacion();
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
    $total_venta=$regc->total_venta;
    

    $rsptad= $venta->ventadetalle($_GET["id"]);
    $item=0;
    $sumdescuento=0.00;
    $sumigv=0.00;
 ?>
    
        <!-- <page_header> -->



            <table id="encabezado" align="center" width="100%">
                <tr class="fila">
                    <td style="height: 15px"></td>
                    <td></td><td></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: left; width: 23%; "> </td>
                    <td style="text-align: center; font-size: 18px;font-weight:bold;"> <img style="width: 100%;" src="../files/perfil/logo01.png"></td>
                    <!--<td style="text-align: center; font-size: 18px;font-weight:bold;"><?php echo $razon_social ?></td>-->
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
        <table align="center" border="none"  style="width: 100%;">
            <tr>
                  <td style="padding-bottom: 7px">___________________________________________</td>
            </tr>   
            <tr>
                  <td align="center" style="font-weight:bold;">PROFORMA N° <?php echo $serie.' - '.$correlativo ?> </td>
            </tr>  
            <tr>
                 
            </tr>   
        </table>
            <table style="width: 100%;">
                <tr style="">
                    <?php 
                        list($anno,$mes,$dia)=explode('-',$fecha)
                         ?>
                        <td style="width: 13%">Cliente</td>
                        <td style="width: 80%">: <?php echo $cliente; ?></td>
                        
                </tr>
                <tr>
                    <td style="width: 13%">R.U.C.</td>
                    <td style="width: 80%">: <?php echo $ruc; ?> </td>
                </tr>
                <tr>
                    <td style="width: 13%">Fecha</td>
                    <td style="width: 80%">: <?php echo $dia.'-'.$mes.'-'.$anno; ?></td>
                </tr>
            </table> 
   <div class="articulos">
        <table align="center" border="none"  width="100%">
                 
                <tr>
                  <td>--------------------------------------------------------------------------------------</td>
                </tr>
        </table> 
        
            <table border="" style="width: 100%;">
                <tr >
                    <th style="width: 1%; text-align:center"></th>
                    <th style="width: 54%; text-align:center">Descripción</th>
                    <th style="width: 10%; text-align:center">Cant.</th>
                    <th style="width: 15%; text-align: center;">P. Unit.</th>
                    <th style="width: 20%; text-align: center;">Importe</th>
                     <th style="width: 1%; text-align:center"></th>
                 </tr>
               </table>
            <table align="center" border="none"  width="100%">
                <tr>
                 <td>--------------------------------------------------------------------------------------</td>
                </tr>
            </table>
        </div>
       
        <div class="articulos">
            
           
            <table border="" style="width: 80%;">
                
                <tbody class="cuerpoM">
                                <?php while($regd=$rsptad->fetch_object()){ 
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

                    $sumigv+=$newigv;

                    if($item%2==0){
                        $estilo='silver';
                    }else{
                        $estilo='clouds';
                    }
                    ?>
                    <tr style="text-align:left">
                    <td class="<?php echo $estilo; ?>" style="text-align: left; width: 1%"> </td>
                    <td class="<?php echo $estilo; ?>" style="text-align: left; width: 66%"><?php echo $regd->articulo.' '.$regd->serieCotizacion; ?></td>
                    <td class="<?php echo $estilo; ?>" style="text-align: center; width: 16%"><?php echo $regd->cantidad; ?></td>
                    <!--<td class="<?php echo $estilo; ?>" style="width: 5%"><?php echo $regd->cantidad; ?></td>-->
                    <td class="<?php echo $estilo; ?>" style="text-align: center; width: 18%"><?php echo $regd->precio_venta; ?></td>
                    <td class="<?php echo $estilo; ?>" style="text-align: center; width: 20%">
                <?php $subTotal =  $regd->precio_venta*$regd->cantidad-$regd->descuento; echo number_format($subTotal,2);?></td>
                <td style="width:1%"> &nbsp;&nbsp;</td>
                </tr>
            </tbody>
        </table>

            <table border="" style="width: 80%;">
                
                <tbody class="cuerpoM">
                    <?php }?>

                
                    <tr  style="font-size: 9px;" align="right">
                            <td style="width: 70%;"></td>
                        <td style="font-size: 9px;">Op. gravada:</td>
                        <td >S/.<?php echo $op_gravadas; ?></td>
                    </tr>
                    <tr style="font-size: 9px;" align="right">
                            <td style="width: 70%;"></td>
                        <td style="font-size: 9px;">IGV:</td>
                        <td >S/. <?php echo round($sumigv,2); ?></td>

                    </tr>
                    <tr style="font-size: 9px;" align="right">
                        <td style="width: 70%;"></td>
                        <td style="font-size: 9px;">Importe:</td>
                        <td >S/. <?php echo number_format($total_venta,2,'.',','); ?></td>
                    </tr>
                  
                </tbody>

            </table>

             <table border="" style="width: 90%;">
                <?php 
            require_once "numeroALetras.php";
            $letras = NumeroALetras::convertir($total_venta);
            list($num,$cen)=explode('.',$total_venta);
             ?>
                <tr>
                    <th style="width: 5%; text-align:center"></th>
                    <td style="font-size: 9px; width: 100%;">SON: <?php echo $letras.' Y '.$cen; ?>/100 SOLES</td>
                 </tr>
               </table>
            <table border="" style="width: 100%;">
                <tr>
                
             </tr>
            </table>
        </div>
            <table border="" style="width: 100%;">
                <tr>
                 
             </tr>
            </table>
            <table align="center" border="none"  width="100%">
                <tr>
                 <td>----------------------------------------------------------------------</td>
                </tr>
           

                <tr>
                  <td align="center">CONDICIONES GENERALES</td>

                </tr>
               
                <tr>
                  <td ><p class="text">
                
                Validez de la oferta: 30 dias<br>
                Cuenta Corriente BCP: 191-2288026-0-72<br>
                Cuenta del titular Gamer Vision E.I.R.L.<br>
                </p></td>
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