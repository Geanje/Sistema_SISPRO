<?php
//Activar el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location:index.php");
} else {
  require 'header.php';

  if ($_SESSION['consultac'] == 1) {



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
                <h1 class="box-title">Compras por fecha &nbsp;&nbsp;&nbsp;
                  <a target="_blank" href="../reportes/rpt_comprafecha.php"> <button class="btn btn-warning btn-art"><i class="fa fa-file-pdf-o"></i> &nbsp;Reporte</button> </a>
                </h1>
                <div class="box-tools pull-right">
                </div>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Fecha inicio</label>
                  <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">

                </div>

                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Fecha fin</label>
                  <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">

                </div>


                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Proveedor</th>
                    <th>Comprobante</th>
                    <th>Numero</th>
                    <th>Total compra</th>
                    <th>Estado</th>


                  </thead>
                  <tbody>

                  </tbody>
                  <tfoot>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Proveedor</th>
                    <th>Comprobante</th>
                    <th>Numero</th>
                    <th>Total compra</th>
                    <th>Estado</th>

                  </tfoot>
                </table>
                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12" align="center">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" align="center">
                  <div style="text-align:center; font-size:large;font-weight: 500;" class="alert alert-danger">
                    <label>Compra Total</label>
                    <p>
                      <span id="sumcompras">0.00</span><span> Soles</span>
                    </p>

                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12" align="center">
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
  } else {
    require 'noacceso.php';
  }



  require 'footer.php';

  ?>

  <script type="text/javascript" src="scripts/comprasfecha.js">
  </script>

<?php
}
ob_end_flush();

?>