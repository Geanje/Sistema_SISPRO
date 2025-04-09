<?php
//Activar el almacenamiento en el buffer
ob_start();
session_start();

if(!isset($_SESSION["nombre"]))
{
  header("Location:index.php");
}
else {
require 'header.php';

if($_SESSION['compras']==1)
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
                           <h1 class="box-title" id="tituloModulo">Ingreso de Productos &nbsp; &nbsp; <button class="btn btn-success" id="btnagregar"
                             onclick="mostrarform(true)">
                             <i class="fa fa-plus-circle"></i>&nbsp; Agregar</button> 
                            </h1> 
                            <button class="btn btn-warning btn-art" id="btnreporte"><i class="fa fa-file-pdf-o"></i> &nbsp;<a target="_blank" href="../reportes/rptingresos.php" style="color: white">Reporte</button></a>

                         <div class="box-tools pull-right" id="btnRegresar">
                          <button class="btn btn-primary" onclick="location.reload()">
                             <i class="fa fa-cog"></i>&nbsp; Actualizar</button>
                         </div>
                     </div>
                     <!-- /.box-header -->
                     <!-- centro -->
                     <div class="panel-body table-responsive"  id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Usuario</th>
                            <th>Documento</th>
                            <th>Numero</th>
                            <th>Nro Cuotas</th>
                            <th>Valor cuota</th>
                            <th>Total compra</th>
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
                            <th>Numero</th>
                            <th>Nro Cuotas</th>
                            <th>Valor cuota</th>
                            <th>Total compra</th>
                            <th>Estado</th>

                          </tfoot>
                        </table>
                     </div>
                     <div class="panel-body" style="" id="formularioregistros">

                      <!--Formulario-->
                      <form name="formulario" method="POST" id="formulario">
                        <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                          <label>Proveedor:</label>
                          <input type="hidden" name="idingreso" id="idingreso">
                          <!-- Eliminar el required -->
                          <select id ="idproveedor" name="idproveedor" class="form-control selectpicker" data-live-search="true">

                          </select>

                        </div>

                        <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                          <label>Fecha:</label>
                          <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required>

                        </div>



                      <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label>Tipo comprobante(*):</label>
                        <select class="form-control selectpicker" name="tipo_comprobante" id="tipo_comprobante" required="">
                          <option value="Boleta">Boleta</option>
                          <option value="Factura">Factura</option>
                          <!-- <option value="Ticket">Ticket</option> -->
                        </select>
                      </div>


                      <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label>Serie:</label>
                        <input type="text" class="form-control" name="serie_comprobante" id="serie_comprobante" maxlength="7" placeholder="Serie" required>
                      </div>

                      <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label>Numero:</label>
                        <input type="text" class="form-control" name="num_comprobante" id="num_comprobante" maxlength="10" placeholder="Numero" required>
                      </div>

                      <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label>¿Es a crédito?</label>
                        <select name="credito" id="credito" class="form-control">
                          <option value="no">No</option>
                          <option value="si">Si</option>
                        </select>
                        <input type="hidden" class="form-control" name="impuesto" id="impuesto" required="">
                      </div>

                      <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <a data-toggle="modal" href="#myModal">
                          <button id="btnAgregarArt" type="button" class="btn btn-primary">
                            <span class="fa fa-plus"></span>&nbsp;&nbsp;Agregar articulos
                          </button>

                        </a>
                      </div>

                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
                        <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                          <thead style="background-color:#A9D0F5">
                            <th>Opciones</th>
                            <th>Codigo</th>
                            <th>Articulo</th>
                            <th>Serie</th>
                            <th>Cantidad</th>
                            <th>Precio compra</th>
                            <th>%</th>
                            <th>Precio venta</th>
                            <th>Subtotal</th>

                          </thead>
                          <tfoot>
                            <th>TOTAL</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th><h4 id="total">S/&nbsp;&nbsp; 0.00</h4><input type="hidden" name="total_compra" id="total_compra"></th>
                          </tfoot>
                          <tbody>

                          </tbody>

                        </table>
                      </div>

                          <!-- <div class="row"> -->
                            <div class="col-xs-12" id="escredito">
                              <div class="box box-success box-solid">
                                <div class="box-header with-border">
                                  <h3 class="box-title">Cuotas</h3>

                                  <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                    </button>
                                  </div>
                                  <!-- /.box-tools -->
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body" >
                                  <div class="form-group">
                                    <div class="col-md-3 col-xs-12">
                                      <label>Cuotas</label>
                                      <input type="number" name="cuota" class="form-control" id="cuota" min="1">
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                      <label>Valor cuotas</label>
                                      <input type="number" name="valorcuota" class="form-control" step="0.01" id="valorcuota" readonly="readonly">
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                      <label>Tipo de crédito</label>
                                      <select name="tipocredito" class="form-control" id="tipocredito">
                                        <option value="Diario">Diario</option>
                                        <option value="Mensual">Mensual</option>
                                        <option value="Seleccionar">Seleccionar fechas</option>
                                      </select>
                                    </div>
                                    <div class="col-md-3 col-xs-12" id="fechainicio">
                                      <label>Fecha de inicio</label>
                                      <input type="date" name="fechainicio" class="form-control" value="<?php echo date("Y-m-d") ?>">
                                    </div>
                                    <div class="col-md-3 col-xs-12" id="diapago">
                                      <label>Día de pago</label>
                                      <select name="diapago" class="form-control" >
                                        <?php 
                                        $x=1;
                                          while($x<=31){
                                            if($x<10){
                                              echo '<option value="0'.$x.'">'.$x.'</option>';

                                            }else{
                                              echo '<option value="'.$x.'">'.$x.'</option>';
                                              
                                            }
                                            $x++;
                                          }
                                         ?>
                                      </select>
                                    </div>
                                    <div class="col-md-3 col-xs-12" id="seleccionarFecha">
                                      <button type="button" class="form-control btn btn-success" id="btnGenerarFechas">Generar</button>
                                    </div>
                                  </div>
                                <!-- /.box-body -->
                                </div>
                              <!-- /.box -->
                              </div>
                              <div id="listadoFechas" class="panel-body table-responsive">
                                 <table id="tblListadoFechas" class="table table-striped table-bordered table-condensed table-hover">
                                   <thead>
                                      <th>Nro Cuota</th>
                                      <th>Valor de cuota</th>
                                      <th>Fecha de pago</th>
                                   </thead>
                                   <tbody>
                                     
                                   </tbody>
                                  
                                 </table>
                               </div>
                            </div>

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                             <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>&nbsp;&nbsp; Guardar</button>
                            <button id="btnCancelar" class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Cancelar</button>

                        </div>
                      </form>

                     </div>

                     <!---------------------------------------------------------------------------------->
                     <!--------------------LISTADO DE CUOTAS-------------------------->
                     <!---------------------------------------------------------------------------------->
                      
                     <div id="listadoCuotas" class="panel-body table-responsive">
                        <div id="modalNuevaCuota">
                          <!-- <a data-toggle="modal" href="#modalListadoCuotas">
                            <button id="btnAgregarArt" type="button" class="btn btn-primary">
                              <span class="fa fa-plus"></span>&nbsp;&nbsp;Agregar nueva cuota
                            </button>
                          </a> -->
                        </div>
                        <br>

                       <table id="tblCuotas" class="table table-striped table-bordered table-condensed table-hover">
                         <thead>
                            <th>Opciones</th>
                            <th>Nro Cuota</th>
                            <th>Nro Comprobante</th>
                            <th>Valor de cuota</th>
                            <th>Fecha de pago</th>
                            <th>Estado</th>
                         </thead>
                         <tbody>
                           
                         </tbody>
                         <tfoot>
                           <th>Opciones</th>
                            <th>Nro Cuota</th>
                            <th>Nro Comprobante</th>
                            <th>Valor de cuota</th>
                            <th>Fecha de pago</th>
                            <th>Estado</th>
                         </tfoot>
                       </table>
                       <br>
                       <!-- <div class="col-md-1 col-sm- col-xs-12"></div> -->
                       <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box">
                          <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>

                          <div class="info-box-content">
                            <span class="info-box-text">Valor a pagar</span>
                            <span class="info-box-number" id="valorapagar"></span>
                          </div>
                          <div class="info-box-content">
                            <span class="info-box-text">Total de saldo cancelado</span>
                            <span class="info-box-number" id="cuotaspagadas"></span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                      </div>

                       <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box">
                          <span class="info-box-icon bg-yellow"><i class="fa fa fa-thumbs-o-up"></i></span>

                          <div class="info-box-content">
                            <span class="info-box-text">Saldo</span>
                            <span class="info-box-number" id="saldoDiferencial"></span>
                          </div>
                         
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>

                     
                     </div>

                     

                     <!--Fin centro -->
                   </div><!-- /.box -->
               </div><!-- /.col -->
           </div><!-- /.row -->
       </section><!-- /.content -->

     </div><!-- /.content-wrapper -->
   <!--Fin-Contenido-->

   <!--Modal-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labellebdy="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Seleccione un articulo</h4>
            </div>
            <div class="modal-body table-responsive">
              <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th>Opciones</th>
                  <th>Nombre</th>
                  <th>Categoria</th>
                  <th>Codigo</th>
                  <th>Stock</th>
                  <th>Afectación</th>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                  <th>Opciones</th>
                  <th>Nombre</th>
                  <th>Categoria</th>
                  <th>Codigo</th>
                  <th>Stock</th>
                  <th>Afectación</th>
                </tfoot>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>

      </div>
    </div>

   <!--Fin-Modal-->

   <!--Modal-->

       <!---------------------------------------------------------------------------------->
       <!----------------------------MODAL DE LISTADO DE CUOTAS---------------------------->
       <!---------------------------------------------------------------------------------->
        <div class="modal fade" id="modalListadoCuotas">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title">Gestionar cuota</h4>
              </div>
              <div class="modal-body">
                <div class="row">
                    <form class="form-horizontal" id="formularioListadoCuotas" name="formularioListadoCuotas">
                      <div class="box-body">
                        <div class="form-group">
                          <label class="control-label col-lg-3 col-md-3 col-sm-3 col-xs-12">Valor cuota:</label>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <input type="hidden" name="idpago" id="idpago" class="form-control">
                            <input type="hidden" name="id_ingreso" id="id_ingreso" class="form-control">
                            <input type="number" name="valor_cuota" step="0.01" id="valor_cuota" class="form-control" required="required">
                          </div>
                        </div>  
                        <div class="form-group">
                          <label class="control-label col-lg-3 col-md-3 col-sm-3 col-xs-12">Fecha</label>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <input type="date" name="fecha_cuota" id="fecha_cuota" class="form-control" required="required">
                          </div>
                        </div>  
                        <div class="form-group">
                          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                          </div>
                        </div>
                      
                      </div>
                    </form>
                  </div>
              </div>
            </div>
          </div>
        </div>

   <!--Fin-Modal-->


   <?php
   }
   else {
     require 'noacceso.php';
  }



require 'footer.php';

    ?>

    <script type="text/javascript" src="scripts/ingreso.js">
    </script>

<?php
}
ob_end_flush();

 ?>
