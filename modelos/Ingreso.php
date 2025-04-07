<?php

date_default_timezone_set('America/Lima'); 
require "../config/Conexion.php";

Class Ingreso
{
  //Implementando nuestro constructor
  public function __construct()
  {
 
 
  }
  //Implementamos metodo para insertar registro
    public function insertar($idproveedor,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$impuesto,$total_compra,$idarticulo,$cantidad,$precio_compra,$precio_venta,$credito,$cuota,$valorcuota,$tipocredito,$fechainicio,$diapago,$fechasElegidas,$serieIngreso,$codigod)
    {

      if($credito =="si"){
        $estado = "Pendiente";
      }else{
        $estado = "Aceptado";
        $cuota =0;
        $valorcuota = 0.00;

      }

      $sql="INSERT INTO ingreso (idproveedor,idusuario,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,impuesto,num_cuotas,valor_cuota,total_compra,estado)
      VALUES ('$idproveedor','$idusuario','$tipo_comprobante','$serie_comprobante','$num_comprobante','$fecha_hora','$impuesto','$cuota','$valorcuota','$total_compra','$estado')";
      //return ejecutarConsulta($sql);
      $idingresonew=ejecutarConsulta_retornarID($sql);
      $num_elementos=0;
      $sw=true;

      while($num_elementos < count($idarticulo) )
      {
        $sql_detalle="INSERT INTO detalle_ingreso(idingreso,idarticulo,cantidad,precio_compra,precio_venta,serie,codigo) VALUES('$idingresonew','$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_compra[$num_elementos]','$precio_venta[$num_elementos]','$serieIngreso[$num_elementos]','$codigod[$num_elementos]')";
        ejecutarConsulta($sql_detalle) or $sw=false;
        $num_elementos=$num_elementos+1;
      }

            // list($anno,$mes,$dia) = explode('-',$fechainicio);

      if($credito == "si"){

        if($tipocredito =="Diario"){
            $fechasElegidas="";

            list($anno,$mes,$dia) = explode('-',$fechainicio);

            if($anno % 2 == 0 && ($anno % 100 !=0 or $anno % 400 == 0)){
              $dias_febrero = 29;
            }else{
              $dias_febrero = 28;
            }

            // switch ($mes) {
            //   case '01': $diasDelMes = 31; break;
            //   case '02': $diasDelMes = $dias_febrero; break;
            //   case '03': $diasDelMes = 31; break;
            //   case '04': $diasDelMes = 30; break;
            //   case '05': $diasDelMes = 31; break;
            //   case '06': $diasDelMes = 30; break;
            //   case '07': $diasDelMes = 31; break;
            //   case '08': $diasDelMes = 31; break;
            //   case '09': $diasDelMes = 30; break;
            //   case '10': $diasDelMes = 31; break;
            //   case '11': $diasDelMes = 30; break;
            //   case '12': $diasDelMes = 31; break;
            //   }

            if($mes =="01" || $mes =="03" ||$mes =="05" ||$mes =="07" ||$mes =="08" ||$mes =="10" || $mes=="12" ){
              $diasDelMes = 31;
            }else if($mes =="04" || $mes =="06" ||$mes =="09" ||$mes =="11"){
              $diasDelMes = 30;
            }else if($mes == "02"){
              $diasDelMes = $dias_febrero;
            } 
          $nn = 0;
          while($nn < $cuota ){
            // if($dia <= $diasDelMes){

              $fecha = $anno."-".$mes."-".$dia; 
              $sqlDiario = "INSERT INTO pago(idingreso,valor_cuota,fecha_pago,estado) VALUES('$idingresonew','$valorcuota','$fecha','Pendiente')";
              ejecutarConsulta($sqlDiario) or $sw=false;
              $dia++;
              

              if($dia > $diasDelMes){
                if($mes == 12){
                  $mes ="01";
                  $anno = $anno+1;
                  if($anno % 2 == 0 && ($anno % 100 !=0 or $anno % 400 == 0)){
                    $dias_febrero = 29;
                  }else{
                    $dias_febrero = 28;
                  }

                }else{
                  $mesN = $mes +1;
                  $mes = str_pad((string)$mesN,2,"0",STR_PAD_LEFT);
                }

                // switch ($mes) {
                // case '01': $diasDelMes = 31; break;
                // case '02': $diasDelMes = $dias_febrero; break;
                // case '03': $diasDelMes = 31; break;
                // case '04': $diasDelMes = 30; break;
                // case '05': $diasDelMes = 31; break;
                // case '06': $diasDelMes = 30; break;
                // case '07': $diasDelMes = 31; break;
                // case '08': $diasDelMes = 31; break;
                // case '09': $diasDelMes = 30; break;
                // case '10': $diasDelMes = 31; break;
                // case '11': $diasDelMes = 30; break;
                // case '12': $diasDelMes = 31; break;
                // }

                if($mes =="01" || $mes =="03" ||$mes =="05" ||$mes =="07" ||$mes =="08" ||$mes =="10" || $mes=="12" ){
                  $diasDelMes = 31;
                }else if($mes =="04" || $mes =="06" ||$mes =="09" ||$mes =="11"){
                  $diasDelMes = 30;
                }else if($mes == "02"){
                  $diasDelMes = $dias_febrero;
                } 

                $dia = "01";

              }

            // }

            $nn=$nn+1;

           }


        }
        else if($tipocredito =="Mensual"){


            $fechasElegidas="";
            
              $fechainicio =date('Y-m-d');
              list($anno,$mes,$dia) = explode('-',$fechainicio);

              if($anno % 2 == 0 && ($anno % 100 !=0 or $anno % 400 == 0)){
                $dias_febrero = 29;
              }else{
                $dias_febrero = 28;
              }

              switch ($mes) {
                case '01': $diasDelMes = 31; break;
                case '02': $diasDelMes = $dias_febrero; break;
                case '03': $diasDelMes = 31; break;
                case '04': $diasDelMes = 30; break;
                case '05': $diasDelMes = 31; break;
                case '06': $diasDelMes = 30; break;
                case '07': $diasDelMes = 31; break;
                case '08': $diasDelMes = 31; break;
                case '09': $diasDelMes = 30; break;
                case '10': $diasDelMes = 31; break;
                case '11': $diasDelMes = 30; break;
                case '12': $diasDelMes = 31; break;
                }
            $nn = 0;
            while($nn < $cuota ){

              $nn++;

              // dia: 1
              // diapago: 31
              // mes: 11
              //      
              if($diapago <= $diasDelMes){
                if($dia < $diapago){
                  $fecha = $anno."-".$mes."-".$diapago; 
                  $sqlMensual = "INSERT INTO pago(idingreso,valor_cuota,fecha_pago,estado) VALUES('$idingresonew','$valorcuota','$fecha','Pendiente')";

                  $mes++;
                  if($mes > 12){
                    $mes = 1;
                    $anno++;

                      if($anno % 2 == 0 && ($anno % 100 !=0 or $anno % 400 == 0)){
                        $dias_febrero = 29;
                      }else{
                        $dias_febrero = 28;
                      }
                  }

                  $mes = str_pad((string)$mes,2,"0",STR_PAD_LEFT);
                    switch ($mes) {
                      case '01': $diasDelMes = 31; break;
                      case '02': $diasDelMes = $dias_febrero; break;
                      case '03': $diasDelMes = 31; break;
                      case '04': $diasDelMes = 30; break;
                      case '05': $diasDelMes = 31; break;
                      case '06': $diasDelMes = 30; break;
                      case '07': $diasDelMes = 31; break;
                      case '08': $diasDelMes = 31; break;
                      case '09': $diasDelMes = 30; break;
                      case '10': $diasDelMes = 31; break;
                      case '11': $diasDelMes = 30; break;
                      case '12': $diasDelMes = 31; break;
                    }
                  $dia = 1;
                }else{
                  $mes++;
                  $mes = str_pad((string)$mes,2,"0",STR_PAD_LEFT);

                  $fecha = $anno."-".$mes."-".$diapago; 
                  $sqlMensual = "INSERT INTO pago(idingreso,valor_cuota,fecha_pago,estado) VALUES('$idingresonew','$valorcuota','$fecha','Pendiente')";
                  $mes++;
                  if($mes >12){
                    $mes = 1;
                    $anno++;
                    if($anno % 2 == 0 && ($anno % 100 !=0 or $anno % 400 == 0)){
                        $dias_febrero = 29;
                      }else{
                        $dias_febrero = 28;
                      }
                  }
                  $mes = str_pad((string)$mes,2,"0",STR_PAD_LEFT);
                    switch ($mes) {
                      case '01': $diasDelMes = 31; break;
                      case '02': $diasDelMes = $dias_febrero; break;
                      case '03': $diasDelMes = 31; break;
                      case '04': $diasDelMes = 30; break;
                      case '05': $diasDelMes = 31; break;
                      case '06': $diasDelMes = 30; break;
                      case '07': $diasDelMes = 31; break;
                      case '08': $diasDelMes = 31; break;
                      case '09': $diasDelMes = 30; break;
                      case '10': $diasDelMes = 31; break;
                      case '11': $diasDelMes = 30; break;
                      case '12': $diasDelMes = 31; break;
                    }
                  $dia = 1;
                }
              }else{



                  if($dia == 1){
                    $fecha = $anno."-".$mes."-".$diasDelMes; 
                    $sqlMensual = "INSERT INTO pago(idingreso,valor_cuota,fecha_pago,estado) VALUES('$idingresonew','$valorcuota','$fecha','Pendiente')";

                    $mes++;
                    $mes = str_pad((string)$mes,2,"0",STR_PAD_LEFT);
                     switch ($mes) {
                      case '01': $diasDelMes = 31; break;
                      case '02': $diasDelMes = $dias_febrero; break;
                      case '03': $diasDelMes = 31; break;
                      case '04': $diasDelMes = 30; break;
                      case '05': $diasDelMes = 31; break;
                      case '06': $diasDelMes = 30; break;
                      case '07': $diasDelMes = 31; break;
                      case '08': $diasDelMes = 31; break;
                      case '09': $diasDelMes = 30; break;
                      case '10': $diasDelMes = 31; break;
                      case '11': $diasDelMes = 30; break;
                      case '12': $diasDelMes = 31; break;
                      }


                  }else{

                    $mes++;
                    $mes = str_pad((string)$mes,2,"0",STR_PAD_LEFT);
                    switch ($mes) {
                      case '01': $diasDelMes = 31; break;
                      case '02': $diasDelMes = $dias_febrero; break;
                      case '03': $diasDelMes = 31; break;
                      case '04': $diasDelMes = 30; break;
                      case '05': $diasDelMes = 31; break;
                      case '06': $diasDelMes = 30; break;
                      case '07': $diasDelMes = 31; break;
                      case '08': $diasDelMes = 31; break;
                      case '09': $diasDelMes = 30; break;
                      case '10': $diasDelMes = 31; break;
                      case '11': $diasDelMes = 30; break;
                      case '12': $diasDelMes = 31; break;
                      }


                      $fecha = $anno."-".$mes."-".$diapago; 
                    $sqlMensual = "INSERT INTO pago(idingreso,valor_cuota,fecha_pago,estado) VALUES('$idingresonew','$valorcuota','$fecha','Pendiente')";

                    $mes++;
                    if($mes >12){
                      $mes = 1;
                      $anno++;

                      if($anno % 2 == 0 && ($anno % 100 !=0 or $anno % 400 == 0)){
                        $dias_febrero = 29;
                      }else{
                        $dias_febrero = 28;
                      }

                    }
                    $mes = str_pad((string)$mes,2,"0",STR_PAD_LEFT);
                     switch ($mes) {
                      case '01': $diasDelMes = 31; break;
                      case '02': $diasDelMes = $dias_febrero; break;
                      case '03': $diasDelMes = 31; break;
                      case '04': $diasDelMes = 30; break;
                      case '05': $diasDelMes = 31; break;
                      case '06': $diasDelMes = 30; break;
                      case '07': $diasDelMes = 31; break;
                      case '08': $diasDelMes = 31; break;
                      case '09': $diasDelMes = 30; break;
                      case '10': $diasDelMes = 31; break;
                      case '11': $diasDelMes = 30; break;
                      case '12': $diasDelMes = 31; break;
                      }
                  $dia = 1;

                  }

              }
            ejecutarConsulta($sqlMensual) or $sw=false;


              }

          }else if($tipocredito =="Seleccionar"){
              $nn = 0;

              while($nn < count($fechasElegidas)){
                $sqlElegidas = "INSERT INTO pago(idingreso,valor_cuota,fecha_pago,estado) VALUES('$idingresonew','$valorcuota','$fechasElegidas[$nn]','Pendiente')";
                ejecutarConsulta($sqlElegidas) or $sw=false;
                $nn++;
              }

          }


      }


      return $sw;
      // return $dia."-".$mes."-".$anno;
      // return $credito."-".$cuota."-".$valorcuota."-".$tipocredito."-".$fechainicio."-".$diapago;
   }


  

    public function insertarListadoCuotas($id_ingreso,$valor_cuota,$fecha_cuota){
       $sql="INSERT INTO pago(idingreso,valor_cuota,fecha_pago,estado) VALUES('$id_ingreso','$valor_cuota','$fecha_cuota','Pendiente')";
       return ejecutarConsulta($sql);
    }
    public function editarListadoCuotas($idpago,$id_ingreso,$valor_cuota,$fecha_cuota){
       $sql="UPDATE pago SET idingreso = '$id_ingreso', valor_cuota = '$valor_cuota', fecha_pago = '$fecha_cuota' where idpago = '$idpago'";
       return ejecutarConsulta($sql);
    }
    

    //Implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idingreso)
    {
      $sql="SELECT i.idingreso,DATE(i.fecha_hora) as fecha, i.idproveedor,p.nombre as proveedor, u.idusuario, u.nombre as usuario, i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i inner join persona p on i.idproveedor=p.idpersona inner join usuario u on i.idusuario=u.idusuario where i.idingreso='$idingreso'";
      return ejecutarConsultaSimpleFila($sql);

    }

    

    public function listarDetalle($idingreso)
    {
      $sql="SELECT di.idingreso, di.idarticulo,a.nombre,di.cantidad,di.precio_compra,di.precio_venta,di.serie,di.codigo FROM detalle_ingreso di inner join articulo a on di.idarticulo=a.idarticulo where di.idingreso='$idingreso'";
      return ejecutarConsulta($sql);

    }


    //Implementar metodo para listar los registros
    public function listar()
    {
      $sql="SELECT i.idingreso,DATE(i.fecha_hora) as fecha, i.idproveedor,p.nombre as proveedor, u.idusuario, u.nombre as usuario, i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.num_cuotas,i.valor_cuota,i.total_compra,i.impuesto,i.estado FROM ingreso i inner join persona p on i.idproveedor=p.idpersona inner join usuario u on i.idusuario=u.idusuario where i.estado = 'Aceptado' or i.estado = 'Pendiente' order by i.idingreso desc";
      return ejecutarConsulta($sql);

    }
    public function listarKardex()
    {
      $sql = "SELECT DATE(i.fecha_hora) as fecha, p.nombre as proveedor, u.nombre as usuario, i.tipo_comprobante, i.serie_comprobante, i.num_comprobante, i.total_compra, i.estado FROM ingreso i INNER JOIN persona p ON i.idproveedor = p.idpersona INNER JOIN usuario u ON i.idusuario = u.idusuario WHERE i.estado IN ('Aceptado', 'Pendiente') ORDER BY i.idingreso DESC";
      return ejecutarConsulta($sql);
    }

    public function listarCuota($idingreso){
      $sql = "SELECT p.idpago,p.idingreso,p.valor_cuota,DATE(p.fecha_pago) as fecha_pago,p.estado,i.serie_comprobante,i.num_comprobante FROM pago p INNER JOIN ingreso i on i.idingreso=p.idingreso where i.idingreso = '$idingreso'" ;
      return ejecutarConsulta($sql);
    }

    public function mostrarSaldoAFavor($id_ingreso){
      $sql = "SELECT p.idpago,p.idingreso,p.valor_cuota,DATE(p.fecha_pago) as fecha_pago,p.estado,i.total_compra FROM pago p INNER JOIN ingreso i on i.idingreso=p.idingreso where i.idingreso = '$id_ingreso'" ;
      return ejecutarConsulta($sql);
    }

    public function mostrarListaCuota($idpago){
       $sql="SELECT p.idpago,p.idingreso,p.valor_cuota,DATE(p.fecha_pago) as fecha_pagoLista FROM pago p where p.idpago='$idpago'";
       return ejecutarConsultaSimpleFila($sql);
    }

    // public function mostrarIdIngreso($idingreso){
    //    $sql="SELECT p.idpago,p.idingreso,p.valor_cuota,DATE(p.fecha_pago) as fecha_pagoLista FROM pago p where p.idpago='$idpago'";
    //    return ejecutarConsultaSimpleFila($sql);
    // }
    

    public function cancelarLetra($idpago){
      $sql = "UPDATE pago SET estado = 'Cancelado' WHERE idpago = '$idpago'";
      return ejecutarConsulta($sql);
    }


      //Implementamos un metodo para eliminar registro
    public function anular($idingreso){
      $sql="UPDATE ingreso SET estado='Anulado' where idingreso='$idingreso'";
      return ejecutarConsulta($sql);
    }

    public function virificarPago($idingreso){
       $sql="SELECT count(idpago) as cantidadRegistros FROM pago where idingreso='$idingreso' and estado='Pendiente'";
        return ejecutarConsulta($sql);
    }

    public function anularCompraCredito($idingreso){
      $sql="UPDATE ingreso SET estado='Cancelado' where idingreso='$idingreso'";
      return ejecutarConsulta($sql);
    }


  }



