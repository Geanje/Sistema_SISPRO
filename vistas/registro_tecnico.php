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
if($_SESSION['ventas']==1)
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
                           <h1 class="box-title">Tecnico&nbsp;&nbsp;&nbsp;&nbsp;<button id="agregarTecnico" class="btn btn-success"
                             onclick="mostrarform(true)">
                             <i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Agregar</button></h1>
                             <button class="btn btn-default" data-toggle="modal" id="consultaSunat" data-target="#modalConsultarSunat">Consultar a SUNAT/RENIEC <i class="fa fa-question"></i></button>
                         <div class="box-tools pull-right">
                         </div>
                     </div>
                     <!-- /.box-header -->
                     <!-- centro -->
                     <div class="panel-body table-responsive"  id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Telefono</th>
                            <th>DNI</th>
                            <th>Area</th>
                            <th>Cargo</th>
                            <th>Tipo de tecnico</th>
                            <!-- <th>Razón Social</th> -->

                          </thead>
                          <tbody>

                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Telefono</th>
                            <th>DNI</th>
                            <th>Area</th>
                            <th>Cargo</th>
                            <th>Tipo de tecnico</th>
                            <!-- <th>Razón Social</th> -->
                          </tfoot>
                        </table>
                     </div>
                     <div class="panel-body" style="height: 400px;" id="formularioregistros">

                      <!--Formulario-->
                      <form name="formulario" method="POST" id="formulario">
                        
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <label>Nombre: (*)</label>
                          <input type="hidden" name="idtecnico" id="idtecnico">
                          <input type="hidden" name="tipo_tecnico" id="tipo_tecnico" value="Tecnico Soporte">
                          <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required>
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Numero de DNI: (*)</label>
                          <input type="text" class="form-control" name="dni" id="dni" maxlength="8" placeholder="Numero">
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Telefono: (*)</label>
                          <input type="text" class="form-control" name="telefono" id="telefono" maxlength="9" placeholder="Telefono">
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Area: (*)</label>
                          <input type="text" class="form-control" name="area" id="area" placeholder="Area">
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Cargo: (*)</label>
                          <input type="text" class="form-control" name="cargo" id="cargo" placeholder="Cargo">
                        </div>

                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>&nbsp;&nbsp; Guardar</button>
                            <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> &nbsp;&nbsp;Cancelar</button>

                        </div>



                      </form>

                     </div>



                     <div class="modal fade" id="modalConsultarSunat" role="dialog">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header backColor">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4>Consultar a SUNAT</h4>
                          </div>
                          <div class="modal-body">
                            <form method="post" class="form-horizontal">
                              <center>
                                <label><input type="radio" class="radio-inline" name="opRD" value="consultaRUC" onclick="mostrarInput(true)" checked>Busqueda por RUC </label>
                                 <label><input type="radio" class="radio-inline" name="opRD" value="consultaDNI" onclick="mostrarInput(false)"> Busqueda por DNI</label>
                                
                              <div id="cargandoSunat"></div>
                              </center>
                              <div class="form-group">
                                <label class="control-label col-sm-4">RUC :</label>
                                <div class="col-sm-6">
                                  <input type="text" name="numRUCSunat" id="numRUCSunat" class="form-control" placeholder="Ingrese numero de RUC">
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="control-label col-sm-4">DNI :</label>
                                <div class="col-sm-6">
                                  <input type="text" name="numDNISunat" id="numDNISunat" class="form-control" placeholder="Ingrese numero de DNI" disabled="disabled">
                                </div>
                              </div>
                                <div class="alertaDoc"></div>
                              <div class="pull-right">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary" data-dismiss="modal" id="btnEnviarConsulta">Enviar Consulta</button>
                                
                              </div>
                            </form>
                           
                            <br>
                          </div>
                          <div class="modal-footer">
                            
                          </div>
                        </div>
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
 else {
   require 'noacceso.php';
 }
require 'footer.php';

    ?>

    <script type="text/javascript" src="scripts/registro_tecnico.js">
    </script>
    <?php
    }
    ob_end_flush();

     ?>
