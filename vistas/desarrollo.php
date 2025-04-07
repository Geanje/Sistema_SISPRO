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
  if($_SESSION['servicio']==1)
  {
   ?>
  <style type="text/css">
    .servicio{
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
               <h1 class="box-title">Registro de Servicios de Desarrollo&nbsp;&nbsp;&nbsp;&nbsp;<button id="agregarservicio" class="btn btn-success"
                 onclick="mostrarform(true)">
                 <i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Nuevo Registro</button></h1>

                 <div class="box-tools pull-right">
                 </div>
               </div>
               <!-- /.box-header -->
               <!-- centro -->
               <div class="panel-body table-responsive"  id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Opciones</th>
                   <th>Fecha Ingreso</th>
                    <th style="width: 20%">Nombre Cliente</th>
                    <th>Tipo del Servicio</th>
                    <th>Estado Servicio</th>                  
                    <th>Estado Pago</th> 
                    <th>Tecnico Respon.</th>                  
                  </thead>
                  <tbody>

                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>Fecha Ingreso</th>
                    <th style="width: 20%">Nombre Clientes</th>
                    <th>Tipo del Equipo</th>
                    <th>Estado Servicio</th>
                    <th>Estado pago</th>
                    <th>Tecnico Respon.</th>
                     
                  </tfoot>
                </table>
              </div>

              <div class="panel-body" style="height:990px;" id="formularioregistros">
                <!--Formulario-->
                <form name="formulario" method="POST" id="formulario"> 
                  <div class="col-lg-12">
                    <label>Codigo de Servicio</label><br>  
                    <div  class="form-group col-lg-2 col-md-6 col-sm-6 col-xs-12">
                      <input type="numer" class="form-control" name="codigo_soporte" id="codigo_soporte" maxlength="50">
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label>Fecha de Ingreso: &nbsp; </label>
                      <input type="date" id="fecha_ingreso" name="fecha_ingreso"> 
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label>Fecha de Entrega: &nbsp; </label>
                      <input type="date" id="fecha_salida" name="fecha_salida">
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label>Fecha Restante </label>
                      <input type="text" id="fecha_restante" name="fecha_restante" readonly>
                    </div>

                  </div> 
                  <div class="col-lg-12"> 
                    <label>Datos del Cliente</label>  <br>                   
                    <div  class="form-group col-lg-5 col-md-6 col-sm-6 col-xs-12">                                 
                      <p>Nombre:<input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" maxlength="50"></p>
                    </div>
                    <div  class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12"> 
                      <p>Telefono:<input type="numer" class="form-control" name="telefono" id="telefono" maxlength="50"></p>
                    </div>
                    <div  class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12"> 
                      <p>Direccion:<input type="numer" class="form-control" name="direccion" id="direccion" maxlength="50"></p>
                    </div>
                                       
                  </div>
                  <div class="col-lg-12"> 
                    <label>Datos del Equipo</label>  <br>                   
                    <div  class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">                                 
                      <p>Tipo:  <input type="text" class="form-control" name="tipo_equipo" id="tipo_equipo" maxlength="50"></p>
                    </div>
                    <div  class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12"> 
                      <p>Marca y Modelo: <input type="text" class="form-control" name="marca" id="marca" maxlength="50"></p>
                    </div><div  class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12"> 
                      <p>Accesorio: <textarea type="text" class="form-control servicio" name="accesorio" id="accesorio" cols="40" rows="5" style="resize: none;"></textarea></p>
                    </div>
                    
                  </div>
                  <div class="col-lg-12 "> 
                    <label>Diagnostico del Equipo</label>  <br>                   
                    <div  class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">                                 
                      <p>Problema:  <textarea type="text" class="form-control servicio" name="problema" id="problema" cols="40" rows="5" style="resize: none;"></textarea></p>
                    </div>
                    <div  class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12"> 
                      <p>Solucion: <textarea type="text" class="form-control servicio" name="solucion" id="solucion" cols="40" rows="5" style="resize: none;"></textarea></p>
                    </div>
                    <div  class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12"> 
                      <p>Recomendacion Tecnica: <textarea type="text" class="form-control servicio" name="recomendacion" id="recomendacion" cols="40" rows="5" style="resize: none;"></textarea></p>
                    </div>
                    
                  </div>
                  <div class="col-lg-12">
                    <label>Estado</label><br> 
                    <div  class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 ">   
                      <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">                  
                        Servicio
                        <select class="form-control select-picker" name="estado_servicio" id="estado_servicio" required>
                           <option value="pendiente">Pendiente</option>
                           <option value="reparacion">Reparacion</option>
                           <option value="terminado">Terminado</option>
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
                  </div>
                  <div class="col-lg-12">
                    <label>Costo del Servicio</label><br>   
                    <div  class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 ">                     
                      <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                        <p>Total S/  <input type="numer" class="form-control" name="total" id="total" maxlength="50" ></p>
                      </div>
                      <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                        <p>A Cuenta S/ <input type="numer" class="form-control" name="cuota" id="cuota" maxlength="50"></p>
                      </div>
                      <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                      <p>Saldo S/ <input type="numer" class="form-control" name="saldo" id="saldo" maxlength="50" disabled="disabled"></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                      <label>Tecnico Responsable</label>
                      <input type="text" class="form-control" name="tecnico_respon" id="tecnico_respon" placeholder="Nombre">
                    </div> 
                      <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                      <label>Garantia del Servicio</label>
                      <input type="text" class="form-control" name="garantia" id="garantia" placeholder="Nombre">
                    </div> 
                  </div>
                  <!-- No olvidarse del ID : Atte: ANONYMOUS(EL PERRO) -->
                  <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <input type="hidden" name="idsoporte" id="idsoporte" value="idsoporte">
                  </div>

           
          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <br>
            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>&nbsp;&nbsp; Guardar</button>
            <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> &nbsp;&nbsp;Cancelar</button>
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

<script type="text/javascript" src="scripts/desarrollo.js">
</script>
<?php
}
ob_end_flush();

?>
