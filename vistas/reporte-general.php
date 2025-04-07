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
              <div class="box-header with-border" style="background-color:white">
                <h1 class="box-title">HISTORIAL
                  <small style="color: #000000; font-size:16px"> Productos vendidos</small> &nbsp;&nbsp;
                  <a target="_blank" href="../reportes/report_general.php"> <button class="btn btn-warning btn-art"><i class="fa fa-file-pdf-o"></i> &nbsp; Reporte</button> </a>
                </h1>
                <div class="box-tools pull-right">
                </div>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
                  <label>Fecha inicio</label>
                  <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>" required>

                </div>

                <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
                  <label>Fecha fin</label>
                  <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>" required>

                </div>

                <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                  <label>Artículo</label>
                  <input class="form-control" name="producto" id="producto" required>
                </div>
                <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                  <label>Serie</label>
                  <input class="form-control" name="serie" id="serie" required placeholder="Por ejemplo #ABCD">
                </div>
                <div class="form-group col-lg-4 col-md-2 col-sm-6 col-xs-12">
                  <button class="btn btn-warning btn-block" onclick="listar()"> Mostrar
                    <i class="fa fa-search"></i>
                  </button>

                </div>


                <!-- <div class="panel-body table-responsive" id="listadoregistros"> -->
                <!-- <div class="table-responsive"> -->
                <table id="tbllistado" class="table table-striped  table-condensed table-hover">
                  <thead>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Comprobante</th>
                    <th>Articulo</th>
                    <th>Serie</th>
                    <th>Cantidad</th>
                    <th>Precio_venta</th>
                  </thead>
                  <tbody>

                  </tbody>

                </table>
                <!-- </div> -->

                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12" align="center">
                </div>
                <!-- <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" align="center" >
                         <div style="text-align:center; font-size:large;font-weight: 500;" class="alert alert-danger" >
                            <label>Venta Total del cliente </label>
                            <span id="client">X</span>
                            <p>
                              <span id="sumventacliente">0.00</span><span> Soles</span>
                            </p>
                          
                         </div>
                     </div> -->

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

  <script type="text/javascript" src="scripts/reporte-general.js">
  </script>

<?php
}
ob_end_flush();

?>