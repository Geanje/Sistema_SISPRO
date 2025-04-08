<?php
if(strlen(session_id()) < 1)
  session_start();
  date_default_timezone_set('America/Lima'); 
// En windows
setlocale(LC_TIME, 'spanish');
 ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.blue-deep_purple.min.css" />
    <link rel="stylesheet" href="libs/mdl/getmdl-select.min.css">
    <!-- <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet"> -->
    <link rel="stylesheet" type="text/css" href="libs/realperson/jquery.realperson.css"> 
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="fonts/fontello/css/fontello.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap-offset-right.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Consulta - Comprobantes</title>
     <link rel="icon" href="img/logo.ico">
    <style>
        
    </style>
</head>
<body>
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
    <div class="header">
        <div class=" header-tex center-block">
                      <h2>Consulta de Comprobantes Electrónicos</h2>
        </div>
           
      
    </div>

    <div class="container">
        <div class="center-block">
           
            <div class="col-lg-4 col-lg-offset-1 col-md-4 col-md-offset-1 col-sm-12 col-xs-12 no-padding" style="z-index:1">
                <!-- Slider -->

                <div class="mlt-carousel">
                    <div id="myCarousel" class="carousel slide carousel-fade" data-ride="carousel">                       
                        <div class="carousel-inner" role="listbox">
                            <div class="item active">
                                <img class="img-responsive center-block" src="img/step1.png" alt="step1">
                                <div class="item-content">
                                    <h3>GAMER VISION E.I.R.L.</h3>
                                    <p>Jr. Leticia N° 948 Int. 1 Lima </p>
                                    <p>Cel.: 986 249 416</p>
                                    <p>gamersvs.ventas@gmail.com</p>
                                </div>
                            </div>
                            <div class="item">
                                <img class="img-responsive center-block" src="img/step2.png" alt="step2">
                                <div class="item-content">
                                    <h3>GAMER VISION E.I.R.L.</h3>
                                    <p>Jr. Leticia N° 948 Int. 1 Lima </p>
                                    <p>Cel.: 986 249 416</p>
                                    <p>gamersvs.ventas@gmail.com</p>
                                </div>
                            </div>
                            <div class="item">
                                <img class="img-responsive center-block" src="img/step3.png" alt="step3">
                                <div class="item-content">
                                    <h3>GAMER VISION E.I.R.L.</h3>
                                    <p>Jr. Leticia N° 948 Int. 1 Lima </p>
                                    <p>Cel.: 986 249 416</p>
                                    <p>gamersvs.ventas@gmail.com</p>
                                </div>
                            </div>
                        </div>
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                            <li data-target="#myCarousel" data-slide-to="1"></li>
                            <li data-target="#myCarousel" data-slide-to="2"></li>
                        </ol>
                    </div>
                    <!--mlt-carousel-->
                </div>
                <!-- Slider -->
            </div>
            <!-- Login -->

            <div class="col-lg-6 col-lg-offset-right-1 col-md-6 col-md-offset-right-1 col-sm-12 col-xs-12 no-padding">
                <div class="mlt-content">
                    
                     <h4>Seleciones el tipo de comprobante electrónico<br>
                        Luego ingrese la seria y el numero correlativo
                     </h4>
                    <div id="myTabContent" class="tab-content">
                        <div class="tab-pane fade in active" id="register">
                            <!--register form-->

                            <form action="validate.php" method="POST">
                                <div class="col-lg-10 col-lg-offset-1 col-lg-offset-right-1 col-md-10 col-md-offset-1 col-md-offset-right-1 col-sm-12 col-xs-12 pull-right ">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select">
                                        <h5>Selecione tipo Comprobante</h5>
                                        <input type="text" value="" class="mdl-textfield__input" id="sl_comprobante" readonly >
                                        <input type="hidden" value="" name="sl_comprobante"> 
                                        <i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i> 
                                        <!--</div><label for="sample4" >Tipo Comprobante</label>-->
                                        <ul for="sample4" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
                                            <li class="mdl-menu__item" data-val="1">Factura</li>
                                            <li class="mdl-menu__item" data-val="3">Boleta de venta</li>
                                        </ul>
                                    </div>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                         <h5>Serie</h5>
                                        <input class="mdl-textfield__input " type="text" id="serie" name="serie" placeholder="F001" required>
                                        <!--<label class="mdl-textfield__label " for="fullName ">Serie</label>-->
                                    </div>
                                    <div style="text-align: initial;">
                                        
                                    </div>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                         <h5>Número</h5>
                                        <input class="mdl-textfield__input " type="text" id="numero" name="numero" placeholder="0000001" required>
                                        <!--<label class="mdl-textfield__label " for="fullName ">Número</label>-->
                                    </div>

                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input " type="text" id="defaultReal" name="defaultReal" required>
                                    </div>
                                    <input type="submit" id="btn-xd" class="btn lt-register-btn" value="Consultar">

                                </div>
                            </form>
                            <!--register form-->
                        </div>

                    </div>
                </div>
                <!--Login-->
            </div>
            <!--center-block-->


        </div>        
        <!--container-->
    </div>


    <div class="container-fluid px-lg-5 px-3">
        <div class="row footer-top">
            <div class="service-thumb-home text-center footer-text">
                <div class="col-lg-3">                  
                    <!--<a href="articulo.php"><i class="fa fa-circle-o"></i> Medicamentos</a>-->
                    
                </div>
                <div class="col-lg-7">
                       Copyright © 2020  | <b><a href="https://solucionesintegralesjb.com/" target="_blank">Soluciones Integrales JB S.A.C.</a></b>
                 
                </div>
            </div>
        </div>
    </div>

    <!-- //Footer -->                        


    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js "></script>
    <script src="libs/mdl/material.min.js "></script>
    <script src="libs/mdl/getmdl-select.min.js"></script>
    <script type="text/javascript" src="libs/realperson/jquery.plugin.js"></script> 
    <script type="text/javascript" src="libs/realperson/jquery.realperson.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js "></script>
    <script>
        $(function() {
            $('#defaultReal').realperson();
        });

        $("#btn-xd").click(function(){
            window.open("index.html")
        });

    </script>

</body>

</html>