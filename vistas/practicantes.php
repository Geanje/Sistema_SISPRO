<?php
//Activar el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location:index.php");
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
                <h1 class="box-title">Registro de Practicantes&nbsp;&nbsp;&nbsp;&nbsp;<button id="agregarSucursal" class="btn btn-success" onclick="mostrarform(true)">
                    <i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Agregar</button>
                  <a target="_blank" href="../reportes/practicantespdf.php"> <button class="btn btn-warning btn-art" id="btnPDF"><i class="fa fa-file-pdf-o"></i> &nbsp;PDF</button> </a>
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
                    <th>Nombres y Apellidos</th>
                    <th>Institucion</th>
                    <th>DNI</th>
                    <th>Sede</th>
                    <th>Especialidad</th>
                    <th>Modalidad</th>
                    <th>Correo</th>
                    <th>Nº Celular</th>
                    <th>Fecha de inicio</th>
                    <th>Fecha de termino</th>
                    <th>Estado</th>
                    <th>Grupo</th>
                    <th>Tarea</th>

                  </thead>
                  <tbody>

                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>Nombres y Apellidos</th>
                    <th>Institucion</th>
                    <th>DNI</th>
                    <th>Sede</th>
                    <th>Especialidad</th>
                    <th>Modalidad</th>
                    <th>Correo</th>
                    <th>Nº Celular</th>
                    <th>Fecha de inicio</th>
                    <th>Fecha de termino</th>
                    <th>Estado</th>
                    <th>Grupo</th>
                    <th>Tarea</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" style="height: 600px;" id="formularioregistros">

                <!--Formulario-->
                <form name="formulario" method="POST" id="formulario">


                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Nombres Apellidos:</label>
                    <input type="text" class="form-control" name="nombres_apellidos" id="nombres_apellidos" maxlength="50" placeholder="Nombres y Apellidos" required value="">
                    <input type="hidden" name="idpracticante" id="idpracticante">
                  </div>

                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>DNI:</label>
                    <input type="text" class="form-control" name="dni" id="dni" maxlength="20" placeholder="DNI" required value="">
                  </div>

                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Institucion:</label>
                    <select type="text" class="form-control" name="institucion" id="institucion" maxlength="200" placeholder="Institucion" required value="">
                    <option value="">Seleccionar Institución</option>
                    <option value="SENATI">SENATI</option>
                   
                    </select>
                  </div>

                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Sede:</label>
                    <input type="text" class="form-control" name="sede" id="sede" maxlength="200" placeholder="Sede">
                  </div>

                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Especialidad:</label>
                    <input type="text" class="form-control" name="especialidad" id="especialidad" maxlength="200" placeholder="Especialidad">
                  </div>

                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Modalidad:</label>
                    <input type="text" class="form-control" name="modalidad" id="modalidad" placeholder="Modalidad">
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Correo:</label>
                    <input type="text" class="form-control" name="correo" id="correo" maxlength="500" placeholder="Correo">
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Nº Celular:</label>
                    <input type="text" class="form-control" name="numero" id="numero" placeholder="Numero">
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Fecha de inicio:</label>
                    <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" maxlength="50" placeholder="dd/mm/yy">
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Fecha de termino:</label>
                    <input type="date" class="form-control" name="fecha_termino" id="fecha_termino" placeholder="dd/mm/yy">
                  </div>
                  <div id="estadoc" class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Estado:</label>
                    <select type="text" class="form-control" name="estado" id="estado" placeholder="Estado">
                    <option value="Activo">Activo</option>
                    <option value="Terminado">Terminado</option>
                    <option value="Retirado">Retirado</option>

                    </select>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Grupo:</label>
                    <input type="text" class="form-control" name="grupo" id="grupo" placeholder="Grupo">
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Tarea:</label>
                    <input type="text" class="form-control" name="tarea" id="tarea" placeholder="Tarea">
                  </div>
                  <!-- empieza modificacion script fecha-->
                  <script>
                      document.addEventListener('DOMContentLoaded', function () {
                              const fechaTerminoInput = document.getElementById('fecha_termino');
                              const estadoSelect = document.getElementById('estado');

                              function actualizarEstado() {
                                  const hoy = new Date();
                                  const fechaTerminoDate = new Date(fechaTerminoInput.value);
                                  
                                  // Set the end date time to the end of the day
                                  fechaTerminoDate.setHours(23, 59, 59, 999);

                                  // Si la fecha de término ha pasado
                                  if (fechaTerminoInput.value && hoy > fechaTerminoDate) {
                                      estadoSelect.value = 'Terminado';
                                  } else {
                                      estadoSelect.value = 'Activo';
                                  }
                              }

                              // Verificar el estado al cargar la página
                              actualizarEstado();

                              // También actualizar el estado cuando se cambia la fecha
                              fechaTerminoInput.addEventListener('change', actualizarEstado);

                              // Verificar periódicamente (cada minuto) si la fecha de término ha pasado
                              setInterval(actualizarEstado, 60000); // 60000 ms = 1 minuto
                          });
                  </script>

                  <!-- termina modificacion script fecha-->


                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>&nbsp;&nbsp; Guardar</button>
                    <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> &nbsp;&nbsp;Cancelar</button>
                    <br> <br>
                  </div>



                </form>

              </div>



              <!-- <div class="modal fade" id="modalConsultarSunat" role="dialog">
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
                       
                     </div>-->





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

  <script type="text/javascript" src="scripts/practicantes.js">
  </script>
<?php
}
ob_end_flush();

?>