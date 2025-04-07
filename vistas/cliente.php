<?php
//Activar el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location:index.php");
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
                <h1 class="box-title">Cliente&nbsp;&nbsp;&nbsp;&nbsp;<button id="agregarCliente" class="btn btn-success" onclick="mostrarform(true)">
                    <i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Agregar</button>
                  <a target="_blank" href="../reportes/clientepdf.php"> <button class="btn btn-warning btn-art" id="btnPDF"><i class="fa fa-file-pdf-o"></i> &nbsp;PDF</button> </a>
                  <button class="btn btn-default" data-toggle="modal" id="consultaSunat" data-target="#modalConsultarSunat">Consultar a SUNAT/RENIEC <i class="fa fa-question"></i></button>
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
                    <th>Nombre</th>
                    <th>Tipo documento</th>
                    <th>Numero documento</th>
                    <th>Telefono</th>
                    <th>Direccion</th>
                    <th>Email</th>
                    <th>Razón Social</th>

                  </thead>
                  <tbody>

                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>Nombre</th>
                    <th>Tipo documento</th>
                    <th>Numero documento</th>
                    <th>Telefono</th>
                    <th>Direccion</th>
                    <th>Email</th>
                    <th>Razón Social</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" style="height: 400px;" id="formularioregistros">

                <!--Formulario-->
                <form name="formulario" method="POST" id="formulario">

                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Nombre o Razon Social: (*)</label>
                    <input type="hidden" name="idpersona" id="idpersona">
                    <input type="hidden" name="tipo_persona" id="tipo_persona" value="Cliente">
                    <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required>

                  </div>

                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>DNI o RUC: (*)</label>
                    <select class="form-control select-picker" name="tipo_documento" id="tipo_documento" required>
                      <option value="DNI">DNI</option>
                      <option value="RUC">RUC</option>
                      <!-- <option value="CEDULA">CEDULA</option> -->
                    </select>
                  </div>

                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Numero de documento: (*)</label>
                    <input type="number" class="form-control" name="num_documento" id="num_documento" maxlength="20" placeholder="Numero" required>
                  </div>

                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Direccion:</label>
                    <input type="text" class="form-control" name="direccion" id="direccion" maxlength="200" placeholder="Direccion">
                  </div>

                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Telefono:</label>
                    <input type="number" class="form-control" name="telefono" id="telefono" maxlength="20" placeholder="Telefono">
                  </div>

                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Email:</label>
                    <input type="text" class="form-control" name="email" id="email" maxlength="50" placeholder="Email">
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Persona de Contacto:</label>
                    <input type="text" class="form-control" name="razon_social" id="razon_social" placeholder="Nombre">
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
  } else {
    require 'noacceso.php';
  }
  require 'footer.php';

  ?>

  <script type="text/javascript" src="scripts/cliente.js">
  </script>
<?php
}
ob_end_flush();

?>