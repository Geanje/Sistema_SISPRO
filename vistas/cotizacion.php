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
                <h1 class="box-title">Cotización &nbsp;&nbsp;<button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;Agregar</button>
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
                    <th>Tipo de Cotizacion</th>
                    <th>Cliente</th>
                    <th>Proveedor</th>
                    <th>Documento </th>
                    <th>Numero</th>
                    <th>Total Venta</th>
                    <th>Estado</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>Fecha</th>
                    <th>Tipo de Cotizacion</th>
                    <th>Cliente</th>
                    <th>Proveedor</th>
                    <th>Documento </th>
                    <th>Numero</th>
                    <th>Total Venta</th>
                    <th>Estado</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">
                  <div class="form-group col-lg-7 col-md-7 col-sm-7 col-xs-12">
                    <label>Cliente(*):</label>
                    <input type="hidden" name="idcotizacion" id="idcotizacion">
                    <select id="idcliente" name="idcliente" class="form-control selectpicker" data-live-search="true" required data-style="btn-success">

                    </select>
                  </div>


                  <div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-12">
                    <label>Fecha(*):</label>
                    <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" value="<?php echo date('Y-m-d'); ?>" required="">
                  </div>

                  <input type="hidden" class="form-control" name="correlativo" id="correlativo">
                  <input type="hidden" class="form-control" name="serie" id="serie" maxlength="54" placeholder="Serie">


                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Numero de documento:</label>
                    <input type="text" class="form-control" name="numdireccion" id="numdireccion" placeholder="Numero de documento">
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Dirección:</label>
                    <input type="text" class="form-control" name="direccioncliente" id="direccioncliente" placeholder="Dirección">
                  </div>
                  <div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-12">
                    <label>Tipo Cotizacion:</label>

                    <select class="form-control selectpicker" id="tipo_proforma" name="tipo_proforma" required>
                      <option value="Servicios">Servicios</option>
                      <option value="Productos">Productos</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-7 col-md-7 col-sm-7 col-xs-12">
                    <label>Referencia</label>
                    <input type="text" class="form-control" name="referencia" id="referencia" placeholder="Agregue una referencia" maxlength="50">
                  </div>
                  <div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-12">
                    <label>Validez de la Oferta</label>
                    <input type="text" class="form-control" name="validez" id="validez" placeholder="Validez de la oferta" required="" maxlength="50">
                  </div>

                  <div class="form-group col-lg-7 col-md-7 col-sm-7 col-xs-12">
                    <a data-toggle="modal" href="#myModal">
                      <button id="btnAgregarArt" type="button" class="btn btn-primary" onclick="agregarDetalle()"> <span class="fa fa-plus"></span>&nbsp;&nbsp;Agregar</button>
                    </a>
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Impuesto:</label>
                    <select name="impuesto" id="impuesto" class="form-control selectpicker" disabled required></select>
                    <input type="hidden" name="igv_asig" id="igv_asig">
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Tributo:</label>
                    <select class="form-control selectpicker" id="tipo_impuesto" required>
                      <option value="1">Sin IGV</option>
                      <option value="2">Con IGV</option>
                    </select>
                  </div>



                  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                      <thead style="background-color:#A9D0F5">
                        <th>Opciones</th>
                        <th>Descripcion</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Precio Parcial</th>
                      </thead>
                      <tfoot>
                        <tr>
                          <th colspan="3"></th>
                          <th colspan="">SUBTOTAL</th>
                          <th>
                            <h4 id="subtotal">0.00</h4><input type="hidden" name="ssubtotal" id="ssubtotal">
                          </th>
                        <tr>
                          <th style="height:2px;" colspan="3"></th>
                          <th colspan="">IGV</th>
                          <th>
                            <h4 id="totaligv">0.00</h4><input type="hidden" name="igv_total" id="igv_total">
                          </th>
                        </tr>
                        <tr>
                          <th style="height:2px;" colspan="3"></th>
                          <th style="height:2px;" colspan="">TOTAL IMPORTE</th>
                          <th style="height:2px;">
                            <h4 id="totalimp">0.00</h4><input type="hidden" name="total_venta" id="total_venta">
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
    </div>
  <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/cotizacion.js"></script>
<?php
}
ob_end_flush();
?>