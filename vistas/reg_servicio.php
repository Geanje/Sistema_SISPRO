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
               <h1 class="box-title">Registro de Servicios&nbsp;&nbsp;&nbsp;&nbsp;<button id="agregarservicio" class="btn btn-success"
                 onclick="mostrarform(true)">
                 <i class="fa fa-plus-circle"></i> &nbsp;&nbsp;Nuevo Registro</button></h1> <a target="_blank" href="../reportes/comprobante_mensual.php"> <button class="btn btn-info">Reporte de servicios</button> </a>

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
<style>
  

                      table {
                        border-collapse: collapse;
                      }

                      table, th, td {
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
  margin-left:45%;
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
               <div class="panel-body table-responsive"  id="listadoregistros">
               <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
  <thead>
    <tr>
      <th>Opciones</th>
      <th>Servicio</th>
      <th style="width: 40%">Costo</th>
      <th>Costo por día</th>
    </tr>
  </thead>
  <tbody>
    <!-- Aquí se mostrarán los datos generados por DataTables -->
  </tbody>
  <tfoot>
    <tr>
    <th>Opciones</th>
      <th>Servicio</th>
      <th style="width: 40%">Costo</th>
      <th>Costo por día</th>
    </tr>
  </tfoot>
</table>
              </div>

              <div class="panel-body" style="height:990px;" id="formularioregistros">
                <!--Formulario-->
                <form name="formulario" method="POST" id="formulario"> 
                  <div class="col-lg-12">
                  </div> 
                    <p>DATOS DEL SERVICIO</p>
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    
                    <div class="form-group col-lg-5 col-md-2 col-sm-2 col-xs-12"> 
                      <label>Nombre:</label><br>
                      <input type="hidden" class="form-control" name="idservicio" id="idservicio">
                      <input type="text" class="form-control" name="nombre" id="nombre">
                    </div>

                    <div class="form-group col-lg-5 col-md-2 col-sm-2 col-xs-12"> 
                      <label>Costo:</label>
                      <input type="text" class="form-control" name="costo" id="costo">
                    </div>
                    
                    <div class="form-group col-lg-5 col-md-2 col-sm-2 col-xs-12"> 
                      <label>Costo por dia (30):</label>
                      <input type="number" class="form-control" name="costo_dia" id="costo_dia" readonly>
                    </div>

                    <div class="form-group col-lg-5 col-md-2 col-sm-2 col-xs-12"> 
                      <label>Costo por dia (31):</label>
                      <input type="number" class="form-control" name="costo_dia_31" id="costo_dia_31" readonly>
                    </div>

                    <div class="form-group col-lg-5 col-md-2 col-sm-2 col-xs-12"> 
                      <label>Costo por dia (28):</label>
                      <input type="number" class="form-control" name="costo_dia_28" id="costo_dia_28" readonly>
                    </div>

                    <div class="form-group col-lg-5 col-md-2 col-sm-2 col-xs-12"> 
                      <label>Costo por dia (29):</label>
                      <input type="number" class="form-control" name="costo_dia_29" id="costo_dia_29" readonly>
                    </div>
                    
                    </div>

                  <div class="col-lg-12"> 
                    <div  class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12"> 
                      <!-- <p>Monto de pago <input type="text" class="form-control" name="monto_pago" id="monto_pago" maxlength="50"></p> -->
                    <!-- <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label>Fecha de Inicio: &nbsp; </label>
                      <input type="date" id="fecha_inicio" name="fecha_inicio">
                      
                    </div> -->
                    
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

<script type="text/javascript" src="scripts/reg_servicio.js">
</script>
<?php
}
ob_end_flush();

?>
