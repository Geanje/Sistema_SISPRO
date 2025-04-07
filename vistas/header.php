<?php
if (strlen(session_id()) < 1)
  session_start();
date_default_timezone_set('America/Lima');

?>
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> Soluciones Integrales JB</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="../public/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../public/css/font-awesome.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../public/css/_all-skins.min.css">
  <link rel="apple-touch-icon" href="../public/img/logo.ico">
  <link rel="shortcut icon" href="images/icono.png">

  <!--   DATATABLES  -->
  <link rel="stylesheet" type="text/css" href="../public/datatables/jquery.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="../public/datatables/buttons.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="../public/datatables/responsive.dataTables.min.css">

  <link rel="stylesheet" type="text/css" href="../public/css/bootstrap-select.min.css">
  <link rel="stylesheet" type="text/css" href="../public/css/sweetalert.css">
  <!-- <link rel="stylesheet" type="text/css" href="../public/css/sweetalert.css"> -->
  <link rel="stylesheet" type="text/css" href="../public/css/principal.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style type="text/css">
    table.verticalDisplay thead {
      float: left;
    }

    table.verticalDisplay thead th {
      display: block;
    }

    table.verticalDisplay tbody {
      float: right;
    }

    table.verticalDisplay tbody td {
      display: block;
    }
  </style>

  <style type="text/css">
    html,
    body {
      height: 100%;
    }

    body {
      margin: 0;
      background: linear-gradient(#f2f2f6, #d3d3e7);
    }

    .container {
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .animated-word {
      font-family: Helvetica;
      letter-spacing: 0.4em;
      font-weight: 900;
      font-size: 100px;
      text-align: center;
      color: #202125;
      cursor: pointer;
      max-width: 900px;
      width: 100%;
      outline: 3px solid;
      outline-color: rgba(71, 126, 232, 0.5);
      outline-offset: 70px;
      transition: all 600ms cubic-bezier(0.2, 0, 0, 0.8);
    }

    .animated-word:hover {
      color: rgba(71, 126, 232, 0.7);
      outline-color: rgba(71, 126, 232, 0);
      outline-offset: 500px;
    }
  </style>


</head>

<body class="hold-transition skin-blue sidebar-mini">
  <?php
  require_once "../modelos/Perfil.php";
  $perfil = new Perfil();
  $rspta = $perfil->cabecera_perfil();
  // $rspta= Perfil::cabecera_perfil();
  $reg = $rspta->fetch_assoc();
  $rucp = $reg['ruc'];
  $razon_social = $reg['razon_social'];
  $nombre_comercial = $reg['nombre_comercial'];
  $direccion = $reg['direccion'];
  $distrito = $reg['distrito'];
  $provincia = $reg['provincia'];
  $departamento = $reg['departamento'];
  $telefono = $reg['telefono'];
  $email = $reg['email'];
  $logo = $reg['logo'];

  ?>
  <div class="wrapper">
    <!-- -light -->
    <header class="main-header">

      <!-- Logo -->
      <a href="" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="images/no.png" /></span>
        <!-- logo for regular state and mobile devices -->
        <div class="img1">
          <img src="images/logo_sis.png" alt="" />
        </div>
      </a>

      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Navegación </span> <b>&nbsp; &nbsp; <?php echo $nombre_comercial; ?> - <?php echo $direccion; ?></a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- Messages: style can be found in dropdown.less-->

            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="user-image" alt="User Image">
                <span class="hidden-xs"><?php echo $_SESSION['nombre']; ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="img-circle" alt="User Image">
                  <p>
                    Usuario
                    <small></small>
                  </p>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer">

                  <div class="pull-right">
                    <a href="../ajax/usuario.php?op=salir" class="btn btn-default btn-flat">CERRAR SESION</a>
                  </div>
                </li>
              </ul>
            </li>

          </ul>
        </div>

      </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          <li class="header"></li>

          <?php
          if ($_SESSION['escritorio'] == 1) {
            echo '<li>
                  <a href="escritorio.php">
                    <i class="fa fa-tasks"></i> <span>Escritorio</span>
                  </a>
                </li>';
          }
          ?>

          <?php
          if ($_SESSION['almacen'] == 1) {
            echo ' <li class="treeview">
                <a href="#">
                  <i class="fa fa-th-large"></i>
                  <span>Almacén</span>
                  <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                 <li><a href="categoria.php"><i class="fa fa-circle-o"></i> Categorías</a></li>
                  <li><a href="articulo.php"><i class="fa fa-circle-o"></i> Reg. Artículos</a></li>
                  <li><a href="precio-articulos.php"><i class="fa fa-circle-o"></i> Precio de Artículos</a></li>  
                </ul>
              </li>';
          }
          ?>


          <?php
          if ($_SESSION['compras'] == 1) {
            echo '<li class="treeview">
                <a href="#">
                  <i class="fa fa-shopping-cart"></i>
                  <span>Compras</span>
                   <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">                  
                  <li><a href="proveedor.php"><i class="fa fa-circle-o"></i> Reg. Proveedores</a></li>
                  <li><a href="ingreso.php"><i class="fa fa-circle-o"></i> Ingresos Almacén</a></li>                 
                </ul>
              </li>';
          }
          ?>
          <?php
          if ($_SESSION['mantenimiento'] == 1) {
            echo '<li class="treeview">
                  <a href="#">
                    <i class="fa fa-cog"></i><span>Mantenimiento</span>
                    <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                  <li><a href="kardex.php"><i class="fa fa-circle-o"></i> Kardex</a></li> 
                  <li><a href="mant-producto.php"><i class="fa fa-circle-o"></i> Mant. Productos</a></li>    
                  </ul>
                </li>';
          }
          ?>

          <?php
          if ($_SESSION['ventas'] == 1) {
            echo '<li class="treeview">
                  <a href="#">
                    <i class="fa fa-line-chart"></i>
                    <span>Ventas</span>
                    <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                  <li><a href="venta.php"><i class="fa fa-circle-o"></i> Factura y Boleta</a></li>
                  <li><a href="ticket.php"><i class="fa fa-circle-o"></i> Nota de Venta</a></li>                 
                  <li><a href="proforma.php"><i class="fa fa-circle-o"></i> Proforma</a></li>  
                  <li><a href="notacredito.php"><i class="fa fa-circle-o"></i> Nota Crédito</a></li>                 
                  <li><a href="guia.php"><i class="fa fa-circle-o"></i> Guia de Remisión</a></li>   
                  <li><a href="cliente.php"><i class="fa fa-circle-o"></i> Reg. Clientes</a></li>
                  <li><a href="venta-credito.php"><i class="fa fa-circle-o"></i> Venta a Crédito</a></li>
                  <li><a href="pedido.php"><i class="fa fa-circle-o"></i> Pedidos en Línea</a></li>
                  <li><a href=""><i class="fa fa-circle-o"></i> Auditoria Comprobantes</a></li>                                  
                  <!--<li><a href="venta-credito.php"><i class="fa fa-circle-o"></i> Venta Crédito</a></li> -->  
                  </ul>
                </li>';
          }
          ?>
          <?php
          if ($_SESSION['servicio'] == 1) {
            echo '<li class="treeview">
                  <a href="#">
                    <i class="fa fa-cubes"></i><span>Servicios</span>
                    <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="factura.php"><i class="fa fa-circle-o"></i> Facturación con IGV</a></li>
                    <li><a href="factura_sin.php"><i class="fa fa-circle-o"></i> Facturación sin IGV</a></li>
                    <li><a href="NCServicio.php"><i class="fa fa-circle-o"></i> Nota Crédito</a></li>
                    <li><a href="orden_pago.php"><i class="fa fa-circle-o"></i> Orden de Pago</a></li>
                    <li><a href="cotizacion.php"><i class="fa fa-circle-o"></i> Cotizacion</a></li>
                  </ul>
                </li>';
          }
          ?>
          <?php
          if ($_SESSION['soporte'] == 1) {
            echo '<li class="treeview">
                  <a href="#">
                    <i class="fa fa-wrench"></i><span>Soporte Técnico</span>
                    <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">                   
                    <li><a href="servicios-soporte.php"><i class="fa fa-circle-o"></i> Soporte Técnico</a></li>  
                    <li><a href="SalidaArticulos.php"><i class="fa fa-circle-o"></i> Salida Producto</a></li>
                    <li><a href="servicio_cliente.php"><i class="fa fa-circle-o"></i> Registro Clientes</a></li> 
                    <li><a href="registro_tecnico.php"><i class="fa fa-circle-o"></i> Registro Técnico</a></li> 
                    <li><a href="soporte_pago_mensual.php"><i class="fa fa-circle-o"></i>Reporte de pagos</a></li> 
                  </ul>
                </li>';
          }
          ?>
          <?php
          if ($_SESSION['desarrollo'] == 1) {
            echo '<li class="treeview">
                  <a href="#">
                    <i class="fa fa-code"></i><span>Desarrollo</span>
                    <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">                  
                    <li><a href="desarrollo-soporte.php"><i class="fa fa-circle-o"></i> Servicio Desarrollo</a></li> 
                  </ul>
                </li>';
          }
          ?>
          <?php
          if ($_SESSION['pag_servicio'] == 1) {
            echo '<li class="treeview">
                  <a href="#">
                    <i class="fa fa-clipboard"></i><span>Pago Servicio</span>
                    <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">                  
                  <li><a href="reg_servicio.php"><i class="fa fa-circle-o"></i>Registro de Servicio</a></li>
                  <li><a href="registro_servicios.php"><i class="fa fa-circle-o"></i>Registro de Contrato</a></li>
                  <li><a href="pago_servicios.php"><i class="fa fa-circle-o"></i>Pago de Servicios</a></li>
                  </ul>
                </li>';
          }
          ?>
          <?php
          if ($_SESSION['sucursal'] == 1) {
            echo '<li class="treeview">
                  <a href="#">
                    <i class="fa fa-credit-card"></i><span>Prestamo</span>
                    <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="sucursal.php"><i class="fa fa-circle-o"></i> Personas</a></li>
                    <li><a href="prestamo.php"><i class="fa fa-circle-o"></i> Préstamo productos</a></li>
                    <li><a href="prestamo-productos.php"><i class="fa fa-circle-o"></i> Consulta de préstamos</a></li>
                  </ul>
                </li>';
          }
          ?>
          <?php
          if ($_SESSION['consultac'] == 1) {
            echo '<li class="treeview">
                <a href="#">
                  <i class="fa fa-pie-chart"></i> <span>Consulta Compras</span>
                  <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="comprasfecha.php"><i class="fa fa-circle-o"></i> Reporte Compras</a></li>
                  <li><a href="reporte-general-c.php"><i class="fa fa-circle-o"></i> Reporte General C</a></li>
                </ul>
              </li>';
          }
          ?>
          <?php
          if ($_SESSION['consultav'] == 1) {
            echo '<li class="treeview">
                    <a href="#">
                      <i class="fa fa-area-chart"></i> <span> Consulta de Ventas</span>
                      <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">                      
                      <li><a href="ventas-fecha-cliente.php"><i class="fa fa-circle-o"></i>  Ventas por Cliente</a></li>
                      <li><a href="ventasfechausuario.php"><i class="fa fa-circle-o"></i>  Ventas por Usuario</a></li>
                      <li><a href="reporte-mensual.php"><i class="fa fa-circle-o"></i> Producto mas Vendido </a></li>
                      <li><a href="reporte-general.php"><i class="fa fa-circle-o"></i> Reporte General</a></li>
                      
                      <li><a href="venta_mensual.php"><i class="fa fa-circle-o"></i> Reporte Venta Mensual</a></li>
                    </ul>
                  </li>';
          }
          ?>

          <?php
          if ($_SESSION['contabilidad'] == 1) {
            echo '<li class="treeview">
                    <a href="#">
                    <i class="fa fa-money"></i><span> Contabilidad</span>
                      <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">  
                    <li><a href="reporte_contable.php"><i class="fa fa-circle-o"></i> Reporte Contable</a></li>
                    <li><a href="reporte_notacredito.php"><i class="fa fa-circle-o"></i> Reporte Nota de Crédito</a></li>
                    <li><a href="rentabilidad-mensual.php"><i class="fa fa-circle-o"></i> Rentabilidad Mensual</a></li>
                    <li><a href="estadistica.php"><i class="fa fa-circle-o"></i> Estadística</a></li>

                    </ul>
                  </li>';
          }
          ?>

          <!--  <?php
                if ($_SESSION['consultal'] == 1) {
                  echo '<li class="treeview">
                    <a href="#">                     
                      <i class="fa fa-file-pdf-o"></i><span>Consulta en Línea</span>
                      <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                     <li><a href="probar.php" target="_blank"><i class="fa fa-circle-o"></i> Consultar comprobante</a></li>
                       <li><a href="../consulta/index.php" target="_blank"><i class="fa fa-circle-o"></i> Consultar comprobante</a></li> 
                    </ul>
                  </li>';
                }
                ?>   -->
          <?php
          if ($_SESSION['acceso'] == 1) {
            echo '<li class="treeview">
                    <a href="#">
                      <i class="fa fa-expeditedssl"></i> <span>Acceso</span>
                      <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                      <li><a href="usuario.php"><i class="fa fa-circle-o"></i> Usuarios</a></li>
                      <li><a href="permiso.php"><i class="fa fa-circle-o"></i> Permisos</a></li>
                    </ul>
                  </li>';
          }
          ?>

          <?php
          if ($_SESSION['administracion'] == 1) {
            echo '<li class="treeview">
                    <a href="#">
                      <i class="fa fa-users"></i> <span>Administracion</span>
                      <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                    <li><a href="practicantes.php"><i class="fa fa-circle-o"></i>Practicantes</a></li> 
                    </ul>
                  </li>';
          }
          ?>

          <?php
          if ($_SESSION['configuracion'] == 1) {
            echo '<li class="treeview">
                <a href="#">
                  <i class="fa fa-cogs"></i><span>Configuracion</span>
                  <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="configuraciones.php"><i class="fa fa-circle-o"></i>Datos de Empresa</a></li>
                  <li><a href="igv.php"><i class="fa fa-circle-o"></i>Cambiar IGV</a></li>
                  <li><a href="servidor_Sunat.php"><i class="fa fa-circle-o"></i>Servidor SUNAT</a></li>

                </ul>
              </li>';
          }
          ?>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-download"></i> <span>Descarga</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="descargaTxt.php"><i class="fa fa-circle-o"></i>Descarga de TXT</a></li>
            </ul>
          </li>

          <li>
            <a href="manual.php">
              <i class="fa fa-plus-square"></i> <span>Manual de Usuario</span>
              <small class="label pull-right bg-red">PDF</small>

            </a>
          </li>




        </ul>
      </section>

      <!-- /.sidebar -->
    </aside>