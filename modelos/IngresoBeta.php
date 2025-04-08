<?php
//Incluimos conexion a la base de trader_cdlrisefall3methods
require "../config/Conexion.php";

Class Ingreso
{
  //Implementando nuestro constructor
  public function __construct()
  {


  }
  //Implementamos metodo para insertar registro
    public function insertar($idproveedor,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$impuesto,$total_compra,$idarticulo,$cantidad,$precio_compra,$precio_venta,$credito,$cuota,$valorcuota,$tipocredito,$fechainicio,$diapago)
    {
      // $sql="INSERT INTO ingreso (idproveedor,idusuario,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,impuesto,total_compra,estado)
      // VALUES ('$idproveedor','$idusuario','$tipo_comprobante','$serie_comprobante','$num_comprobante','$fecha_hora','$impuesto','$total_compra','Aceptado')";
      // //return ejecutarConsulta($sql);
      // $idingresonew=ejecutarConsulta_retornarID($sql);
      // $num_elementos=0;
      // $sw=true;

      // while($num_elementos < count($idarticulo) )
      // {
      //   $sql_detalle="INSERT INTO detalle_ingreso(idingreso,idarticulo,cantidad,precio_compra,precio_venta) VALUES('$idingresonew','$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_compra[$num_elementos]','$precio_venta[$num_elementos]')";
      //   ejecutarConsulta($sql_detalle) or $sw=false;
      //   $num_elementos=$num_elementos+1;
      // }


      if($credito == "si"){

        if($tipocredito =="Diario"){

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
            // dia: 28 
            // mes: febrero 02

            if($dia <= $diasDelMes){
              $fecha = $anno."-".$mes."-".$dia; 
              $sql = "INSERT INTO pago(idingreso,valor_cuota,fecha_pago,estado) VALUES('$idingresonew','$valorcuota','$fecha','Pendiente')";
              $dia++;

            }else if($mes<= 12 ){
              if($mes == 12){
                $mes ="01";
                $anno = $anno+1;
              }else{
                $mesN = $mes +1;
                $mes = str_pad(string($mesN),2,"0",STR_PAD_LEFT);
              }
 
              $dia = "01";

              $fecha = $anno."-".$mes."-".$dia; 
              $sql = "INSERT INTO pago(idingreso,valor_cuota,fecha_pago,estado) VALUES('$idingresonew','$valorcuota','$fecha','Pendiente')";
              $dia++;


            }

           }
           ejecutarConsulta($sql) or $sw=false;


        }else if($tipocredito =="Mensual"){

            $nn = 0;
            while($nn < $cuota ){
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

              $nn++;

              // 30 - 30
              if($dia < $diapago){
                if($diapago <= $diasDelMes){
                  $fecha = $anno."-".$mes."-".$diapago; 
                  $sql = "INSERT INTO pago(idingreso,valor_cuota,fecha_pago,estado) VALUES('$idingresonew','$valorcuota','$fecha','Pendiente')";

                  $mes++;
                  $mes = str_pad(string($mes),2,"0",STR_PAD_LEFT);
                }else{
                  $fecha = $anno."-".$mes."-".$diasDelMes; 
                  $sql = "INSERT INTO pago(idingreso,valor_cuota,fecha_pago,estado) VALUES('$idingresonew','$valorcuota','$fecha','Pendiente')";
                  $mes++;
                  $mes = str_pad(string($mes),2,"0",STR_PAD_LEFT);
                }
              }else{
                  $mes++;
                  $mes = str_pad(string($mes),2,"0",STR_PAD_LEFT);
                if($dia <= $diasDelMes){

                  $fecha = $anno."-".$mes."-".$dia; 
                  $sql = "INSERT INTO pago(idingreso,valor_cuota,fecha_pago,estado) VALUES('$idingresonew','$valorcuota','$fecha','Pendiente')";

                  $mes++;
                  $mes = str_pad(string($mes),2,"0",STR_PAD_LEFT);

                }else{
                  $fecha = $anno."-".$mes."-".$diasDelMes; 
                  $sql = "INSERT INTO pago(idingreso,valor_cuota,fecha_pago,estado) VALUES('$idingresonew','$valorcuota','$fecha','Pendiente')";

                  $mes++;
                  $mes = str_pad(string($mes),2,"0",STR_PAD_LEFT);
                }

              }
            ejecutarConsulta($sql) or $sw=false;


              // if($diapago<=$diasDelMes){
              //   if($dia <= $diapago){
              //     $fecha = $anno."-".$mes."-".$diapago; 
              //     $sql = "INSERT INTO pago(idingreso,valor_cuota,fecha_pago,estado) VALUES('$idingresonew','$valorcuota','$fecha','Pendiente')";

              //     $mes++;
              //     $mes = str_pad(string($mes),2,"0",STR_PAD_LEFT);
              //   }else{
              //     $mes++;
              //     $mes = str_pad(string($mes),2,"0",STR_PAD_LEFT);

              //     $fecha = $anno."-".$mes."-".$diapago; 
              //     $sql = "INSERT INTO pago(idingreso,valor_cuota,fecha_pago,estado) VALUES('$idingresonew','$valorcuota','$fecha','Pendiente')";

              //     $mes++;
              //     $mes = str_pad(string($mes),2,"0",STR_PAD_LEFT);
              //   }


              // }else{
              //     $dia = $diasDelMes;
              //     $fecha = $anno."-".$mes."-".$dia; 
              //     $sql = "INSERT INTO pago(idingreso,valor_cuota,fecha_pago,estado) VALUES('$idingresonew','$valorcuota','$fecha','Pendiente')";

              //     $mes++;
              //     $mes = str_pad(string($mes),2,"0",STR_PAD_LEFT);
              // }




              // if($dia <= $diapago){
              //   $fecha = $anno."-".$mes."-".$dia; 
              //   $sql = "INSERT INTO pago(idingreso,valor_cuota,fecha_pago,estado) VALUES('$idingresonew','$valorcuota','$fecha','Pendiente')";

              //   $mes++;
              //   $mes = str_pad(string($mes),2,"0",STR_PAD_LEFT);

              // }else if($mes<= 12 ){
              //   if($mes == 12){
              //     $mes ="01";
              //     $anno = $anno+1;
              //   }else{
              //     $mesN = $mes +1;
              //     $mes = str_pad(string($mesN),2,"0",STR_PAD_LEFT);
              //   }

              //   $dia = "01";

              //   $fecha = $anno."-".$mes."-".$dia; 
              //   $sql = "INSERT INTO pago(idingreso,valor_cuota,fecha_pago,estado) VALUES('$idingresonew','$valorcuota','$fecha','Pendiente')";
              //   $dia++;


              }

          }

      }


      // return $sw;
      return $fecha;
   }


    //Implementamos un metodo para eliminar registro
    public function anular($idingreso)
    {
      $sql="UPDATE ingreso SET estado='Anulado'
      where idingreso='$idingreso'";
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
      $sql="SELECT di.idingreso, di.idarticulo,a.nombre,di.cantidad,di.precio_compra,di.precio_venta FROM detalle_ingreso di inner join articulo a on di.idarticulo=a.idarticulo where di.idingreso='$idingreso'";
      return ejecutarConsulta($sql);

    }


    //Implementar metodo para listar los registros
    public function listar()
    {
      $sql="SELECT i.idingreso,DATE(i.fecha_hora) as fecha, i.idproveedor,p.nombre as proveedor, u.idusuario, u.nombre as usuario, i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i inner join persona p on i.idproveedor=p.idpersona inner join usuario u on i.idusuario=u.idusuario order by i.idingreso desc";
      return ejecutarConsulta($sql);

    }





  }







 ?>
