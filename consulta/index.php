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

<style type="text/css">
		 table {color:black;
		 border: none;
            width: 100%;
		 }
		

		 .header{
		 	
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

		 .factura{		 
		 	font-size: 16px;
		 	width: 28%;
		 	height:10px;
		 	border: 1px solid red;
		 	text-align: center;
		 	border-collapse: separate;
	        border-spacing: 10;
	        border: 1px solid black;
	        border-radius: 15px;
	        -moz-border-radius: 20px;
	        padding: 2px;
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
		 	border-top-left-radius: 10px;
		 	border-top-right-radius: 10px;		 	
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

		 .silver{
			background:white;
			padding: 3px 4px 3px;
		}
		.clouds{
			background:#ecf0f1;
			padding: 3px 4px 3px;
		}
		.razon-social{
		 	color: red;
		 	font-size:13px;
		 	font-weight:bold;
		 	text-transform: uppercase;
		 }
		.rubro{
			color: black;
		 	font-size:18px;
		 	font-weight:bold;
		 	text-transform: uppercase;
		}
		.nombre{
			color: #263C9A;
		 	font-size:35px;
		 	font-weight:bold;
		 	text-transform: uppercase;
		}
		.info{
		 	width: 100%; 
		 	font-size:14px;
		 	text-align:justify;
             padding: 20px;
		 }
		.cuadro-cliente{	
		 	border-collapse: separate;
	        border-spacing: 10;
	        border: 1px solid black;
	        border-radius: 15px;
	        -moz-border-radius: 20px;
	        padding: 2px;
	        width: 100%;
		}
		.cuadro-articulo{	
		 	border-collapse: separate;
	        border-spacing: 10;
	        border: 1px solid black;	       
	        width: 100%;
	        border-color: #087DA2;
		}
		.boder{	
		 	
		 	border-collapse:collapse;
		 	border-color:#087DA2;    
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
        $nombre_comercial=$reg['nombre_comercial'];
        $direccion=$reg['direccion'];
        $distrito=$reg['distrito'];
        $provincia=$reg['provincia'];
        $departamento=$reg['departamento'];
        $telefono=$reg['telefono'];
        $email=$reg['email'];
       $logo=$reg['logo'];

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
                                    <h3><?php echo $nombre_comercial; ?></h3>
                                    <h3>RUC: <?php echo $rucp; ?></h3>
                                    <p> <?php echo $direccion; ?> </p>
                                    <p> <?php echo $distrito; ?> - <?php echo $provincia; ?>  - <?php echo $departamento; ?></p>
                                    <p>Cel.: <?php echo $telefono; ?></p>                                   
                                </div>
                            </div>
                            <div class="item">
                                <img class="img-responsive center-block" src="img/step2.png" alt="step2">
                                <div class="item-content">
                                    <h3><?php echo $nombre_comercial; ?></h3>
                                    <h3>RUC: <?php echo $rucp; ?></h3>
                                    <p> <?php echo $direccion; ?> </p>
                                    <p> <?php echo $distrito; ?> - <?php echo $provincia; ?>  - <?php echo $departamento; ?></p>
                                    <p>Cel.: <?php echo $telefono; ?></p>     
                                </div>
                            </div>
                            <div class="item">
                                <img class="img-responsive center-block" src="img/step3.png" alt="step3">
                                <div class="item-content">
                                    <h3><?php echo $nombre_comercial; ?></h3>
                                    <h3>RUC: <?php echo $rucp; ?></h3>
                                    <p> <?php echo $direccion; ?> </p>
                                    <p> <?php echo $distrito; ?> - <?php echo $provincia; ?>  - <?php echo $departamento; ?></p>
                                    <p>Cel.: <?php echo $telefono; ?></p>        
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
                    <h4 class="info">
                        Para la descarga de su comprobante primero debe seleccionar el tipo de comprobante, después ingresar la serie y el numero correlativo.
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
                                    <br><br>
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
            window.open("index.php")
        });

    </script>

</body>

</html>