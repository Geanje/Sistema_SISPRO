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
                    <div class="box-header with-border ">
                          <h1 class="box-title ">Precio de Artículos &nbsp;
                          <a target="_blank" href="../reportes/rptarticulos.php"> <button class="btn btn-warning btn-art"><i class="fa fa-file-pdf-o"></i>&nbsp; Reporte</button> </a>
                         
                          </h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Código</th>
                            <th>Stock</th>
                            <th>Precio Compra</th>
                            <th>Precio Venta</th>
                            <th>Unidad Medida</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>
                          </tbody>
                          <!-- <tfoot>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Código</th>
                            <th>Stock</th>
                            <th>Precio Compra</th>
                            <th>Precio Venta</th>
                            <th>Unidad Medida</th>
                            <th>Estado</th>
                          </tfoot> -->
                        </table>
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
<script type="text/javascript" src="scripts/arti_precio.js"></script>

<?php
}
ob_end_flush();

 ?>
