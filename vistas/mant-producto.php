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
if($_SESSION['almacen']==1)
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
                    <div class="box-header with-border ">
                          <h1 class="box-title ">Mantenimiento  Precio de Ventas &nbsp;
                          </h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Código</th>
                            <th>Stock</th>
                            <!--<th>Precio Compra</th>-->
                            <th>Precio Venta</th>
                            <th>Unidad Medida</th>
                            <th>Estado</th>
                            <th>Acción</th>
                          </thead>
                          <tbody>
                          </tbody>
                          <!-- <tfoot>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Código</th>
                            <th>Stock</th>
                            <th>Precio Compra</th>
                            <th>Precio Venta</th>
                            <th>Unidad Medida</th>
                            <th>Estado</th>
                          </tfoot> -->
                        </table>
                    </div>


                     <div class="panel-body" style="height: 400px;" id="formularioregistros">

                      <!--Formulario-->
                      <form name="formulario" method="POST" id="formulario">
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Descripción:</label>
                          <input type="hidden" name="id_detalle_ingreso" id="id_detalle_ingreso">
                          <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="50" placeholder="Nombre" required readonly>

                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Precio de Venta:</label>
                          <input type="number" class="form-control" name="precio_venta" id="precio_venta" step="0.01" >

                        </div>

                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>&nbsp;&nbsp;Guardar</button>
                            <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Cancelar</button>

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
}
else {
  require 'noacceso.php';
}
require 'footer.php';
?>
<script type="text/javascript" src="scripts/mant-producto.js"></script>

<?php
}
ob_end_flush();

 ?>
