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
                <h1 class="box-title">Pago de Servicios&nbsp;&nbsp;&nbsp;&nbsp;<button id="agregarservicio" class="btn btn-success" onclick="mostrarform(true)">
                    <i class="fa fa-usd"></i> &nbsp;&nbsp;Realizar Pago</button></h1>
                <button class="btn btn-success" onclick="generar(event);"><i class="fa fa-book"></i> Generar Comprobantes</button>
                <a data-toggle="modal" href="#tblContrato"><button class="btn btn-success" onclick="generarIndividual(event);"><i class="fa fa-file-text"></i> Generar comprobante</button></a>
                <a data-toggle="modal" href="../reportes/borrarPDF.php"><button class="btn btn-danger" onclick="borrarPDF(event)"><i class="fa fa-trash-o"></i></button></a><!--😎24.08.2023 TODA LA LÍNEA 35-->
                <div class="box-tools pull-right">
                </div>
              </div>
              <div id="ventanaEmergente" class="ventana-emergente">
                <div class="contenido-ventana">
                  <span class="cerrarVentana" onclick="cerrarVentanaEmergente()">X</span>
                  <h3 class="titulo-ventana">Ingrese los datos del pago:</h3>
                  <table>
                    <tr>
                      <th>Fecha de Pago</th>
                      <th>Monto a pagar</th>
                      <!-- <th>Saldo</th> -->
                      <!-- <th>Tipo de pago</th> -->
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
                  <input type="hidden" name="periodo" id="periodo" value="nombremes">
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

              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th style="width: 9.2%;">Opciones</th>
                    <th style="width: 8%;">Fecha</th>
                    <th style="width: 20%;">Nombre Cliente</th>
                    <th style="width: 20%;">Servicio</th>
                    <th style="width: 4%;">Costo</th>
                    <th style="width: 6.5%;">Contrato</th>
                    <th style="width: 8.5%;">Periodo</th>
                    <th style="width: 8.5%;">Serie y Correlativo</th>
                    <th style="width: 5.5%;">Estado Pago</th>
                  </thead>
                  <tbody>

                  </tbody>
                  <tfoot>
                  <th style="width: 9.2%;">Opciones</th>
                    <th style="width: 8%;">Fecha</th>
                    <th style="width: 20%;">Nombre Cliente</th>
                    <th style="width: 20%;">Servicio</th>
                    <th style="width: 4%;">Costo</th>
                    <th style="width: 6.5%;">Contrato</th>
                    <th style="width: 8.5%;">Periodo</th>
                    <th style="width: 8.5%;">Serie y Correlativo</th>
                    <th style="width: 5.5%;">Estado Pago</th>
                  </tfoot>
                </table>
              </div>

              <div class="panel-body" style="height:990px;" id="formularioregistros">
                <!--Formulario-->
                <form name="formulario" method="POST" id="formulario">

                  &nbsp;&nbsp;&nbsp;&nbsp;<label>Datos del Cliente</label>
                  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    &nbsp;&nbsp;&nbsp;&nbsp;<label>Cliente:</label>
                    <span id="nombre_cliente_span"></span>
                    <br>
                    <div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-12" id="select">

                      <input type="hidden" name="id_p_servicio" id="idsoporte" value="">
                      <!-- <input type="hidden" name="idsoportepago" id="idsoportepago" value=""> -->

                      <select id="idcliente" name="idcliente" class="form-control selectpicker" data-live-search="true" required data-style="btn-default" title="Razón social" hidden>
                      </select>

                    </div>
                    <div class="form-group col-lg-5 col-md-2 col-sm-2 col-xs-12">
                      <label>Telefono:</label><br>
                      <input type="text" class="form-control" name="periodo" id="periodo">
                    </div>
                    <div class="form-group col-lg-5 col-md-2 col-sm-2 col-xs-12">
                      <label>Direccion:</label>
                      <input type="text" class="form-control" name="direccioncliente" id="direccioncliente">
                    </div>
                  </div>



                  <div class="col-lg-12">



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


                  </div>
                  <!-- fin -->



                  <div class="form-group col-lg-7 col-sm-7 col-md-7 col-xs-12">
                    <a data-toggle="modal" href="#tblComprobantesModal">
                      <button id="btnagregar" type="button" class="btn btn-primary"> <span class="fa fa-plus"></span>&nbsp;&nbsp;Seleccionar Comprobante</button>
                    </a>
                  </div>
                  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                      <thead style="background-color:#A9D0F5">
                        <th>Opciones</th>
                        <th>CLIENTE</th>
                        <th>CONCEPTO</th>
                        <th>MONTO</th>
                      </thead>
                    </table>

                  </div>
                  <input name="listacomprobante" id="listacomprobante" type="hidden" value="">



                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <br>
                    <button class="btn btn-primary" type="submit" id="btnGuardar" onclick="guardar();"><i class="fa fa-save"></i>&nbsp;&nbsp; Guardar</button>
                    <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> &nbsp;&nbsp;Cancelar</button>
                  </div>


                </form>
                <div>
                  TOTAL:
                  <span id="total_pagar"></span>
                </div>
              </div>

              <!-- MODAL PARA EDITAR LA FECHA DE CORTE DE LOS COMPROBANTES -->
              <div id="editComprobante" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header headerColor">
                      <h5 class="modal-title" id="my-modal-title">Ingrese la nueva fecha de corte</h5>
                      <button class="close mb-4" data-dismiss="modal" aria-label="Close">&times;
                        <!-- <span aria-hidden="true">&times;</span> -->
                      </button>
                    </div>
                    <div class="modal-body">
                      <!-- <h2>Modal con Input de Tipo Date</h2> -->
                      <form id="formFecha" name="formFecha" method="POST">
                        <input type="hidden" name="idcomprobante" id="idcomprobante">
                        <label for="fecha_corte">Selecciona una fecha:</label>
                        <input type="date" id="fecha_corte" name="fecha_corte">
                        <button id="btnGuardarfecha" type="submit" onclick="guardarFechaCorte(event);">Guardar</button>
                      </form>
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
    
    <!-- modal Para comprobantes individuales -->
    <div class="modal fade" id="tblContrato" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content" style="width: 170%;" >
          <div class="modal-header headerColor">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Seleccione el Contrato </h4>
          </div>
          <div class="modal-body">

            <table id="tblContratos" class="table table-striped table-bordered table-condensed table-hover" style="text-align: center;" >
              <thead>
                <th style="width: 30px;">Opciones</th>
                <!-- <th>Fecha emision</th> -->
                <th>Cliente</th>
                <th>Servicio</th>
                <th>Serie y Correlativo</th>
                <th>Monto de Pago</th>
                <th>Fecha de corte</th> <!--28.08.2023-->
                <th>Fecha de emisión</th><!--28.08.2023-->
                <!-- <th>Estado</th> -->
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                <th style="width: 30px;">Opciones</th>
                <!-- <th>Fecha emision</th> -->
                <th style="width: 150px;">Cliente</th>               
                <th>Servicio</th>
                <th>Serie y Correlativo</th>
                <th>Monto de Pago</th>
                <th>Fecha de corte</th>
                <th>Fecha de emisión</th>
                <!-- <th>Estado</th> -->
              </tfoot>
            </table>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="submit" id="btnGenerar" class="btn btn-success" data-dismiss="modal" onclick="guardarParaContratosSeleccionados(event);">Generar</button>
          </div>

        </div>
      </div>
    </div>

    <!-- MODAL PARA REALIZAR EL PAGO -->
    <div class="modal fade" id="tblComprobantesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content" style="width: 850px">
          <div class="modal-header headerColor">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Seleccione Comprobante </h4>
          </div>
          <div class="modal-body">

            <table id="tblcomprobantes" class="table table-striped table-bordered table-condensed table-hover">
              <thead>
                <th>Opciones</th>
                <th>Fecha emision</th>
                <th>Cliente</th>
                <th>Periodo</th>
                <th>Serie y Correlativo</th>
                <th>Monto de Pago</th>
                <th>Estado</th>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                <th>Opciones</th>
                <th>Fecha emision</th>
                <th>Cliente</th>
                <th>Periodo</th>
                <th>Serie y Correlativo</th>
                <th>Monto de Pago</th>
                <th>Estado</th>
              </tfoot>
            </table>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>

        </div>
      </div>
    </div>

  



  <?php
  } else {
    require 'noacceso.php';
  }
  require 'footer.php';

  ?>

  <script type="text/javascript" src="scripts/pago_servicios.js">
  </script>
<?php
}
ob_end_flush();

?>