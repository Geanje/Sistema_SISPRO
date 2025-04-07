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
    <style type="text/css">
      .servicio {
        height: 100px;



      }
    </style>
    <!--Contenido-->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h1 class="box-title" id="idPrincipal">Registro de Servicios&nbsp;&nbsp;&nbsp;&nbsp;
                  <button id="agregarservicio" class="btn btn-success" onclick="mostrarform(true)">
                    <i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Nuevo Soporte
                  </button>
                  <button id="agregars" class="btn btn-primary" onclick="mostrarform(true)">
                    <i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Nuevo Servicio
                  </button>
                  <a href="SalidaArticulos.php"> <button class="btn btn-danger" id="btnSalida">
                      <i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;Salida de Articulos</button> </a>
                  <a href="../reportes/rptservicio-tenico.php"> <button class="btn btn-warning btn-art" id="btnPDF">
                      <i class="fa fa-file-pdf-o"></i>&nbsp;PDF</button> </a>

                </h1>
                <h1 class="box-title" id="idSoporteTec">Nuevo Registro de Soporte Tecnico</h1>
                <h1 class="box-title" id="idServGenetal">Nuevo Registro de Servicio General</h1>
                <div class="box-tools pull-right">
                </div>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th style="width: 11%">Opciones</th>
                    <th style="width: 6%">Fecha Ingreso</th>
                    <th style="width: 21%">Nombre Cliente</th>
                    <th style="width: 10%">Codigo Servicio</th>
                    <th style="width: 10%">Area de Servicio</th>
                    <th style="width: 12%">Tipo de Servicio</th>
                    <th style="width: 10%">Estado Servicio</th>
                    <th style="width: 8%">Estado Pago</th>
                  </thead>
                  <tbody>

                  </tbody>
                  <tfoot>
                    <th style="width: 11%">Opciones</th>
                    <th style="width: 6%">Fecha Ingreso</th>
                    <th style="width: 21%">Nombre Cliente</th>
                    <th style="width: 10%">Codigo Servicio</th>
                    <th style="width: 10%">Area de Servicio</th>
                    <th style="width: 12%">Tipo de Servicio</th>
                    <th style="width: 10%">Estado Servicio</th>
                    <th style="width: 8%">Estado Pago</th>
                  </tfoot>
                </table>
              </div>

              <div class="panel-body" style="height:auto;" id="formularioregistros">
                <!--Formulario-->
                <form name="formulario" method="POST" id="formulario">
                  <div class="col-lg-12">

                    <div class="form-group col-lg-2 col-md-6 col-sm-6 col-xs-12">
                      <label>Codigo de Servicio</label><br>
                      <select name="codigotipo_comprobante" id="codigotipo_comprobante" class="form-control select-picker" required="" data-live-search="true" readonly></select>
                    </div>

                    <label>Área de Servicio</label><br>
                    <div class="form-group col-lg-2 col-md-6 col-sm-6 col-xs-12">
                      <select class="form-control select-picker" name="area_servicio" id="area_servicio" required>
                        <option value="Oficina" id="oficina">Oficina</option>
                        <option value="Area de Soporte" id="areaSop">Area de Soporte</option>
                        <option value="Area TI" id="AreaTi">Area TI</option>
                        <option value="Ingieneria Electrica" id="ingElectrica">Ingieneria Electrica</option>
                      </select>
                    </div>
                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label>Fecha de Ingreso: &nbsp; </label>
                      <input type="date" id="fecha_ingreso" name="fecha_ingreso" required>

                    </div>
                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label>Fecha de Entrega: &nbsp; </label>
                      <input type="date" id="fecha_salida" name="fecha_salida">
                    </div>
                  </div>
                  &nbsp;&nbsp;&nbsp;&nbsp;<label>Datos del Cliente</label>
                  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    &nbsp;&nbsp;&nbsp;&nbsp;<label>Cliente:</label>
                    <span id="nombre_cliente_span"></span>
                    <br>
                    <div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-12" id="select">

                      <input type="hidden" name="idsoporte" id="idsoporte" value="">
                      <input type="hidden" name="idsoportepago" id="idsoportepago" value="">
                      <select id="idcliente" name="idcliente" class="form-control" data-live-search="true" required data-style="btn-default" title="Razón social" hidden>
                      </select>

                    </div>
                    <div class="form-group col-lg-5 col-md-2 col-sm-2 col-xs-12">
                      <label>Telefono:</label><br>
                      <input type="text" class="form-control" name="telefono" id="telefono">
                    </div>
                    <div class="form-group col-lg-5 col-md-2 col-sm-2 col-xs-12">
                      <label>Direccion:</label>
                      <input type="text" class="form-control" name="direccioncliente" id="direccioncliente">
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <label>Datos del Equipo</label> <br>
                    <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                      <p>Tipo de Servicio:
                        <select class="form-control select-picker" name="tipo_servicio" id="tipo_servicio" required>
                          <option value="Soporte Técnico">Soporte Técnico</option>
                          <option value="Soporte Remoto">Soporte Remoto</option>
                          <option value="Servicio Domicilio">Servicio Domicilio</option>
                          <option value="Desarrollo Software">Desarrollo Software</option>
                          <option value="Facturacion Electronica">Facturacion Electronica</option>
                          <option value="Tecnologia en Seguridad">Tecnologia en Seguridad</option>
                          <option value="Redes & Infraestructura">Redes & Infraestructura</option>
                          <option value="Instalaciones Electricas">Instalaciones Electricas</option>
                          <option value="Mantenimiento Electrico">Mantenimiento Electrico</option>
                          <option value="Mantenimiento Industrial">Mantenimiento Industrial</option>
                        </select>
                      <p>
                    </div>
                    <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12" id="xd">
                      <p class="text-marca" id="ti_Marca">Marca y Modelo: <input type="text" class="form-control select-marca" name="marca" id="marca" maxlength="50"></p>
                    </div>
                    <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                      <p class="text-accesorio" id="ti_Accesorio">Accesorio: <textarea type="text" class="form-control servicio select-accesorio" name="accesorio" id="accesorio" cols="40" rows="5" style="resize: none;"></textarea></p>
                    </div>

                  </div>
                  <div class="col-lg-12 ">
                    <label id="lblDiagnostico">Diagnostico del Equipo</label> <br>
                    <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                      <p id="pProblema">Problema: </p><textarea type="text" class="form-control servicio" name="problema" id="problema" cols="40" rows="5" style="resize: none;"></textarea>
                    </div>
                    <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                      <p id="sSolucion">Solucion: </p><textarea type="text" class="form-control servicio" name="solucion" id="solucion" cols="40" rows="5" style="resize: none;"></textarea>
                    </div>
                    <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                      <p id="rRecomendacion">Recomendacion Tecnica: </p><textarea type="text" class="form-control servicio" name="recomendacion" id="recomendacion" cols="40" rows="5" style="resize: none;"></textarea>
                    </div>

                  </div>
                  <div class="col-lg-12">
                    <label>Estado</label><br>
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                      <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                        Servicio
                        <select class="form-control select-picker" name="estado_servicio" id="estado_servicio" required>
                          <option value="Pendiente">Pendiente</option>
                          <option value="Reparacion">Reparacion</option>
                          <option value="Terminado">Terminado</option>
                        </select>
                      </div>

                      <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                        Entrega
                        <select class="form-control select-picker" name="estado_entrega" id="estado_entrega" required>
                          <option value="Pendiente">Pendiente</option>
                          <option value="Entregado">Entregado</option>
                        </select>
                      </div>
                      <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                        Pago
                        <select class="form-control select-picker" name="estado_pago" id="estado_pago" required>
                          <option value="Pendiente">Pendiente</option>
                          <option value="Pagado">Pagado</option>
                          <option value="Sin Servicio">Sin Servicio</option>
                          <option value="Por Servicio">Por Servicio</option>
                        </select>
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
                      </div>
                    </div>

                    <!-- inicio -->
                    <div class="col-lg-12">
                      <label>Costo del Servicio</label><br>
                      <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                        <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                          <p>Total S/ <input type="numer" class="form-control" name="total" id="total" maxlength="50"></p>
                        </div>
                      </div>

                    </div>
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
                            <td><input type="date" name="fecha_pago" id="fecha_pago"></td>
                            <td><input type="number" name="cuotas" id="cuotas"></td>
                            <td><input type="number" name="saldos" id="saldos"></td>
                            <td><input type="text" name="tipo_pago" id="tipo_pago"></td>
                          </tr>
                        </table>
                        <div class="boton-container">
                          <button type="button" id="btnguardarpagos" onclick="guardarPagos()">Guardar</button>
                        </div>
                        <!-- <input type="hidden" name="idsoporte" value=""> -->
                        <!-- <input type="hidden" name="idcliente" value=""> -->
                        <input type="hidden" name="idsoportepago" value="">
                      </div>
                    </div>

                    <div id="ventanaEmergente2" class="ventana-emergente">
                      <div class="contenido-ventana" id="integ">
                        <span class="cerrarVentana" onclick="cerrarVentanaEmergente2()">X</span>
                        <h3 class="titulo-ventana">Ingrese los datos Intregrantes:</h3>
                        <table>
                          <tr>
                            <th>Nombres</th>
                          </tr>
                          <tr>
                            <td><input type="text" name="nombre_integrante" id="nombre_integrante"></td>
                          </tr>
                        </table>
                        <div class="boton-container">
                          <button type="button" id="btnguardarpagos" onclick="guardarIntegrantes()">Guardar</button>
                        </div>
                        <input type="hidden" name="id_integrante_servicio" value="">
                      </div>
                    </div>

                    <style>
                      table {
                        border-collapse: collapse;
                      }

                      table,
                      th,
                      td {
                        border: 1px solid black;
                        padding: 5px;
                      }

                      th {
                        font-weight: bold;
                        text-align: center;
                      }

                      tr {
                        border: 1px solid black;
                      }

                      .ventana-emergente {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background-color: rgba(0, 0, 0, 0.5);
                        display: none;
                        z-index: 9999;
                      }

                      #nombre_integrante {
                        width: 400px;
                      }


                      #btnguardarpagos {
                        margin-top: 5px;
                        margin-left: 45%;
                      }

                      .cerrarVentana {
                        position: absolute;
                        top: 0;
                        right: 0;
                        margin-right: 13px;
                        margin-top: 7px;
                        cursor: pointer;
                      }

                      .contenido-ventana {
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        background-color: #fff;
                        padding: 20px;
                        border-radius: 5px;
                        border: 3px solid #19A7CE;
                        display: flex;
                        flex-direction: column;
                      }

                      .titulo-ventana {
                        color: #fff;
                        background-color: #19A7CE;
                        padding: 10px;
                        margin-bottom: 20px;
                      }

                      @keyframes pulsate {
                        0% {
                          box-shadow: 0 0 20px rgba(255, 0, 100, 0.5);
                        }

                        50% {
                          box-shadow: 0 0 40px rgba(255, 0, 50, 0.9);
                        }

                        100% {
                          box-shadow: 0 0 20px rgba(255, 0, 0, 0.5);
                        }

                      }
                    </style>
                    <div class="col-lg-12">
                      <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" id="selectrdf">
                        <label>Técnico Responsable:</label>
                        <select id="idtecnico" name="idtecnico" class="form-control selectpicker" data-live-search="true" data-style="btn-default" title="Tecnico">
                        </select>
                      </div>
                      <div class="modal-body" id="totalIntegrantes">
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <table id="listarIntegrantes">
                            <tr>
                              <th>Intregrantes
                              </th>
                            </tr>
                          </table>
                        </div>
                      </div>

                      <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label>Garantia del Servicio</label>
                        <input type="text" class="form-control" name="garantia" id="garantia" placeholder="Nombre">
                      </div>
                    </div>


                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <br>
                      <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>&nbsp;&nbsp; Guardar</button>
                      <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> &nbsp;&nbsp;Cancelar</button>
                    </div>

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
  } else {
    require 'noacceso.php';
  }
  require 'footer.php';

  ?>

  <script type="text/javascript" src="scripts/servicios-soporte.js">
  </script>
<?php
}
ob_end_flush();

?>