<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();


if (!isset($_SESSION["nombre"])){
  header("Location: index.php");
}
else{

  require 'header.php';
  echo '<title>Ventas - Guía de Remisión</title>';

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
                          <h1 class="box-title">
                          &nbsp;&nbsp;&nbsp;&nbsp;Guia de Remision&nbsp;&nbsp;<button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)">
                              <i class="fa fa-plus-circle"></i>&nbsp;&nbsp;Agregar</button></h1>
                              <a target="_blank" href="cliente.php"> <button class="btn btn-info" id="btndestinatario"><i class="fa fa-envelope"></i>&nbsp;Destinatario</button></a>
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
                            <th>Motivo Translado</th>
                            <th>Comprobante</th>
                            <th>Número</th>
                            <!-- <th>Total Venta</th> -->
                            <!-- <th>Estado</th> -->
                          </thead>
                          <tbody>
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Motivo Translado</th>
                            <th>Documento</th>
                            <th>Número</th>
                            <!-- <th>Total Venta</th> -->
                            <!-- <th>Estado</th> -->
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                        <fieldset class=" form-group col-lg-12 col-sm-12 col-md-12 col-xs-12">
                          <legend>DATOS DEL INICIO DE TRASLADO</legend>
                            <div class="form-group col-lg-5 col-md-2 col-sm-2 col-xs-12">
                              <label>Tipo Comprobante(*):</label>
                              <select name="codigotipo_comprobante" id="codigotipo_comprobante" class="form-control selectpicker" 
                              data-live-search="" required="">
                              </select>
                            </div>
                            <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">                            
                              <label>Fecha de Emision(*):</label>
                              <!-- <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="" readonly> -->
                              <input type="date" class="form-control" name="fecha_emision" id="fecha_hora" required="" readonly>
                            </div>
                            <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                              <label>Fecha de Traslado(*):</label>
                              <!-- <input type="date" class="form-control" name="fecha_tras" id="fecha_tras" required=""> -->
                              <input type="date" class="form-control" name="fecha_traslado" id="fecha_ven" required="">
                            </div>
                            <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                              <label>Motivo De Traslado(*):</label>
                              <!-- <select name="codigo_traslado" id="codigo_traslado" class="form-control selectpicker" required data-style="btn-default" title="Motivo">  -->
                              <select name="codigo_traslado" class="form-control selectpicker" required data-style="btn-default" title="Motivo">
                                <?php

                                  require "../controlador/motivotranslado.controlador.php";
                                  $motivo_de_traslados = ControladorMotivoTranslado::ctrMostrarMotivoTranslado();
                                  foreach($motivo_de_traslados as $motivo_de_traslado){
                                    echo '<option value="'.$motivo_de_traslado["descripcion_traslado"].'">'.$motivo_de_traslado["descripcion_traslado"].'</option>';
                                    phpversion();
                                  }
                                  
                                ?>
                              </select>

                            </div>
                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label>Modalidad De Trasporte(*):</label>                           
                              <input type="text" class="form-control" 
                              name="mondalidad_transporte" placeholder="Modalidad de Transporte" onkeypress="return SoloLetras(event);">
                            </div> 
                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label>Tipo de Traslado(*):</label>                           
                              <input type="text" class="form-control"  name="tipo_translado" placeholder="Tipo de Traslado" onkeypress="return SoloLetras(event);">
                            </div> 
                            <div class="form-group col-lg-3 col-md-2 col-sm-2 col-xs-12">
                              <label>Peso bruto total de la Guia(*):</label>
                              <input type="peso" class="form-control" name="peso"  placeholder="Peso" onkeypress="return SoloNumeros();">
                            </div>
                        </fieldset>
      
                        <fieldset class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                          <legend>DATOS DEL DESTINATARIO</legend>
                          <div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-12">                        
                            <label>Apellidos y nombres, denominacion o razon(*):</label>
                            <select id="idcliente" name="idcliente" class="form-control selectpicker" data-live-search="true" required  data-style="btn-default" title="Denomicacion,Apellidos y nombres">
                            </select>
                          </div>
                          <div class="form-group col-lg-4 col-md-3 col-sm-3 col-xs-12">
                            <label>Nº De Documento o RUC(*):</label>
                            <input type="text" class="form-control" name="numdireccion" id="numdireccion" placeholder="Nº De Documento o RUC">
                          </div>                    
                          <div>
                            <input type="hidden" name="direccioncliente" id="direccioncliente">
                          </div>
                        </fieldset>
                        <fieldset class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                          <legend>DATOS DEL TRANSPORTISTA</legend>
                          <div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-12">                        
                            <label>Razón Social(*):</label>
                                  <select id="sltransportista" name="sltransportista" class="form-control selectpicker" data-live-search="true" required  data-style="btn-default" title="Razón social">
                            </select>
                          </div>
                          <div class="form-group col-lg-4 col-md-3 col-sm-3 col-xs-12">
                            <label>Nº De Documento o RUC(*):</label>
                            <input type="text" class="form-control" id="txtnumdoc" name="txtnumdoc"  placeholder="Nº De Documento o RUC">
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Marca(*):</label>
                            <input type="text" class="form-control" id="txtmarca" name="txtmarca"  placeholder="Marca">
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Placa(*):</label>
                            <input type="text" class="form-control" id="txtplaca" name="txtplaca"  placeholder="Placa">
                          </div>
                          
                          
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Licencia de Conducir(*):</label>
                            <input type="text" class="form-control" id="licencia" name="licencia"  placeholder="Licencia">
                          </div>
                          
                        </fieldset>
                        <fieldset class="col-lg-12 col-sm-12 col-md-12 col-xs-12">  
                          <legend>DIRECCION DE PUNTO DE PARTIDA</legend>
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                 
                            
                          <!-- <select class="form-control" id="idpartida" name="idpartida" title="Direccion de punto de partida" onkeypress="return LetrasyNum();"> -->
                          <select name="idpartida" class="form-control selectpicker" title="Direccion de punto de partida">
                              <?php
                                $direccion_punto_partidas = ControladorDireccionPuntoPartida::ctrMostrarDireccionPuntoPartida();
                                foreach($direccion_punto_partidas as $direccion_punto_partida){
                                  echo'<option value="'.$direccion_punto_partida["direccion"].'">'.$direccion_punto_partida["direccion"].'</option>';
                                }
                              ?>
                          </select>

                          </div>
                        </fieldset>
                        <fieldset class="col-lg-12 col-sm-12 col-md-12 col-xs-12">                        
                            <legend>DIRECCION DE PUNTO DE LLEGADA</legend>
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                            <input type="text" class="form-control" id="idpuntofinal" name="idpuntofinal"  placeholder="Dirección de punto de llegada" >
                            </select>
                            </div>
                        </fieldset>
                        <div>
                            <input type="hidden" name="impuesto" id="impuesto">
                        </div>

                        <div class="form-group col-lg-7 col-sm-7 col-md-7 col-xs-12">   
                          <a data-toggle="modal" href="#tblComprobantesModal">
                            <button id="btnagregar" type="button"
                            class="btn btn-primary"> <span class="fa fa-plus"></span>&nbsp;&nbsp;Selecionar Comprobante</button>
                          </a>
                        </div>
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                              <thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>U. Medida</th>
                                    <th>Cantidad</th>
                              </thead>
                            </table>
                          </div>

                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          
                          <input name="listacomprobante" id="listacomprobante" type="hidden" value="">                          

                          <?php
                              $registrogia = new ControladorGia();
                              $registrogia->ctrRegistroGia();
                              ?>

                            <!-- <input type="submit" value="Guardar"> -->

                            <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;  Guardar</button>
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

  <!-- window.location = "http://localhost/sGCF/vistas/guia.php"; -->
  <!-- Modal -->
  <div class="modal fade" id="tblComprobantesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content" style="width: 850px" >
        <div class="modal-header headerColor">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Seleccione Comprobante </h4>
        </div>
        <div class="modal-body">
         
          <table  id="tblcomprobantes" class="table table-striped table-bordered table-condensed table-hover">
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
                            <th>Proveedor</th>
                            <th>Usuario</th>
                            <th>Documento</th>
                            <th>Número</th>
                            <th>Total Venta</th>
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
  <!-- Fin modal -->
<?php
}
else
{
  require 'noacceso.php';
}

require 'footer.php';
?>
<script type="text/javascript" src="scripts/guia.js"></script>
<?php
}
ob_end_flush();
?>
  
