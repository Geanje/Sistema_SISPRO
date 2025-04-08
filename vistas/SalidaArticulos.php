<?php
// Activar el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: index.php");
} else {
  require 'header.php';
  if ($_SESSION['servicio'] == 1) {
?>
    <style type="text/css">
      .servicio {
        height: 100px;
      }
    </style>
    <!-- Contenido -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h1 class="box-title" id="idPrincipal" style="font-size: 24px; color: black;">Salida Articulos&nbsp;&nbsp;&nbsp;&nbsp;</h1>
                <a href="servicios-soporte.php"> <button class="btn btn-danger" id="btnSalida">
                    <i class="fa fa-arrow-circle-left"></i>&nbsp;Regresar</button> </a>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th style="width: auto;">Opciones</th>
                    <th style="width: 7%;">Fecha Ingreso</th>
                    <th style="width: 20%">Nombre Cliente</th>
                    <th style="width: auto;">Usuario</th>
                    <th style="width: auto;">Documento</th>
                    <th style="width: auto;">Numero</th>
                    <th style="width: auto;">Area Servicio</th>
                    <th style="width: auto;">Tipo Servicio</th>
                    <th style="width: auto;">Codigo</th>
                    <th style="width: auto;">Estado</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th style="width: auto;">Opciones</th>
                    <th style="width: auto;">Fecha Ingreso</th>
                    <th style="width: 20%">Nombre Cliente</th>
                    <th style="width: auto;">Usuario</th>
                    <th style="width: auto;">Documento</th>
                    <th style="width: auto;">Numero</th>
                    <th style="width: auto;">Area Servicio</th>
                    <th style="width: auto;">Tipo Servicio</th>
                    <th style="width: auto;">Codigo</th>
                    <th style="width: auto;">Estado</th>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <!-- Fin-Contenido -->
  <?php
  } else {
    require 'noacceso.php';
  }
  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/Salida-Articulos.js"></script>
<?php
}
ob_end_flush();
?>