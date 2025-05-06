<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"]))
{
  header("Location: index.php");
}
else
{
require 'header.php';

if ($_SESSION['ventas']==1)
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
                    <div class="box-header with-border">
                          <h1 class="box-title">Proforma&nbsp;&nbsp; <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Agregar</button></h1>
                          <a target="_blank" href="cliente.php"> <button class="btn btn-info" id="btncliente"><i class="fa fa-users"></i>&nbsp;&nbsp;Clientes</button></a>
                  <a target="_blank" href="../reportes/proformapdf.php"> <button class="btn btn-warning btn-art" id="btnPDF"><i class="fa fa-file-pdf-o"></i> &nbsp;PDF</button> </a>
                          
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
                            <th>Total Venta</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Usuario</th>
                            <th>Documento</th>
                            <th>Número</th>
                            <th>Total Venta</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-7 col-md-7 col-sm-7 col-xs-12">
                            <label>Cliente(*):</label>
                            <input type="hidden" name="idventa" id="idventa">
                            <select id="idcliente" name="idcliente" class="form-control selectpicker" data-live-search="true"  data-style="btn-default"></select>

                            </select>
                          </div>
                         
                         
                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Moneda:</label>
                            <!-- <input type="text" class="form-control " name="moneda" id="moneda" placeholder="S/." > -->
                            <select name="moneda" id="moneda" class="form-control selectpicker" required ></select>
                          </div>

                          <!-- <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12"> -->
                            <!-- <label>Impuesto:</label> -->
                            <!-- <select name="impuesto" id="impuesto" style="display:none !important;" disabled></select> -->
                          <!-- </div> -->

                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Fecha(*):</label>
                            <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                          </div>
                          
                            <input type="hidden" class="form-control" name="codigotipo_comprobante"  value="10">
                         
                            <input type="hidden" class="form-control" name="serie" id="serie" maxlength="54" placeholder="Serie">
                        
                            <input type="hidden" class="form-control" name="correlativo" id="correlativo" >
                          
                            <!-- <input type="text" class="form-control selectpicker" name="impuesto" id="impuesto"> -->

                            <div class="form-group col-lg-2 col-md-7 col-sm-7 col-xs-8">
                            <label>Tiempo de Entrega</label>                           
                            <input type="text" class="form-control" name="tiempoEntrega" id="tiempoEntrega" maxlength="50">
                          </div> 
                          <div class="form-group col-lg-2 col-md-7 col-sm-7 col-xs-8">
                           <label>Validez de la Oferta</label>                           
                            <input type="text" class="form-control" name="validez" id="validez" maxlength="50">
                           </div> 
                           
                           <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Impuesto:</label>
                            <select name="impuesto" id="impuesto" class="form-control selectpicker" disabled required></select>
                            <input type="hidden" name="igv_asig" id="igv_asig">


                            <!-- <input type="text" class="form-control" name="impuesto" id="impuesto" required="" > -->
                          </div>                           
                           
                          <div class="form-group col-lg-12 col-md-3 col-sm-6 col-xs-12">
                            <a data-toggle="modal" href="#myModal">
                              <button id="btnAgregarArt" type="button" class="btn btn-primary"> <span class="fa fa-plus"></span>&nbsp;&nbsp; Agregar Artículos</button>
                            </a>
                          </div>

                          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                              <thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Serie</th>
                                    <th>U. Medida</th>
                                    <th>Cantidad</th>
                                    <th>Val. Vta. U.</th>
                                    <th>Impuestos</th>
                                    <th>Precio Venta</th>
                                    <th>Val. Vta. Total </th>
                                    <th>Importe</th>
                                </thead>
                                <!-- <tfoot>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><h4 id="total">S/. 0.00</h4><input type="hidden" name="total_venta" id="total_venta"></th>
                                </tfoot> -->
                                <tfoot>
                                  <tr>
                                    <th colspan="7"></th>
                                    <th colspan="2">SUB TOTAL DE VENTA</th>
                                    <th><h4 id="totalg">0.00</h4><input type="hidden" name="total_venta_gravado" id="total_venta_gravado"></th>
                                  </tr>
                                   <!-- <tr>
                                    <th colspan="7"></th>
                                    <th colspan="2">TOTAL VENTA EXONERADO</th>
                                    <th><h4 id="totale">0.00</h4><input type="hidden" name="total_venta_exonerado" id="total_venta_exonerado"></th>
                                   </tr>
                                   <tr>
                                    <th colspan="7"></th>
                                    <th colspan="2">TOTAL VENTA INAFECTAS</th>
                                    <th><h4 id="totali">0.00</h4><input type="hidden" name="total_venta_inafectas" id="total_venta_inafectas"></th>
                                   </tr>
                                   <tr>
                                    <th colspan="7"></th>
                                    <th colspan="2">TOTAL VENTA GRATUITAS</th>
                                    <th><h4 id="totalgt">0.00</h4><input type="hidden" name="total_venta_gratuitas" id="total_venta_gratuitas"></th>
                                   </tr>
                                   <tr>
                                    <th colspan="7"></th>
                                    <th colspan="2">TOTAL DESCUENTO</th>
                                    <th><h4 id="totald">0.00</h4><input type="hidden" name="total_descuentos" id="total_descuentos"></th>
                                   </tr>
                                   <tr>
                                    <th colspan="7"></th>
                                    <th colspan="2">ISC</th>
                                    <th><h4 id="totalisc">0.00</h4><input type="hidden" name="isc" id="isc"></th>
                                   </tr>
                                   <tr> -->
                                    <th style="height:2px;"  colspan="7"></th>
                                    <th colspan="2">IGV</th>
                                    <th><h4 id="totaligv">0.00</h4><input type="hidden" name="igv_total" id="igv_total"></th>
                                   </tr>
                                   <tr>
                                    <th style="height:2px;" colspan="7"></th>
                                    <th style="height:2px;" colspan="2">TOTAL DE VENTA </th>
                                    <th style="height:2px;"><h4 id="totalimp">0.00</h4><input type="hidden" name="total_venta" id="total_venta"></th>
                                   </tr>
                                </tfoot>
                                    
                                <tbody>

                                </tbody>
                            </table>
                          </div>

                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> &nbsp;&nbsp;Guardar</button>

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
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog" style="width: 65% !important;">
      <div class="modal-content">
        <div class="modal-header backColor">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Seleccione un Artículo</h4>
        </div>
        <div class="modal-body table-responsive">
          <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>U. Medida</th>
                <th>Categoría</th>
                <th>Código</th>
                <th>Stock</th>
                <th>Precio Venta</th>
                <th>Imagen</th>
                <th>Afectacion</th>
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
                <th>Afectacion</th>
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
}
else
{
  require 'noacceso.php';
}

require 'footer.php';
?>
<script src="...sweetalert.min.js"></script>
<script type="text/javascript" src="scripts/proforma.js"></script>
<?php
}
ob_end_flush();
?>
  
