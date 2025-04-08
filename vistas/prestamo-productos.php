<?php
//Activar el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location:index.php");
} else {
  require 'header.php';

  if ($_SESSION['consultav'] == 1) {



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
                <h1 class="box-title">CONSULTA DE PRODUCTOS PRESTADOS&nbsp;&nbsp;&nbsp;
                  <a target="_blank" href="../reportes/rtp_presta_Producto.php"> <button class="btn btn-warning btn-art"><i class="fa fa-file-pdf-o"></i> &nbsp;Reporte</button> </a>
                </h1>
                <div class="box-tools pull-right">
                </div>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                  <label>Fecha inicio</label>
                  <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">

                </div>

                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                  <label>Fecha fin</label>
                  <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">

                </div>

                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Sucursal</label>
                  <select class="form-control selectpicker" name="idsucursal" id="idsucursal" data-live-search="true" required>

                  </select>

                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>

                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                  <button class="btn btn-success form-control" style="margin-bottom:-10px" onclick="listar()">Mostrar</button>
                </div>



                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Sucursal</th>
                    <th>N° Comprobante</th>
                    <th>Artículo</th>
                    <th>Cantidad</th>
                    <th>Total venta</th>
                    <th>Estado</th>


                  </thead>
                  <tbody>

                  </tbody>
                  <tfoot>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Sucursal</th>
                    <th>N° Comprobante</th>
                    <th>Artículo</th>
                    <th>Cantidad</th>
                    <th>Total venta</th>
                    <th>Estado</th>

                  </tfoot>
                </table>
                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12" align="center">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" align="center">
                  <div style="text-align:center; font-size:large;font-weight: 500;" class="alert alert-warning">
                    <h4><span id="sucursa">SUCURSAL</span></h4>
                    <label>Cantidad Total de productos prestados</label>

                    <p>
                      <span id="cantsucursal">0</span>
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

  <script type="text/javascript" src="scripts/prestamo-productos.js">
  </script>

<?php
}
ob_end_flush();

?>