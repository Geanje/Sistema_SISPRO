<?php 
ob_start();
session_start();
  if (!isset($_SESSION['nombre'])) {
    header("Location:index.php");
  }else{
    require 'header.php';

  if($_SESSION['servicio']==1){
    require_once '../modelos/proceso_desarrollo.php';
    $proceso = new Proceso_desarrollo();
    $iddesarrollo = isset($_GET["iddesarrollo"]) ? limpiarCadena($_GET["iddesarrollo"]) : "";
    $rpta=$proceso->mostrar($iddesarrollo);
    $reg = $rpta->fetch_assoc();
    ?>
    <div class="content-wrapper">
    <section class="content">
        <div class="row">
          <div class="col-md-12">
              <div class="box box-primary">
              <div class="box-header with-border">
          <h1 class="box-title"> PROCESO DE DESARROLLO</h1>
            <div class="box-tools pull-right">
            </div>
          </div>
                <div class="panel-body table-responsive"  id="listadofilaistros">
                 <form  id="formulario" name="formulario"  enctype="multipart/form-data">
                 <div class="form-group col-lg-6 col-md-2 col-sm-2 col-xs-12">
                 <label class="linea_p">NOMBRE DEL CLIENTE:</label>
           <br>
           <spam id="nombre_cliente"><?php echo $reg['nombre']; ?></spam>
              </div>
              <input type="hidden" name="idproc_desarrollo" id="idproc_desarrollo" value="<?php echo $reg['idproc_desarrollo']; ?>">
                      <div class="form-group col-lg-6 col-md-2 col-sm-2 col-xs-12" >
              <label class="linea_p">NOMBRE DEL PROYECTO:</label><br>
              <spam id="nombre_proyecto"><?php echo $reg['nombre_proyecto']; ?></spam>
              </div>
           
                       <div class="col-lg-12">
                       <label>ANÁLISIS</label><br>
                     <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                       <label>Fecha de Inicio:</label>
                       <input type="date" id="AN_fecha_inicio" name="AN_fecha_inicio"  onchange="calcularDiferencia()" value="<?php echo $reg['AN_fecha_inicio'];?>">
                     </div>
                     <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
             <label>Fecha de Término:</label>
             <input type="date" id="AN_fecha_termino" name="AN_fecha_termino" onchange="calcularDiferencia()" value="<?php echo $reg['AN_fecha_termino'];?>">
           </div>
           <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
             <label>Días Restantes:</label>
             <input type="number" id="AN_fecha_restante" name="AN_fecha_restante"  readonly>
           </div>
           <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
         <label >Día Actual:</label><br>       
         <div id="AN_progreso_dia"></div>
        <!--<div id="AN_progreso_dia" class="progress-bar"></div>
         <div id="dias-element-AN_progreso_dia" class="dias-element"></div>-->


       </div>
       <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
       <label>Estado:</label>
       <select class="form-control select-picker" name="AN_estado" id="AN_estado" required>
           <option value="Pendiente"<?php if ($reg['AN_estado'] === 'Pendiente') echo ' selected'; ?>>Pendiente</option>
           <option value="Proceso"<?php if ($reg['AN_estado'] === 'Proceso') echo ' selected'; ?>>Proceso</option>
           <option value="Terminado"<?php if ($reg['AN_estado'] === 'Terminado') echo ' selected'; ?>>Terminado</option>
       </select>
       </div>
                   <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
       <label>Comentario:</label>
       <textarea class="form-control textarea elegant-background" name="AN_comentario" id="AN_comentario" cols="40" rows="2" style="resize: none;"><?php echo $reg['AN_comentario'];?></textarea>
     </div>
               <label>DISEÑO</label><br>  
               <div class="form-group  col-lg-2 col-md-2 col-sm-2 col-xs-12 ">
                 <label>Fecha de Inicio: &nbsp; </label>
                  <input type="date" id="DI_fecha_inicio" name="DI_fecha_inicio" onchange="calcularDiferencia()"value="<?php echo $reg['DI_fecha_inicio'];?>">
               </div>
               <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                 <label>Fecha de Termino: &nbsp; </label>
                 <input type="date" id="DI_fecha_termino" name="DI_fecha_termino"onchange="calcularDiferencia()" value="<?php echo $reg['DI_fecha_termino'];?>">
               </div>
               <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                 <label>Dias Restante:&nbsp; </label>
                 <input type="number" id="DI_fecha_restante" name="DI_fecha_restante" readonly>
               </div>
               <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12" >
                 <label>Dia Actual:&nbsp;  </label>
                 <div id="DI_progreso_dia"></div>
               </div>
               <div  class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12 ">   
               <label>Estado</label>     
                   <select class="form-control select-picker" name="DI_estado" id="DI_estado" required>
                   <option value="Pendiente"<?php if ($reg['DI_estado'] === 'Pendiente') echo ' selected'; ?>>Pendiente</option>
                  <option value="Proceso"<?php if ($reg['DI_estado'] === 'Proceso') echo ' selected'; ?>>Proceso</option>
                  <option value="Terminado"<?php if ($reg['DI_estado'] === 'Terminado') echo ' selected'; ?>>Terminado</option>
                   </select>
               </div> 
               <div class="form-group  col-lg-2 col-md-2 col-sm-2 col-xs-12">
                 <label>Comentario:</label>
                 <textarea type="text" class="form-control desarrollo" name="DI_comentario" id="DI_comentario" cols="40" rows="2" style="resize: none;"><?php echo $reg['DI_comentario'];?></textarea>
               </div>
               <label>DESARROLLO</label><br>  
               <div class="form-group  col-lg-2 col-md-2 col-sm-2 col-xs-12 ">
                 <label>Fecha de Inicio: &nbsp; </label>
                 <input type="date" id="DE_fecha_inicio" name="DE_fecha_inicio" onchange="calcularDiferencia()" value="<?php echo $reg['DE_fecha_inicio'];?>">
               </div>
               <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                 <label>Fecha de Termino: &nbsp; </label>
                 <input type="date" id="DE_fecha_termino" name="DE_fecha_termino"onchange="calcularDiferencia()" value="<?php echo $reg['DE_fecha_termino'];?>">
               </div>
               <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                 <label>Dias Restante:&nbsp; </label>
                 <input type="number" id="DE_fecha_restante" name="DE_fecha_restante" readonly>
               </div>
               <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12" >
                 <label>Dia Actual:&nbsp;  </label>
                 <div id="DE_progreso_dia">  </div>
               </div>
               <div  class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12 ">   
               <label>Estado</label>     
                   <select class="form-control select-picker" name="DE_estado" id="DE_estado" required>
                   <option value="Pendiente"<?php if ($reg['DE_estado'] === 'Pendiente') echo ' selected'; ?>>Pendiente</option>
                   <option value="Proceso"<?php if ($reg['DE_estado'] === 'Proceso') echo ' selected'; ?>>Proceso</option>
                   <option value="Terminado"<?php if ($reg['DE_estado'] === 'Terminado') echo ' selected'; ?>>Terminado</option>
                   </select>
               </div> 
               <div class="form-group  col-lg-2 col-md-2 col-sm-2 col-xs-12">
                 <label>Comentario:</label>
                 <textarea type="text" class="form-control desarrollo" name="DE_comentario" id="DE_comentario" cols="40" rows="2" style="resize: none;"><?php echo $reg['DE_comentario'];?></textarea>
               </div>
               <label>IMPLEMENTACIÓN</label><br>  
               <div class="form-group  col-lg-2 col-md-2 col-sm-2 col-xs-12 ">
                 <label>Fecha de Inicio: &nbsp; </label>
                 <input type="date" id="IM_fecha_inicio" name="IM_fecha_inicio" onchange="calcularDiferencia()" value="<?php echo $reg['IM_fecha_inicio'];?>">
               </div>
               <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                 <label>Fecha de Termino: &nbsp; </label>
                 <input type="date" id="IM_fecha_termino" name="IM_fecha_termino"onchange="calcularDiferencia()" value="<?php echo $reg['IM_fecha_termino'];?>">
               </div>
               <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                 <label>Dias Restante:&nbsp; </label>
                 <input type="number" id="IM_fecha_restante" name="IM_fecha_restante" readonly>
               </div>
               <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12" >
                 <label>Dia Actual:&nbsp;  </label>
                 <div id="IM_progreso_dia"></div>
               </div>
               <div  class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12 ">   
               <label>Estado</label>     
                   <select class="form-control select-picker" name="IM_estado" id="IM_estado" required>
                   <option value="Pendiente"<?php if ($reg['IM_estado'] === 'Pendiente') echo ' selected'; ?>>Pendiente</option>
                   <option value="Proceso"<?php if ($reg['IM_estado'] === 'Proceso') echo ' selected'; ?>>Proceso</option>
                   <option value="Terminado"<?php if ($reg['IM_estado'] === 'Terminado') echo ' selected'; ?>>Terminado</option>
                   </select>
               </div> 
               <div class="form-group  col-lg-2 col-md-2 col-sm-2 col-xs-12">
                 <label>Comentario:</label>
                 <textarea type="text" class="form-control desarrollo" name="IM_comentario" id="IM_comentario" cols="40" rows="2" style="resize: none;"><?php echo $reg['IM_comentario'];?></textarea>
               </div>
               <label>MANTENIMIENTO</label><br>  
               <div class="form-group  col-lg-2 col-md-2 col-sm-2 col-xs-12 ">
                 <label>Fecha de Inicio: &nbsp; </label>
                 <input type="date" id="MAN_fecha_inicio" name="MAN_fecha_inicio" onchange="calcularDiferencia()" value="<?php echo $reg['MAN_fecha_inicio'];?>">
               </div>
               <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                 <label>Fecha de Termino: &nbsp; </label>
                 <input type="date" id="MAN_fecha_termino" name="MAN_fecha_termino"onchange="calcularDiferencia()" value="<?php echo $reg['MAN_fecha_termino'];?>">
               </div>
               <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                 <label>Dias Restante:&nbsp; </label>
                 <input type="number" id="MAN_fecha_restante" name="MAN_fecha_restante" readonly>
               </div>
               <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12" >
                 <label>Dia Actual:&nbsp;  </label>
                 <div id="MAN_progreso_dia">  </div>
               </div>
               <div  class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12 ">   
               <label>Estado</label>     
                   <select class="form-control select-picker" name="MAN_estado" id="MAN_estado" required>
                   <option value="Pendiente"<?php if ($reg['MAN_estado'] === 'Pendiente') echo ' selected'; ?>>Pendiente</option>
                   <option value="Proceso"<?php if ($reg['MAN_estado'] === 'Proceso') echo ' selected'; ?>>Proceso</option>
                   <option value="Terminado"<?php if ($reg['MAN_estado'] === 'Terminado') echo ' selected'; ?>>Terminado</option>
                   </select>
               </div> 
               <div class="form-group  col-lg-2 col-md-2 col-sm-2 col-xs-12">
                 <label>Comentario:</label>
                 <textarea type="text" class="form-control desarrollo" name="MAN_comentario" id="MAN_comentario" cols="40" rows="2" style="resize: none;"><?php echo $reg['MAN_comentario'];?></textarea>
               </div>    
                       <div class='col-md-12' id="result"></div><!-- Carga los datos ajax -->
                   </div>
                   <div class="panel-footer text-center col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button type="submit" class="btn btn-sm btn-success"  id="btnGuardar"><i class="fa fa-save"></i> Actualizar datos</button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="cancelarform()"><i class="fa fa-arrow-circle-left"></i> &nbsp;&nbsp; Regresar</button>
                          </div>
                 </form>
                </div>
                <!--Fin centro -->
              </div><!-- /.box -->
          </div><!-- /.col -->
      </div><!-- /.row -->
  </section><!-- /.content -->
</div>
<?php
}else{
require "noacceso.php";
}
require_once('footer.php');
?>
<link rel="stylesheet" type="text/css" href="../public/css/proceso_des.css">
<link rel="stylesheet" type="text/css" href="../public/css/ciclo.css">
<script type="text/javascript" src="scripts/proceso_desarrollo.js"></script>
<?php 
}
ob_end_flush();
?>