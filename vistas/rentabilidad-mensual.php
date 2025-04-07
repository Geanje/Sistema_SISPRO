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

if($_SESSION['consultav']==1)
{

 ?>
 <!--Contenido-->
       <!-- Content Wrapper. Contains page content -->
       <div class="content-wrapper">

         <!-- Main content -->
         <section class="content">
             <div class="row">
               <div class="col-md-12" >
                   <div class="box">
                     <div class="box-header with-border">
                           <h1 class="box-title">RENTABILIDAD<small style="color: #000000; font-size:16px"> DEL MES</small></h1>
                         <div class="box-tools pull-right">
                         </div>
                     </div>
                     <!-- /.box-header -->
                     <!-- centro -->
                     <div class="panel-body table-responsive form-horizontal"  id="listadoregistros" height="800px">
                       <!-- <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        

                       </div> -->
                       <div class="form-group">
	                       <label class="control-label col-lg-1 col-md-2 col-sm-1 col-xs-12">MES</label>
                         <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
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
                              <option value="11">11-Noviembre</option>
                              <option value="12">12-Diciembre</option>
                             </select>

                         </div>
                         
                      <!--  </div>

                       <div class="form-group"> -->
                          <label class="control-label col-lg-1 col-md-1 col-sm-1 col-xs-12">AÑO</label>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                             <select class="form-control selectpicker" id="anno">
                              <?php 
                                $x = 2019;
                                while($x<=2035){
                                  echo '<option value="'.$x.'">'.$x.'</option>';
                                  $x++;
                                }
                               ?>
                            <!--   <option value="2019">2019</option>
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

                            <label class="control-label col-lg-1 col-md-1 col-sm-1 col-xs-12">%</label>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                              <input type="number" name="porcentaje" step="0.01" width="10px" class="form-control" placeholder="porcentaje..." id="porcen" required="required" min="0" value="0">
                             <!--  <select class="form-control selectpicker" id="porcentaje">
                                <option>1%</option>
                                <option>2%</option>
                              </select> -->
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                              <button class="btn btn-success" id="btnMostrar">Mostrar</button>
                              
                            </div>
                       </div>
                      
                      <br> <br>
                      <br>

                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th width="12%" >Ventas</th>
                            <!-- <th width="">Ventas</th> -->
                            <th width="10%">Compras</th>
                            <th width="10%">Porcentaje</th>
                            <th width="10%">Total</th>


                          </thead>
                          <tbody>
                            <td colspan="4" id="cuerpotabla"></td>
                          </tbody>
                         
                        </table>
                      <!-- </div> -->
                      <div class="row">
                        <div class="col-lg-3 col-xs-6">
                          <!-- small box -->
                          <div class="small-box bg-aqua">
                            <div class="inner">
                              <h3 id="cantventa">0.00</h3>

                              <p>Soles</p>
                            </div>
                            <div class="icon">
                              <i class="fa fa-shopping-cart"></i>
                            </div>
                            <div class="small-box-footer">
                              VENTAS <i class="fa fa-arrow-circle-right"></i>
                            </div>
                          </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                          <!-- small box -->
                          <div class="small-box bg-yellow">
                            <div class="inner">
                              <h3 id="cantcompra">0.00<sup style="font-size: 20px"></sup></h3>

                              <p>Soles</p>
                            </div>
                            <div class="icon">
                              <i class="ion ion-stats-bars"></i>
                            </div>
                            <div class="small-box-footer">
                              COMPRAS <i class="fa fa-arrow-circle-right"></i>
                            </div>
                          </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                          <!-- small box -->
                          <div class="small-box bg-red">
                            <div class="inner">
                              <h3 id="cantporcentaje">0.00</h3>

                              <p>Soles</p>
                            </div>
                            <div class="icon">
                              <i class="ion ion-person-add"></i>
                            </div>
                            <div class="small-box-footer">
                              PORCENTAJE <i class="fa fa-arrow-circle-right"></i>
                            </div>
                          </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                          <!-- small box -->
                          <div class="small-box bg-green">
                            <div class="inner">
                              <h3 id="canttotal">0.00</h3>

                              <p>Soles</p>
                            </div>
                            <div class="icon">
                              <i class="fa fa-thumbs-o-up"></i>
                            </div>
                            <div class="small-box-footer">
                              RENTABILIDAD <i class="fa fa-arrow-circle-right"></i>
                            </div>
                          </div>
                        </div>
                        <!-- ./col -->
                      </div>
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
   }
   else {
     require 'noacceso.php';
  }



require 'footer.php';

    ?>

    <script type="text/javascript" src="scripts/rentabilidad-mensual.js">
    </script>

<?php
}
ob_end_flush();

 ?>
