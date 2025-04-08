<!DOCTYPE html>
			<html lang="en">
			<head>
				<meta charset="UTF-8">
				<!-- Styles -->
                <style type="text/css">
                    table {color:black; 
                    border: none;
                    width: 100%;            
                    }		
        
                .header{
                    display: inline-block;
                    padding-left: 30px; 
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
                    height: 0.01px;		 	
                }
                .cliente{
                    padding-left: 40px; 
                    padding-right: 20px;
                    font-size:14px;
                }
                .articulos{
                    padding-left: 40px; 
                    padding-right: 40px;
                    font-size:11px;
                   
        
                }
                .razon-social{
                    font-size:30px;	
                    color: red;
                }
                .cabecera{
                    background:red;
                   color:white;
                   font-size:12px;
                   padding-left: 20px; 
                    padding-right: 20px;
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
                .aviso{		 	
                    font-size: 10pt;
                     text-align: justify;		
                    border: solid 0.3px #000;
                    width: 91%;
                    margin-left: 20px;
                    padding-left: 10px;
                    margin-top: 5px;
                    margin-bottom: 5px;
                    padding-top: 5px;
                    padding-bottom: 5px;
                }
        
            </style>
				<title>Constancia de pedido</title>
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

        $ventaDetalle = $venta->ventadetalle($_GET["id"]);

?>
        <page_footer>
            <table id="">
                <tr class="">
                    <td style="">
                        <div style="text-align: center;" class="foot">
                            Antes de imprimir seleccione sólo lo necesario y PIENSE EN EL MEDIO AMBIENTE. 
                        </div>
                        <hr style="border-color:red;">
                    </td>
                </tr>
            </table><br>
        </page_footer>
            <div class="header"><br><br>
                <table style="width: 100%;">
                    <tr>					
                        <td style="width: 55%;">
                            <!-- Loogo - Razon Social -->
                            <table style="text-align: center;" >		
                                <tr>
                                    <td class="razon-social" style="width: 100%;"><b><?=$razon_social?>.</b></td>
                                </tr>
                                <tr>
                                    <td style="width: 100%;">
                                    <?=$direccion?>
                                    </td>
                                </tr>
                                <tr><td style="width: 100%;">Telef.:  <?=$telefono?></td></tr>
                                <tr><td style="width: 100%;">Email.:  <?=$email?>m</td></tr>
                            </table>
                        </td>				 	
                        <td style="width: 40%;">
                            <!-- Numeracion -->
                            <table >
                                <tr>
                                    <th class="factura" style="width:80%"><br>
                                    <p>ORDEN DE PEDIDO<br><br>
                                    RUC:  <?=$rucp?></p>
                                    <?=$serie?>-<?=$correlativo?><br><br>
                                    </th>
                                </tr>
                            </table>				 		
                        </td>
                        <td style="width: 5%;"></td>
                    </tr>
                </table>
               
            </div>
            <hr style="border-color:red;">	
            <div class="cliente">
                <table style="width: 100%;">
                    <tr><td style="width: 14%;">Cliente</td><td style="width: 80%">: <?=$cliente?></td></tr>
                    <tr><td style="width: 14%;">RUC</td><td style="width: 80%">: <?=$ruc?></td></tr>
                    <tr><td style="width: 14%;">Dirección:</td><td style="width: 80%">:  <?=$direccioncliente?></td></tr>
                </table>
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 14%;">Fecha Emisión </td>
                        <td style="width: 40%;">: <?=$fechaCompleta?></td>
                        <td style="width: 8%;">Moneda</td>
                        <td style="width: 30%;">: <?=$moneda?></td>						
                    </tr>                  
                </table>
            </div>
            <br>
            <div class="articulos">
                <table style="width: 100%; border: solid 0.3px red;">
                    <tr class="cabecera">
                        <th style="width: 60%; text-align: center; height: 12px; padding-top: 5px ">DESCRIPCIÓN</th>
                        <th style="width: 7%; text-align: center; padding-top: 5px ">CAT.</th>
                        <th style="width: 13%; text-align: center; padding-top: 5px ">P. UNIT.</th>
                        <th style="width: 13%; text-align: center; padding-top: 5px ">IMPORTE</th>
                    </tr>
                </table>
            </div>
             
            <!--content-->

            <div class="articulos">
                <table >
                <?php while ($reg = $ventaDetalle->fetch_object()) { ?>
                    <tr style="text-align:left">
                        <td style="width:60%; height: 5px; padding-top: 5px " rowspan="&"><?=$reg->articulo?></td>
                        <td style="width:7%; padding-top: 5px; text-align: center;"> <?=$reg->cantidad?></td>
                        <td style="width:13%; padding-top: 5px; text-align: right;"> <?=number_format(($reg->precio_venta), '2', '.', ',')?>&nbsp;&nbsp;</td>
                        <td style="width:13%; padding-top: 5px; text-align: right;"><?=number_format(($reg->cantidad * $reg->precio_venta), '2', '.', ',')?>&nbsp;&nbsp;</td> 
                    </tr>
                <?php } ?> 
                </table>
            </div>
                    

        <br><br>
            <div class="articulos">	
                <table cellspacing="0" cellpadding="0" border="0.2"  >
                    <tr class="articulos" style="width: 100%; text-align: center">
                        <td style="text-align: center; width:20%">SUBTOTAL</td>
                        <td style="text-align: center; width:20%">IGV (18%)</td>
                        <td style="text-align: center; width:20%">PRECIO TOTAL</td>            
                    </tr>
                    <tr style="width: 100%; text-align: center;">
                        <td style="width:20%">S/&nbsp;&nbsp;&nbsp;&nbsp; <?=number_format($op_gravadas, '2', '.', ',')?></td>                        
                        <td style="width:20%">S/ &nbsp;&nbsp;&nbsp;&nbsp; <?=number_format($total_igv, '2', '.', ',')?></td>
                        <td style="width:20%">S/&nbsp;&nbsp;&nbsp;&nbsp; <?=number_format($total_venta, '2', '.', ',')?></td>
                    </tr>
                </table> 
                <br><br> 
                <?php 
			require_once "numeroALetras.php";
			$letras = NumeroALetras::convertir($total_venta);
			list($num,$cen)=explode('.',$total_venta);
			 ?>       		
                <table>
                    <tr style="width: 80%;">
                        <td style=" width:92%; height: 14px;">SON: <?php echo $letras.' Y '.$cen; ?>/100 DOLARES AMERICANOS</td>	
                    </tr>	
                </table>
                
            </div>
            <hr style="border: solid 0.3px #000;">
            <div class="articulos">	
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 95%; text-align: center;"><b>¡¡¡ Precios,Terminos y Condiciones estan sujetos a cambios sin previo aviso !!!</b></td><br>
                    </tr>
                </table>
            </div>
        <div class="aviso">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 95%;"><b>INSTRUCCIONES PARA PAGAR</b> </td>
                </tr>
                <tr>
                    <td style="width: 95%;">
                        Acercándose a una agencia u oficina del Banco, cajero automático, agente o transferencia por internet, (si el pago es de provincia considerar la comisión por plaza).  
                    </td>				
                </tr>
            </table>
            <table style="width: 100%;">
                <tr>
                    <td style="width: 28%;"><b>CUENTA SOLES BCP</b></td>
                    <td style="width: 25%;">: 191-2288026-0-72</td>
                    <!--<td style="width: 4%;"><b>CCI</b></td>
                    <td style="width: 30%;">: 00219113478934304852</td>-->
                </tr>
                <!--<tr>
                    <td style="width: 28%;"><b>CUENTA SOLES BBVA</b></td>
                    <td style="width: 25%;">: 191-2288026-0-72</td>
                    <td style="width: 4%;"><b>CCI</b></td>
                    <td style="width: 30%;">: 011-264-000200083101-92</td>
                </tr>-->	
            </table>
        </div>
</body>
</html>