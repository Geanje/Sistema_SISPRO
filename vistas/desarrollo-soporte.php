<?php
//Activar el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location:index.php");
} else {

  require 'header.php';
  if ($_SESSION['servicio'] == 1) {
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
                <h1 class="box-title">Registro de Servicios de Desarrollo&nbsp;&nbsp;&nbsp;&nbsp;<button id="agregarservicio" class="btn-pyramid bg-accent-focus h-10 w-44 rounded-xl text-lg text-base-100 font-semibold shadow-xl hover:scale-105" onclick="mostrarform(true)">
                    <i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Nuevo Registro</button></h1>

                <div class="box-tools pull-right">
                </div>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Opciones</th>
                    <th>Fecha Ingreso</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Termino</th>
                    <th style="width: 20%">Nombre Cliente</th>
                    <th>Nombre proyecto</th>
                    <th>Estado servicio</th>
                    <th>Estado pago</th>
                    <th>Saldo a pagar</th>

                  </thead>
                  <tbody>

                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>Fecha Ingreso</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Termino</th>
                    <th style="width: 20%">Nombre Cliente</th>
                    <th>Nombre proyecto</th>
                    <th>Estado servicio</th>
                    <th>Estado pago</th>
                    <th>Saldo a pagar</th>


                  </tfoot>
                </table>
              </div>

              <div class="panel-body" style="height:100%;" id="formularioregistros">
                <!--Formulario-->
                <form name="formulario" method="POST" id="formulario">
                  <div id="colorin">
                    &nbsp;&nbsp;&nbsp;&nbsp;<label>Datos del Cliente</label> </div>
                  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <div class="form-group col-lg-3 col-md-5 col-sm-5 col-xs-12" id="select">
                      &nbsp;&nbsp;&nbsp;&nbsp;<label>Nombre Cliente:</label>
                      <input type="hidden" name="iddesarrollo" id="iddesarrollo" value="">
                      <input type="hidden" name="iddet_pag_desarrollo" id="iddet_pag_desarrollo" value="">
                      <select id="idcliente" name="idcliente" class="form-control selectpicker" data-live-search="true" data-style="btn-default" hidden>
                      </select>

                    </div>
                    <div id="invisible">
                      <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label>Nº : documento</label>
                        <input type="text" class="form-control" name="num_documento" id="num_documento" placeholder="Numero de documento" readonly>
                      </div>
                      <div class="form-group col-lg-3 col-md-2 col-sm-2 col-xs-12 ">
                        <label>Dirección:</label>
                        <input type="text" class="form-control" name="direccioncliente" id="direccioncliente" placeholder="Direccion" readonly>
                      </div>
                      <div class="form-group col-lg-3 col-md-2 col-sm-2 col-xs-12">
                        <label>Teléfono:</label>
                        <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Telefono" readonly>
                      </div>
                    </div>

                    <div class="form-group col-lg-3 col-md-2 col-sm-2 col-xs-12 ">
                      <label>Nombre Proyecto:</label>
                      <input type="text" class="form-control" name="nombre_proyecto" id="nombre_proyecto" placeholder="Nompre del proyecto">
                    </div>
                    <div id="loqui">
                      <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12 ">
                        <label>Costo Servicio:</label>
                        <input type="text" class="form-control" name="saldoRes" id="saldoRes" readonly>
                      </div>
                    </div>
                  </div>

                  <div class="modal-body" id="cuotasdepago">
                    <div class="col-lg-6">
                      <table id="tblpagos">
                        <!-- Cabecera 4 datos -->
                        <tr>
                          <th>Fecha de pago</th>
                          <th>Monto pagado</th>
                          <th>Saldo Restante</th>
                          <th>Tipo de pago &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        </tr>
                      </table>
                      <div> <br><br></div>
                    </div>
                  </div>

                  <style>
                    #btnagregar,
                    #btnagregarInter {
                      background: #19A7CE;
                      cursor: pointer;
                    }

                    #btneditintegrante {
                      margin-left: 67%;
                      background: #19A7CE;
                      cursor: pointer;
                    }

                    .cerrarVentana {
                      width: 24px;
                      height: 22px;
                      background: #19A7CE;
                      font-size: 20px;
                      color: white;
                      text-align: center;
                      border-radius: 5px 1px;

                    }
                  </style>


                  <div id="formu">
                    <div class="col-lg-12">
                      <label>Estado</label><br>
                      <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                        <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                          <label> Servicio</label>
                          <select class="form-control select-picker" name="estado_servicio" id="estado_servicio" required>
                            <option value="Pendiente">Pendiente</option>
                            <option value="Reparacion">Reparacion</option>
                            <option value="Terminado">Terminado</option>
                          </select>
                        </div>

                        <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                          <label>Entrega</label>
                          <select class="form-control select-picker" name="estado_entrega" id="estado_entrega" required>
                            <option value="Pendiente">Pendiente</option>
                            <option value="Entregado">Entregado</option>
                          </select>
                        </div>
                        <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                          <label> Pago</label>
                          <select class="form-control select-picker" name="estado_pago" id="estado_pago" required>
                            <option value="Pendiente">Pendiente</option>
                            <option value="Pagado">Pagado</option>
                            <option value="Sin Servicio">Sin Servicio</option>
                            <option value="Por Servicio">Por Servicio</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- inicio -->
                  <div id="integran">
                    <div class="col-lg-12  form-inline">
                      <div class="form-group col-lg-3 col-md-2 col-sm-2 col-xs-12 ">
                        <label>Costo del Desarrollo</label> <br>
                        <p>Total: S/ <input type="numer" class="form-control" name="costo_desarrollo" id="costo_desarrollo" maxlength="50"></p>
                        <br>
                      </div>



                      <!-- <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                        <p>A Cuenta S/ <input type="numer" class="form-control" name="cuota" id="cuota" maxlength="50"></p>
                      </div> -->
                      <!-- <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                      <p>Saldo S/ <input type="numer" class="form-control" name="saldo" id="saldo" maxlength="50" readonly></p>
                      </div> -->

                      <div class="form-group col-lg-4 col-md-2 col-sm-2 col-xs-1">

                        <label>Integrantes:</label>
                        <div class="col-lg-12 " style="display: flex; align-items: center">
                          <button type="button">Agregar Integrantes</button>
                          <input type="button" onclick="vizualizarVentana()" value="+" id="btnagregarInter" style="margin-left: 5px;">


                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-body" id="totalIntegrantes">
                    <div class="col-lg-6">
                      <table id="listarIntegrantes">

                        <tr>
                          <th>Lista de los Integrantes</th>

                        </tr>
                      </table>
                      <div> <br><br></div>
                    </div>
                  </div>






                  <div class="col-lg-12 form-inline">
                  </div>
                  <div id="editarventanita" class="ventana-emergente">

                    <div class="contenido-ventana">

                      <span class="cerrarVentana" onclick="cierraVentana()">X</span>
                      <h3 class="titulo-ventana">Ingrese nombre del Integrante:</h3>
                      <table id="integrantes">
                        <tr>
                          <th> Nombre </th>
                          <th><input type="text" name="integrante" id="integrante"></th>
                        </tr>

                        </tr>

                      </table>
                      <div class="boton-container">
                        <button type="button" id="btnguardarIntegrantes" onclick="EditarIntegrantes()">Guardar</button>
                      </div>
                      <input type="hidden" name="idintegrant_desarrollo" value="">
                    </div>
                  </div>
                  <div id="ventanita" class="ventana-emergente">

                    <div class="contenido-ventana">

                      <span class="cerrarVentana" onclick="cierraVentanitaEmergente()">X</span>
                      <h3 class="titulo-ventana">Ingrese nombre del Integrante:</h3>
                      <table id="integrantes">
                        <tr>
                          <th> Nombre </th>
                          <th><input type="text" name="integrantes1" id="integrantes1"></th>
                        </tr>

                        </tr>

                      </table>
                      <div class="boton-container">

                        <button type="button" id="btnguardarIntegrantes" onclick="guardarIntegrantes()">Guardar</button>
                      </div>
                      <input type="hidden" name="idintegrant_desarrollo" value="">
                    </div>
                  </div>

                  <style>
                    #listarIntegrantes {
                      width: 100%;
                      /* Otros estilos que tengas para la tabla */
                    }
                  </style>



                  <!-- No olvidarse del ID : Atte: ANONYMOUS(EL PERRO) -->
                  <!-- <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <input type="hidden" name="idsoporte" id="idsoporte" value="idsoporte">
                  </div> -->

                  <!-- fin -->

                  <div id="ventanaEmergente" class="ventana-emergente">
                    <div class="contenido-ventana">
                      <span class="cerrarVentana" onclick="cerrarVentanaEmergente()">X</span>
                      <h3 class="titulo-ventana">Ingrese los datos del pago:</h3>
                      <table>
                        <tr>
                          <th>Fecha de Pago</th>
                          <th>Monto a pagar</th>
                          <th>Saldo</th>
                          <th>Tipo de pago</th>
                        </tr>
                        <tr>
                          <td><input type="date" name="fecha" id="fecha"></td>
                          <td><input type="number" name="monto" id="monto"></td>
                          <td><input type="number" name="saldo" id="saldo"></td>
                          <td><input type="text" name="tipo_pago" id="tipo_pago"></td>
                        </tr>
                      </table>

                      <div class="boton-container">
                        <button type="button" id="btnguardarpagos" onclick="guardarPagos()">Guardar</button>
                      </div>


                      <!-- <input type="hidden" name="idsoporte" value=""> -->
                      <!-- <input type="hidden" name="idcliente" value=""> -->
                      <input type="hidden" name="iddet_pag_desarrollo" value="">
                    </div>
                  </div>

                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <br>
                    <button class="btn btn-success" type="submit" id="btnGuardar"><i class="fa fa-save"></i>&nbsp;&nbsp; Guardar</button>
                    <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> &nbsp;&nbsp;Cancelar</button>
                  </div>

                  <!-- <button  type="submit" id="btnGuardar"><i class="login-form-btn"></i>&nbsp;&nbsp; Guardar</button>
            <button class="btn btn-danger " onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> &nbsp;&nbsp;Cancelar</button>
            <button class="btn-pyramid bg-accent-focus h-10 w-44 rounded-xl text-lg text-base-100 font-semibold shadow-xl hover:scale-105" data-v-efcec222="">Iniciar Sesión</button>-->

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
  <link rel="stylesheet" type="text/css" href="../public/css/ciclo.css">
  <script type="text/javascript" src="scripts/desarrollo-soporte.js">
  </script>

<?php
}
ob_end_flush();

?>