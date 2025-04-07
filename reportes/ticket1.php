
   
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
            padding-left: 20px; 
            padding-right: 20px;
            font-size:14px;
         }
         .articulos{
            padding-left: 20px; 
            padding-right: 20px;
            font-size:12px;
            height: 224px;
            min-height: 215px;
         }
         .total{
            padding-left: 20px; 
            padding-right: 20px;
            font-size:14px;

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
        .tbl-main2{
            background: silver;
            position: static;
            height: 214px;
            min-height: 214px;
        }

    </style>
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
    require_once "../modelos/Venta2.php";
    $venta=new Venta2();
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
<page format="100x100" orientation="P" backcolor="#AAAACC" style="font: arial;">



        <form action>
            <input type="hidden" name="rucempresa">
            <input type="hidden" name="seriecompro">
            <input type="hidden" name="correlativocompro">
        </form>
        <br>
        <br><br><br><br><br><br><br><br><br><br>

        <div class="cliente">

            <table >
                <tr>
                    <td style="text-align: left; width: 8%; height: 15">&nbsp;</td>
                    <td style="text-align: left; width: 10%"> <?php echo date('d'); ?></td>
                    <td style="width: 20%"> <?php echo date('m'); ?></td>
                    <td style="width: 20%"><?php echo date('Y'); ?></td>
                </tr>
            </table>
            <table >
                <tr>
                    <td style="text-align: left; width: 8%; height: 15">&nbsp;</td>
                    <td style="text-align: left; width: 65%"> <?php echo $cliente; ?></td>
                    <td style="width: 22%"> <?php echo $ruc; ?></td>
                    <td style="width: 5%"></td>
                </tr>
            </table>
            <table >
                <tr>
                    <td style="text-align: left; width: 8%; height: 40">&nbsp;</td>
                    <td style="text-align: left; width: 70%"><?php echo $direccioncliente; ?></td>
                    <td style="width: 10%"></td>
                    
                </tr>
            </table>
        </div>
        
        <div class="articulos">
            
                
                   <!-- <th style="width: 5%; text-align:center">Item</th>-->
                  <!--  <td style="text-align: left; width: 6%; height: 15">&nbsp;</td>
                    <td style="text-align: left; width: 8%">CANT.</td>
                    <td style="text-align: left; width: 8%"> MED.</td>
                    <td style="text-align: left; width: 54%"> DESCRIPCIÓN</td>-->

                    <!--<th style="width: 10%; text-align:center">Descuento</th>-->
                    <!--<td style="text-align: left; width: 11%"> PRECIO UNIT.</td>
                    <td style="text-align: left; width: 11%"> PRECIO VENTA</td>-->
                    <!--<th style="width: 5%; text-align: center;">IGV</th>-->
                    <!--<th style="width: 10%; text-align: left">Subtotal</th>-->                          
                <br><br>
            
                <table height: 15% >
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

                <tr  >
                    <td style="text-align: left; width: 5%; height: 15">&nbsp;</td>         
                    <td style="text-align: left; width: 5%"><?php echo $regd->cantidad; ?></td>
                    <td style="text-align: left; width: 8%"><?php echo $medida; ?></td>
                    <td style="text-align: left; width: 57%"><?php echo $regd->articulo; ?></td>
                    <td style="text-align: left; width: 5%"></td>
                    <!--<td class="<?php echo $estilo; ?>"><?php /*echo $regd->descuento;*/ ?></td>-->
                    <td style="text-align: left; width: 10%"><?php echo $regd->precio_venta;?></td>
                    <td style="text-align: left; width: 10"><?php  echo number_format(($regd->precio_venta*$regd->cantidad-$regd->descuento) , 2, '.', ',');?></td>
                    <!--<td class="<?php echo $estilo; ?>"><?php/* echo round($newigv,2);*/ ?></td>-->
                    <!--<td class="<?php echo $estilo; ?>"><?php/*echo $regd->precio_venta*$regd->cantidad-$regd->descuento;*/ ?></td>-->
                </tr>

                 <?php }?>
                
                <br>
            </table>
                

            <?php 
            require_once "numeroALetras.php";
            $letras = NumeroALetras::convertir($total_venta);
            list($num,$cen)=explode('.',$total_venta);
             ?>
            
            
        </div>
            <div class="total">

            <table >
                <tr>
                    <td style="text-align: left; width: 5%; height: 15">&nbsp;</td>
                    <td style="text-align: left; width: 75%"> <?php echo $letras.' Y '.$cen; ?>/100 SOLES</td>
                    <td style="text-align: left; width: 5%"></td>
                    <td style="text-align: right; width: 13%"><?php echo number_format(( $op_gravadas), 2, '.', ',');?></td>
                </tr>
            </table>
            <table >
                <tr>
                    <td style="text-align: left; width: 5%; height: 15">&nbsp;</td>
                    <td style="text-align: left; width: 75%"> </td>
                    <td style="text-align: left; width: 5%">18</td>
                    <td style="text-align: right; width: 13%"><?php echo number_format($sumigv,2,'.',','); ?></td>
                </tr>
            </table>
            <table >
                <tr>
                    <td style="text-align: left; width: 5%; height: 15">&nbsp;</td>
                    <td style="text-align: left; width: 75%"> </td>
                    <td style="text-align: left; width: 5%"></td>
                    <td style="text-align: right; width: 13%"><?php echo number_format($total_venta,2,'.',','); ?></td>
                </tr>
            </table>

        </div>
        

        
</page>
        

   