<?php
//Activar el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location:index.php");
} else {
  require 'header.php';

  if ($_SESSION['consultav'] == 1) {



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
                <h1 class="box-title">REPORTE DEL MES<small style="color: #000000; font-size:16px"> Productos con mayor venta</small> &nbsp;&nbsp;
                  <a target="_blank" href="../reportes/rpt_mensual.php"> <button class="btn btn-warning btn-art"><i class="fa fa-file-pdf-o"></i> Reporte</button> </a>
                </h1>
                <div class="box-tools pull-right">
                </div>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive form-horizontal" id="listadoregistros" height="800px">
                <!-- <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        

                       </div> -->
                <div class="form-group">
                  <label class="control-label col-lg-2 col-md-2 col-sm-1 col-xs-12">MES</label>
                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <select class="form-control selectpicker" id="mes">
                      <option value="01">01-Enero</option>
                      <option value="02">02-Febrero</option>
                      <option value="03">03-Marzo</option>
                      <option value="04">04-Abril</option>
                      <option value="05">05-Mayo</option>
                      <option value="06">06-Junio</option>
                      <option value="07">07-Julio</option>
                      <option value="08">08-Agosto</option>
                      <option value="09">09-Setiembre</option>
                      <option value="10">10-Octubre</option>
                      <option value="10">11-Noviembre</option>
                      <option value="10">12-Diciembre</option>
                    </select>

                  </div>

                  <!--  </div>

                       <div class="form-group"> -->
                  <label class="control-label col-lg-1 col-md-1 col-sm-1 col-xs-12">AÑO</label>
                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <select class="form-control selectpicker" id="anno">
                      <?php
                      $x = 2019;
                      while ($x <= 2035) {
                        echo '<option value="' . $x . '">' . $x . '</option>';
                        $x++;
                      }
                      ?>
                      <!-- <option value="2018">2018</option>
                              <option value="2019">2019</option>
                              <option value="2020">2020</option>
                              <option value="2021">2021</option>
                              <option value="2022">2022</option>
                              <option value="2023">2023</option>
                              <option value="2024">2024</option>
                              <option value="2025">2025</option>
                              <option value="2026">2026</option>
                              <option value="2027">2027</option>
                              <option value="2028">2028</option>
                              <option value="2029">2029</option>
                              <option value="2030">2030</option> -->
                    </select>

                  </div>
                  <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                    <button class="btn btn-success" onclick="listarProductosMasVendidos();">Mostrar</button>

                  </div>
                </div>

                <br> <br> <br>

                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <!--<th width="12%">Imagen</th>-->
                    <th width="">Producto</th>
                    <th width="10%">Codigo</th>
                    <th width="10%">Cantidad Vendida</th>


                  </thead>
                  <tbody>

                  </tbody>

                </table>
              </div>



              <!-- </div> -->

              <!-- </div> -->

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

  <script type="text/javascript" src="scripts/reporte-mensual.js">
  </script>

<?php
}
ob_end_flush();

?>