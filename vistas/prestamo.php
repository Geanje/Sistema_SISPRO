<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: index.php");
} else {
  require 'header.php';

  if ($_SESSION['sucursal'] == 1) {
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
                <h1 class="box-title">Préstamo de productos &nbsp;&nbsp;<button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)">
                    <i class="fa fa-plus-circle"></i>&nbsp;&nbsp;Agregar</button>
                  <button class="btn btn-primary" onclick="location.reload()" id="btnActulizar">
                    <i class="fa fa-cog"></i>&nbsp;&nbsp;Actualizar</button>
                  <a target="_blank" href="../reportes/prestamopdf.php" target="__blank"> <button class="btn btn-warning btn-art" id="btnPDF"><i class="fa fa-file-pdf-o"></i> &nbsp;PDF</button> </a>
                </h1>
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
                    <th>Sucursal</th>
                    <th>Usuario</th>
                    <th>Número</th>
                    <th>Precio</th>
                    <th>Estado</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>Fecha</th>
                    <th>Sucursal</th>
                    <th>Usuario</th>
                    <th>Número</th>
                    <th>Precio</th>
                    <th>Estado</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" style="" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">
                  <div class="form-group col-lg-9 col-md-9 col-sm-9 col-xs-12">
                    <label>Sucursal(*):</label>
                    <input type="hidden" name="idventa" id="idventa">
                    <select id="idsucursal" name="idsucursal" class="form-control selectpicker" data-live-search="true" required data-style="btn-default" title="Seleccione Sucursal">

                    </select>
                  </div>

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Fecha(*):</label>
                    <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                  </div>

                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>N° de documento:</label>
                    <input type="text" class="form-control" name="numdoc" id="numdoc" placeholder="Numero de documento">
                  </div>
                  <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                    <label>Dirección:</label>
                    <input type="text" class="form-control" name="direccionsucursal" id="direccionsucursal" placeholder="Dirección">
                  </div>
                  <!-- <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12"></div> -->
                  <br>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right">
                    <button id="btnAgregarArt" data-toggle="modal" data-target="#myModal" type="button" class="btn btn-primary form-control "> <span class="fa fa-plus"></span>&nbsp;&nbsp;Prestar Artículos</button>

                  </div>
                  <!--     <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Serie:</label>
                            <input type="text" class="form-control" name="serie" id="serie" maxlength="4" placeholder="Serie">
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Correlativo:</label>
                            <input type="text" class="form-control" name="correlativo" id="correlativo" maxlength="8" placeholder="Número" >
                          </div> -->




                  <div class="table-responsive col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                      <thead style="background-color:#A9D0F5">
                        <th>Opciones</th>
                        <th>Artículo</th>
                        <th>Serie</th>
                        <th>U. Medida</th>
                        <th>Cantidad</th>
                        <th>Precio Venta</th>
                        <th>Importe</th>
                      </thead>

                      <tfoot>
                        <tr>
                          <th colspan="4"></th>
                          <th colspan="2">TOTAL VENTA GRAVADO</th>
                          <th>
                            <h4 id="totalg">0.00</h4><input type="hidden" name="total_venta_gravado" id="total_venta_gravado">
                          </th>
                          <!-- </tr> -->
                          <!-- <tr> -->
                          <!-- <th colspan="4"></th> -->
                          <!-- <th colspan="2">TOTAL VENTA EXONERADO</th> -->
                          <!-- <th> -->
                          <h4 id="totale"></h4><input type="hidden" name="total_venta_exonerado" id="total_venta_exonerado">
                          <!-- </th> -->
                          <!-- </tr> -->
                          <!-- <tr> -->
                          <!-- <th colspan="4"></th> -->
                          <!-- <th colspan="2">TOTAL VENTA INAFECTAS</th> -->
                          <!-- <th> -->
                          <h4 id="totali"></h4><input type="hidden" name="total_venta_inafectas" id="total_venta_inafectas">
                          <!-- </th> -->
                          <!-- </tr> -->
                          <!-- <tr> -->
                          <!-- <th colspan="4"></th> -->
                          <!-- <th colspan="2">TOTAL VENTA GRATUITAS</th> -->
                          <!-- <th> -->
                          <h4 id="totalgt"></h4><input type="hidden" name="total_venta_gratuitas" id="total_venta_gratuitas">
                          <!-- </th> -->
                        </tr>
                        <tr>
                          <th colspan="4"></th>
                          <th colspan="2">TOTAL DESCUENTO</th>
                          <th>
                            <h4 id="totald">0.00</h4><input type="hidden" name="total_descuentos" id="total_descuentos">
                          </th>
                          <!-- <th> -->
                          <!-- </th> -->
                          <!-- </tr> -->
                          <!-- <tr> -->
                          <!-- <th colspan="8"></th> -->
                          <!-- <th colspan="2">ISC</th> -->
                          <!-- <th> -->
                          <h4 id="totalisc"></h4><input type="hidden" name="isc" id="isc">
                          <!-- </th> -->
                        </tr>
                        <tr>
                          <th style="height:2px;" colspan="4"></th>
                          <th colspan="2">IGV</th>
                          <th>
                            <h4 id="totaligv">0.00</h4><input type="hidden" name="total_igv" id="total_igv">
                          </th>
                        </tr>
                        <tr>
                          <th style="height:2px;" colspan="4"></th>
                          <th style="height:2px;" colspan="2">TOTAL IMPORTE</th>
                          <th style="height:2px;">
                            <h4 id="totalimp">0.00</h4><input type="hidden" name="total_importe" id="total_importe">
                          </th>
                        </tr>
                      </tfoot>

                      <tbody>

                      </tbody>
                    </table>
                  </div>

                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>&nbsp;&nbsp; Guardar</button>

                    <button id="btnCancelar" class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> &nbsp;&nbsp;Cancelar</button>
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
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 65% !important;">
        <div class="modal-content">
          <div class="modal-header backColor">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Seleccione un Artículo</h4>
          </div>
          <div class="modal-body table-responsive">
            <table id="tblarticulos" class="table  table-striped table-bordered table-condensed table-hover">
              <thead>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>U. Medida</th>
                <th>Categoría</th>
                <th>Código</th>
                <th>Stock</th>
                <th>Precio Venta</th>
                <th>Imagen</th>
              </thead>
              <tbody>

              </tbody>
              <tfoot>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>U. Medida</th>
                <th>Categoría</th>
                <th>Código</th>
                <th>Stock</th>
                <th>Precio Venta</th>
                <th>Imagen</th>
              </tfoot>
            </table>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
  <script type="text/javascript" src="scripts/prestamo.js"></script>
<?php
}
ob_end_flush();
?>