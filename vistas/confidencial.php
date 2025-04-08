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

if ($_SESSION['confidencial']==1)
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
                          <h1 class="box-title">CARGA <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Nueva carga</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover ">
                          <thead>
                            <th>Número</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Usuario</th>
                            <th>Gastos Totales</th>
                            <th>Compra Productos</th>
                            <th>Porcentaje</th>
                          </thead>
                          <tbody>
                          </tbody>
                          <tfoot>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Usuario</th>
                            <th>Número</th>
                            <th>Gastos Totales</th>
                            <th>Compra Productos</th>
                            <th>Porcentaje</th>
                          </tfoot>
                        </table>
                    </div>
                   
                    <div class="panel-body table-responsive" style="" id="formularioregistros">
                      <div class="row">
                        <form id="formAgregarCarga" method="POST" class="form-horizontal">
                           <!-- <div class="form-group">
                              <div class="col-sm-5" >
                                <span style="font-size: 17px">Provedor</span>
                                 <select class="form-control">
                                    <option>XXX</option>
                                    <option>XXX</option>
                                    <option>XXX</option>
                                    <option>XXX</option>
                                  </select>
                              </div>
                              <div class="col-sm-7">
                                <span style="font-size: 17px">Fecha</span>
                                <input type="date" name="" class="form-control">

                              </div>
                            </div> -->
                          <div class="col-md-2"></div>
                          <div class="col-md-8">
                            
                            <table id="tablaAgregarCarga" class="table table-striped table-bordered table-condensed table-hover">
                          
                              <tbody style="width: 100%">
                                <tr>
                                  <th class="pull-right">Proveedor
                                    <input type="hidden" name="idconfidencial" id="idconfidencial">
                                    <!-- <input type="hidden" name="idpersona" id="idpersona"> -->
                                  </th>
                                  <td>
                                    <select class="form-control" style="text-align: center;" id="selectproveedor" name="idpersona">
                                    </select>
                                  </td>
                                </tr>
                                <tr>
                                  <th class="pull-right">Fecha</th>
                                  <td><input type="date" name="fecha" id="fecha" class="form-control" style="text-align: center;" required="required"></td>
                                </tr>
                                <tr>
                                  <th align="right" class="pull-right">Ajuste de valores: </th>
                                  <td><input type="number" name="" id="ajusteValores" step="0.01" class="form-control" required="required" style="text-align: center;" value="0" min="0"></td>
                                </tr>
                                <tr>
                                  <th class="pull-right">Botiquin químico: </th>
                                  <td><input type="number" name="" id="botiquinQuimico" step="0.01" class="form-control" required="required" style="text-align: center;" value="0" min="0"></td>
                                </tr> 
                                <tr>
                                  <th class="pull-right">Impuestos SUNAT: </th>
                                  <td><input type="number" name="" step="0.01" id="impuestosSUNAT" class="form-control" required="required" style="text-align: center;" value="0" min="0"></td>
                                </tr> 
                                <tr>
                                  <th class="pull-right">Flete: </th>
                                  <td><input type="number" name="" step="0.01" id="flete" class="form-control" required="required" style="text-align: center;" value="0" min="0"></td>
                                </tr>
                                <tr>
                                  <th class="pull-right">Almacén: </th>
                                  <td><input type="number" name="" step="0.01" id="almacen" class="form-control" required="required" style="text-align: center;" value="0" min="0"></td>
                                </tr>
                                <tr>
                                  <th class="pull-right">Agente de ADUANA: </th>
                                  <td><input type="number" name="" step="0.01" id="agenteADUANA" class="form-control" required="required" style="text-align: center;" value="0" min="0"></td>
                                </tr>
                                <tr>
                                  <th class="pull-right">Cargador: </th>
                                  <td><input type="number" name="" step="0.01" id="cargador" class="form-control" required="required" style="text-align: center;" value="0" min="0"></td>
                                </tr>
                                  <tr>
                                  <th class="pull-right">Pato: </th>
                                  <td><input type="number" name="" step="0.01" id="pato" class="form-control" required="required" style="text-align: center;" value="0" min="0"></td>
                                </tr>
                                  <tr>
                                  <th class="pull-right">Comisionista: </th>
                                  <td><input type="number" name="" step="0.01" id="comisionista" class="form-control" required="required" style="text-align: center;" value="0" min="0"></td>
                                </tr>
                                <tr>
                                  <td colspan="2">
                                    <hr class="lineaCarga">
                                  </td>
                                </tr>
                                </tr>
                                <tr>
                                  <th class="pull-right">Gastos totales: </th>
                                  <td align="center">
                                    <span id="gastos_totalesHTML">0</span><input type="hidden" name="gastos_totales" id="gastos_totales">
                                  </td>
                                </tr>
                                <tr>
                                  <th class="pull-right">Compra de productos: </th>
                                  <td align="center"><input type="number" name="compras" step="0.01" id="compraProductos" class="form-control" style="text-align: center;" min="1" required=""></td>
                                </tr>
                                <tr>
                                 <td colspan="2">
                                    <hr class="lineaCarga">
                                  </td>
                                </tr>
                                <tr>
                                  <th class="pull-right">Total: </th>
                                  <td align="center"><span id="total">0</span></td>
                                </tr>
                                <tr>
                                  <th class="pull-right">Compra de productos: </th>
                                  <td align="center"><span id="compraProductosCopia">0</span></td>
                                </tr>
                                <tr>
                                <tr>
                                 <td colspan="2">
                                    <hr class="lineaCarga">
                                  </td>
                                </tr>
                                <tr>
                                  <th class="pull-right">Porcentaje: </th>
                                  <td align="center">
                                    <span id="porcentaje">0</span><span>%</span>
                                    <!-- <input type="hidden" name="porcentaje" id="gastos_totales"> -->
                                  </td>
                                </tr>
                                
                              </tbody>


                            </table>
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                            <button id="btnCancelar" class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                          </div>
                          </div>
                          <br>
                            
                        </form>
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
}
else
{
  require 'noacceso.php';
}

require 'footer.php';
?>
  <script type="text/javascript" src="scripts/confidencial.js"></script>
<?php
}
ob_end_flush();
?>