<?php
//Activar el almacenamiento en el buffer
ob_start();
session_start();

if(!isset($_SESSION["nombre"]))
{
  header("Location:index.php");
}
else {
require 'header.php';

if($_SESSION['escritorio']==1)
{
 ?>
 <!--Contenido-->
 <link rel="stylesheet" type="text/css" href="css/iconos.css">

       <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
          <div class="row">
              <div class="col-md-12">
                <div class="box">
                <h1>BIENVENIDO</h1>
                <h2>SELECCIONA UN MÓDULO A MOSTRAR</h2>

                <div class="container2">
                  <div class="module-container">
                    <div class="module" data-href="articulo.php">
                      <img class="module-image" src="images/img/registro_articulo.png" alt="Registro de Artículos">
                      <p>Registro de Artículos</p>
                    </div>
                    <div class="module" data-href="precio-articulos.php">
                      <img class="module-image" src="images/img/precio_articulo.png" alt="Precio de Artículos">
                      <p>Precio de Artículos</p>
                    </div>
                    <div class="module" data-href="proveedor.php">
                      <img class="module-image" src="images/img/proveedor.png" alt="Proveedores">
                      <p>Proveedores</p>
                    </div>
                    <div class="module" data-href="ingreso.php">
                      <img class="module-image" src="images/img/ingres_producto.png" alt="Ingreso de Productos">
                      <p>Ingreso de Productos</p>
                    </div>
                    <div class="module" data-href="cliente.php"> 
                      <img class="module-image" src="images/img/cliente.png" alt="Clientes">
                      <p>Clientes</p>
                    </div>
                    <div class="module" data-href="venta.php">
                      <img class="module-image" src="images/img/boleta_factura.png" alt="Boleta y Factura">
                      <p>Boleta y Factura</p>
                    </div>
                    <div class="module" data-href="ticket.php">
                      <img class="module-image" src="images/img/nota_venta.png" alt="Nota de Venta">
                      <p>Nota de Venta</p>
                    </div>
                    <div class="module" data-href="proforma.php">
                      <img class="module-image" src="images/img/proforma.png" alt="Proformas">
                      <p>Proformas</p>
                    </div>
                    <div class="module" data-href="notacredito.php">
                      <img class="module-image" src="images/img/nota_credito.png" alt="Nota de Crédito">
                      <p>Nota de Crédito</p>
                    </div>
                    <div class="module" data-href="comprasfecha.php">
                      <img class="module-image" src="images/img/consulta_compra.png" alt="Consulta de Compra">
                      <p>Consulta de Compra</p>
                    </div>
                    <div class="module" data-href="ventasfechausuario.php">
                      <img class="module-image" src="images/img/venta_usuario.png" alt="Consulta de Venta por Usuario">
                      <p>Consulta Venta por Usuario</p>
                    </div>
                    <div class="module" data-href="venta_mensual.php">
                      <img class="module-image" src="images/img/reporte_mensual.png" alt="Reporte Mensual">
                      <p>Reporte Mensual</p>
                    </div>
                    <div class="module" data-href="factura.php">
                      <img class="module-image" src="images/img/5.png" alt="Reporte Mensual">
                      <p>Facturación con IGV</p>
                    </div>
                    <div class="module" data-href="factura_sin.php">
                      <img class="module-image" src="images/img/5.png" alt="Reporte Mensual">
                      <p>Facturación Sin IGV</p>
                    </div>
                    <div class="module" data-href="servicios-soporte.php">
                      <img class="module-image" src="images/img/8.png" alt="Reporte Mensual">
                      <p>Soporte Técnico</p>
                    </div>
                    <div class="module" data-href="soporte_pago_mensual.php">
                      <img class="module-image" src="images/img/16.png" alt="Reporte Mensual">
                      <p>Consulta total pagos mensual</p>
                    </div>
                    <div class="module" data-href="desarrollo-soporte.php">
                      <img class="module-image" src="images/img/15.png" alt="Reporte Mensual">
                      <p>Servicio de Desarrollo</p>
                    </div>
                    <div class="module" data-href="reg_servicio.php">
                      <img class="module-image" src="images/img/13.png" alt="Reporte Mensual">
                      <p>Registro de Servicio</p>
                    </div>
                    <div class="module" data-href="registro_servicios.php">
                      <img class="module-image" src="images/img/14.png" alt="Reporte Mensual">
                      <p>Registro de Contrato</p>
                    </div>
                    <div class="module" data-href="pago_servicios.php">
                      <img class="module-image" src="images/img/12.png" alt="Reporte Mensual">
                      <p>Pago de Servicios</p>
                    </div>
                    <div class="module" data-href="sucursal.php">
                      <img class="module-image" src="images/img/gente.png" alt="Reporte Mensual">
                      <p>Registro de Personas</p>
                    </div>
                    <div class="module" data-href="prestamo.php">
                      <img class="module-image" src="images/img/9.png" alt="Reporte Mensual">
                      <p>Préstamo de Productos</p>
                    </div>
                    <div class="module" data-href="prestamo-productos.php">
                      <img class="module-image" src="images/img/10.png" alt="Reporte Mensual">
                      <p>Consulta de Productos Prestados</p>
                    </div>
                    <div class="module" data-href="reporte-general.php">
                      <img class="module-image" src="images/img/6.png" alt="Reporte Mensual">
                      <p>Historial Productos comprados</p>
                    </div>
                    <div class="module" data-href="venta_mensual.php">
                      <img class="module-image" src="images/img/tarea.png" alt="Reporte Mensual">
                      <p>Consulta Ventas por Cliente</p>
                    </div>
                    <div class="module" data-href="ventasfechausuario.php">
                      <img class="module-image" src="images/img/graduacion.png" alt="Reporte Mensual">
                      <p>Consulta Ventas por Usuario</p>
                    </div>
                    <div class="module" data-href="reporte_contable.php">
                      <img class="module-image" src="images/img/7.png" alt="Reporte Mensual">
                      <p>Reporte Contable</p>
                    </div>
                    <div class="module" data-href="reporte_notacredito.php">
                      <img class="module-image" src="images/img/4.png" alt="Reporte Mensual">
                      <p>Reporte Nota de Crédito</p>
                    </div>
                    <div class="module" data-href="practicantes.php">
                      <img class="module-image" src="images/img/11.png" alt="Reporte Mensual">
                      <p>Practicantes</p>
                    </div>
                    <div class="module" data-href="configuraciones.php">
                      <img class="module-image" src="images/img/config.png" alt="Reporte Mensual">
                      <p>Configuración</p>
                    </div>
                    <div class="module" data-href="manual.php">
                      <img class="module-image" src="images/img/estrella.png" alt="Reporte Mensual">
                      <p>Manual de usuario</p>
                    </div>
                    

                  </div>
                </div>
              <!--Fin centro -->
                </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->

       </section><!-- /.content -->

     </div><!-- /.content-wrapper -->
   <!--Fin-Contenido-->

   <?php
   }
   else {
     require 'noacceso.php';
  }
require 'footer.php';

    ?>
<script>
  const modules = document.querySelectorAll(".module");

  modules.forEach((module) => {
    module.addEventListener("click", () => {
      const targetURL = module.getAttribute("data-href");
      if (targetURL) {
        window.location.href = targetURL;
      }
    });
  });
</script>
<?php
}
ob_end_flush();

 ?>
