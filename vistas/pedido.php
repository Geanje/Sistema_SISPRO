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
if($_SESSION['ventas']==1)
{
 ?>
 <!--Contenido-->
       <!-- Content Wrapper. Contains page content -->
       <div class="content-wrapper">

         <!-- Main content -->
         <section class="content">
             <div class="row">
               <div class="col-md-12">
                   <div class="box">
                     <div class="box-header with-border">
                         <div class="box-tools pull-right">
                                  <!-- opciones header -->
                         </div>
                     </div>
                     <!-- /.box-header -->
                     <!-- centro -->
                     <div class="panel-body table-responsive"  id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Usuario</th>
                            <th>Documento</th>
                            <th>Numero</th>
                            <th>TotalVenta</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>

                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Usuario</th>
                            <th>Documento</th>
                            <th>Numero</th>
                            <th>TotalVenta</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
                     </div>
                     <div class="panel-body" style="height: 400px;" id="formularioregistros">          
                          <!--formularios-->
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
  <script src="scripts/pedido.js"></script>

    <?php
    }
    ob_end_flush();

     ?>
