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

    <div class="content-wrapper">

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border" style="background-color:white">
                <h1 class="box-title">HISTORIAL
                  <small style="color: #000000; font-size:16px"> Productos Comprados</small> &nbsp;&nbsp;&nbsp;
                  <a target="_blank" href="../reportes/rpt_general_c.php"> <button class="btn btn-warning btn-art"><i class="fa fa-file-pdf-o"></i> &nbsp;Reporte</button> </a>
                </h1>
                <div class="box-tools pull-right">
                </div>
              </div>

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

                <table id="tbllistado" class="table table-striped  table-condensed table-hover">
                  <thead>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Usuario</th>
                    <th>Comprobante</th>
                    <th>Articulo</th>
                    <th>Cantidad</th>
                    <th>Serie</th>
                    <th>Precio_venta</th>
                  </thead>
                  <tbody>

                  </tbody>

                </table>


                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12" align="center">
                </div>

              </div>
            </div>
          </div>
      </section>
    </div>
  <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';

  ?>
  <script type="text/javascript" src="scripts/reporte-general-c.js">
  </script>

<?php
}
ob_end_flush();

?>