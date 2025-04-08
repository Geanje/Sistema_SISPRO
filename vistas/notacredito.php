<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: index.php");
} else {
  require 'header.php';

  if ($_SESSION['ventas'] == 1) {
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
                <h1 class="box-title">NOTA DE CREDITO <button class="btn btn-success" id="btnaescoger" data-toggle="modal" data-target="#eligeModal" onclick=""><i class="fa fa-plus-circle"></i> Escoger tipo de nota de credito</button> <button class="btn btn-success" id="btnagregar" data-toggle="modal" data-target="#tblComprobantesModal" onclick=""><i class="fa fa-plus-circle"></i> Agregar nueva nota de credito</button></h1>
                <div class="box-tools pull-right">
                </div>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Opciones</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Usuario</th>
                    <th>Documento</th>
                    <th>Número</th>
                    <th>Motivo</th>
                    <th>ID. Doc. Relacionado</th>
                    <th>Estado</th>
                    <th>Respuesta SUNAT</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Usuario</th>
                    <th>Documento</th>
                    <th>Número</th>
                    <th>Motivo</th>
                    <th>ID. Doc. Relacionado</th>
                    <th>Estado</th>
                    <th>Respuesta SUNAT</th>
                  </tfoot>
                </table>
              </div>

              <div class="panel-body" id="formularioregistros">
                <!--  <div class="container" style="text-align: right;">
                          <h4 id="tipoNC"></h4>
                        </div> -->
                <!-- <form id="detalleConcepto" method="POST" action="agregaventa.php"> -->
                <form id="detalleConcepto" method="POST">
                  <div id="listaCompro1"></div>

                  <table id="listaCompro2" class="table table-striped table-bordered table-condensed table-hover"></table>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-primary" type="submit" id="btnGuardar" onclick="anular()"><i class="fa fa-save"></i> Guardar</button>
                    <button id="btnCancelar" class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                  </div>
                </form>

              </div>
              <!--Fin centro -->
            </div><!-- /.box -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
    <!--Fin-Contenido-->

    <!-- Modal -->
    <div class="modal fade" id="docRelacionadoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xs modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header backColor">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Documento relacionado</h4>
          </div>
          <div class="modal-body">
            <table id="" class="table table-striped table-bordered table-condensed table-hover">
              <tr>
                <td>Fecha :</td>
                <td id="fechar"></td>
              </tr>
              <tr>
                <td>Cliente :</td>
                <td id="clienter"></td>
              </tr>
              <tr>
                <td>Usuario :</td>
                <td id="usuarior"></td>
              </tr>
              <tr>
                <td>Documento :</td>
                <td id="documentor"></td>
              </tr>
              <tr>
                <td>Tipo de nota de credito :</td>
                <td id="tiponcr"></td>
              </tr>
              <tr>
                <td>Sustento :</td>
                <td id="sustentor"></td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

          </div>

        </div>
      </div>
    </div>
    <!-- Fin modal -->


    <!-- Modal -->
    <div class="modal fade" id="eligeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header headerColor">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Elige el tipo de nota de credito a realizar</h4>
          </div>
          <div class="modal-body">
            <select id="tipoNotaC" class="form-control selectpicker" name="tipoNota" data-live-search="true"></select>
          </div>
          <div class="modal-footer">
            <center><button type="button" class="btn btn-default" onclick="isession();" data-dismiss="modal">Aceptar</button></center>

          </div>

        </div>
      </div>
    </div>
    <!-- Fin modal -->

    <!-- Modal -->
    <div class="modal fade" id="tblComprobantesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content" style="height: 100%; width: 125%;">
          <div class="modal-header headerColor">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Seleccione documento </h4>
          </div>
          <div class="modal-body">

            <table id="tblcomprobantes" class="table table-striped table-bordered table-condensed table-hover">
              <thead>
                <th>Opciones</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Usuario</th>
                <th>Documento</th>
                <th>Número</th>
                <th>Estado</th>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                <th>Opciones</th>
                <th>Fecha</th>
                <th>Proveedor</th>
                <th>Usuario</th>
                <th>Documento</th>
                <th>Número</th>
                <th>Estado</th>
              </tfoot>
            </table>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <?php
            echo isset($_SESSION['isession']) ?

              "" : "<p>Debe escoger el tipo de nota de crédito antes de este paso!</p>";
            ?>
          </div>

        </div>
      </div>
    </div>
    <!-- Fin modal -->


    <!-- Modal -->
    <div class="modal fade" id="tiponota3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Error en la descripcion</h4>
          </div>
          <div class="modal-body">
            <!-- <div id="error3"></div> -->
            <input type="hidden" name="cont" id="cont">
            <label>DICE:</label>
            <input type="text" name="dice" id="dice" class="form-control">
            <label>DEBE DECIR:</label>
            <input type="text" name="debedecir" id="debedecir" class="form-control">
          </div>
          <div class="modal-footer">
            <center><button type="button" class="btn btn-default" onclick="limpiarAgregarTipoNota3()" data-dismiss="modal">Aceptar</button></center>

          </div>

        </div>
      </div>
    </div>
    <!-- Fin modal -->

  <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/notaCredito.js"></script>
  <!-- <script type="text/javascript" src="scripts/Venta2.js"></script> -->
<?php
}
ob_end_flush();
?>