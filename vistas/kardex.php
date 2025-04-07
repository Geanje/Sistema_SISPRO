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

if($_SESSION['almacen']==1)
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
                           <h1 class="box-title">REPORTE DE KARDEX</h1>
                         <div class="box-tools pull-right">
                         </div>
                     </div>
                     <!-- /.box-header -->
                     <!-- centro -->
                     <div class="panel-body table-responsive"  id="listadoregistros">

                       <div class="form-group col-lg-6 col-md-3 col-sm-6 col-xs-12" style="display: inline;">
                        <a target="_blank" href="../reportes/reporteKardex.php"><button class="btn btn-success "><i class="fa fa-file"></i> &nbsp;REPORTE</button></a>
                       </div>

                       <div class="form-group col-lg-1 col-md-3 col-sm-6 col-xs-12  col-md-offset-4 " style="display: inline;">
                         <a href="#modalKardex" data-toggle="modal" class="btn btn-success " onclick="">REINICIAR</a>
                       </div>
                       <span id="loading"></span>
                     <!-- </div> -->



                        <table id="tbllistado" class="table  table-bordered table-condensed table-hover">
                          <thead>
                            <th>Codigo</th>
                            <th>Producto</th>
                            <th>Categoria</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Saldo</th>

                          </thead>
                          <tbody>

                          </tbody>
                          <tfoot>
                            <th>Codigo</th>
                            <th>Producto</th>
                            <th>Categoria</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Saldo</th>

                          </tfoot>
                        </table>
                     </div>


           <!-- Modal -->
            <div class="modal fade modalKardex" tabindex="-1" id="modalKardex" role="dialog">
              <div class="modal-dialog modal-dialog-centered modal-sm" >
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title" id="exampleModalLabel">AVISO IMPORTANTE!</h5>
                  </div>
                  <div class="modal-body ">
                      
                      <div class="col-sm-12 col-xs-12">
                        <p>
                          ¿Está seguro de reiniciar el kardex?
                        </p>
                      </div>

                  </div>
                  <div class="modal-footer">
                 <!--  ¿Ya tienes una cuenta registrada? | <strong><a href="#modalIngreso" data-dismiss="modal" data-toggle="modal">Ingresar</a></strong> -->
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-primary" onclick="reiniciar();" data-dismiss="modal">Aceptar</button>
                  </div>
                </div>
              </div>
            </div>
<!-- Fin modal -->
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

    <script type="text/javascript" src="scripts/kardex.js">
    </script>

<?php
}
ob_end_flush();

 ?>
