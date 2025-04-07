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
    <!-- <link rel="stylesheet" type="text/css" href="css/venta_radio.css"> -->
    <div class="content-wrapper">
      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h1 class="box-title">Facturacion por Servicio sin IGV &nbsp;&nbsp;<button class="btn btn-success" id="btnagregar" onclick="informe1()"><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;Agregar</button></h1>
                <div class="box-tools pull-right">
                </div>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <input type="hidden" name="listado" id="listado" value="2">
                  <thead>
                    <th>Opciones</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Usuario</th>
                    <th>Documento</th>
                    <th>Número</th>
                    <th>Total Venta</th>
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
                    <th>Total Venta</th>
                    <th>Estado</th>
                    <th>Respuesta SUNAT</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">

                  <div class="form-group col-lg-7 col-md-7 col-sm-7 col-xs-12">
                    <label><i class="fa fa-address"></i>Cliente(*):</label>
                    <input type="hidden" name="id_factura" id="id_factura">
                    <select id="idcliente" name="idcliente" class="form-control selectpicker" data-live-search="true" required title="Seleccionar nombre del cliente" data-style="btn-default">
                    </select>
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Fecha Emision:</label>
                    <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                    <script type="text/javascript" src="scripts/calendario.js"></script>
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Moneda:</label>
                    <select name="moneda" id="moneda" class="form-control selectpicker" required></select>
                  </div>

                  <div class="form-group col-lg-1 col-md-2 col-sm-2 col-xs-12">
                    <label>Impuesto:</label>
                    <select name="impuesto" id="impuesto" class="form-control selectpicker" disabled required></select>
                    <input type="hidden" name="igv_asig" id="igv_asig">
                    <input type="hidden" name="ivg" id="ivg" value="2">
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Nº de documento:</label>
                    <input type="text" class="form-control" name="num_documento" id="num_documento" placeholder="Numero de documento" readonly>
                  </div>

                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Dirección:</label>
                    <input type="text" class="form-control" name="direccioncliente" id="direccioncliente" placeholder="Dirección" readonly>
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Tipo Pago(*):</label>
                    <select name="codigotipo_pago" id="codigotipo_pago" class="form-control selectpicker" required="" data-live-search="true"></select>
                  </div>


                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Tipo Comprobante(*):</label>
                    <select name="codigotipo_comprobante" id="codigotipo_comprobante" class="form-control selectpicker" required="" data-live-search="true"></select>
                  </div>


                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Fecha Vencimiento(*):</label>
                    <input type="date" class="form-control" name="fecha_ven" id="fecha_ven" required="">
                  </div>


                  <div class="form-group col-lg-7 col-md-7 col-sm-7 col-xs-12">
                    <a data-toggle="modal" href="#myModal">
                      <button id="btnAgregarArt" type="button" class="btn btn-primary" onclick="agregarDetalle()"> <span class="fa fa-plus"></span>&nbsp;&nbsp;Agregar Servicio</button>
                    </a>
                  </div>



                  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive ">
                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                      <thead style="background-color:#A9D0F5">
                        <th>Opciones</th>
                        <th style="width:90px;">Codigo</th>
                        <th>Servicio</th>
                        <th>U. Medida</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Sub total</th>
                        <th>IGV</th>
                        <th>Importe</th>
                      </thead>

                      <tfoot>
                        <tr>
                          <th colspan="6"></th>
                          <th colspan="2">SUBTOTAL</th>
                          <th>
                            <h4 id="totalg">0.00</h4><input type="hidden" name="op_gravadas" id="op_gravadas">
                          </th>
                        </tr>

                        <tr>
                          <th style="height:2px;" colspan="6"></th>
                          <th colspan="2">IGV</th>
                          <th>
                            <h4 id="totaligv">0.00</h4><input type="hidden" name="igv_total" id="igv_total">
                          </th>
                        </tr>
                        <tr>
                          <th style="height:2px;" colspan="6"></th>
                          <th style="height:2px;" colspan="2">TOTAL</th>
                          <th style="height:2px;">
                            <h4 id="totalventa">0.00</h4><input type="hidden" name="total_venta" id="total_venta">
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

  <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/factura.js"></script>
<?php
}
ob_end_flush();
?>