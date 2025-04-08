<?php

date_default_timezone_set('America/Lima');
require_once('../config/Conexion.php');


class Venta2
{

  public function __construct()
  {
  }

  public function insertar($idcliente, $idusuario, $idsoporte, $codigotipo_comprobante, $codigotipo_pago, $fecha_hora, $fecha_ven, $impuesto, $op_gravadas, $op_inafectas, $op_exoneradas, $op_gratuitas, $isc, $total_descuentos, $total_igv, $total_venta, $leyenda, $idmoneda, $idarticulo, $cantidad, $precio_venta, $descuento, $serieArticulo, $igv_asig)
  {
    $saber = "SELECT serie,correlativo FROM venta WHERE codigotipo_comprobante='$codigotipo_comprobante'";
    $saberExiste = ejecutarConsultaSimpleFila($saber);
    if ($saberExiste["serie"] == null and $saberExiste["correlativo"] == null) {
      if ($codigotipo_comprobante == 1) {
        $serie = 'F001';
      } else if ($codigotipo_comprobante == 22) {
        $serie = 'HS001';
      } else {
        $serie = 'B001';
      }
      $correlativo = '00000001';
    } else {
      $sqlmaxserie = "SELECT max(serie) as maxSerie FROM venta WHERE codigotipo_comprobante='$codigotipo_comprobante' ";
      $maxserie = ejecutarConsultaSimpleFila($sqlmaxserie);
      $serie = $maxserie["maxSerie"];
      $ultimoCorrelativo = "SELECT max(correlativo) as ultimocorrelativo,serie,correlativo FROM venta WHERE codigotipo_comprobante='$codigotipo_comprobante'  and serie='$serie'";
      $ultimo = ejecutarConsultaSimpleFila($ultimoCorrelativo);
      if ($ultimo["ultimocorrelativo"] == '99999999') {
        $ser = substr($serie, 1) + 1;
        $seri = str_pad((string)$ser, 3, "0", STR_PAD_LEFT);
        if ($codigotipo_comprobante == 1) {
          $serie = "F" . $seri;
        } else if ($codigotipo_comprobante == 22) {
          $serie = 'HS' . $seri;
        } else {
          $serie = "B" . $seri;
        }
        $correlativo = '00000001';
      } else {
        $corre = $ultimo["ultimocorrelativo"] + 1;
        $correlativo = str_pad($corre, 8, "0", STR_PAD_LEFT);
      }
    }

    $fecha_todo = date('Y-m-d H:i:s');
    $hora = date("H:i:s");

    $sql = "INSERT INTO venta (idcliente,idusuario,idsoporte,codigotipo_comprobante,serie,correlativo,fecha_hora,hora,fecha_ven,impuesto,op_gravadas,op_inafectas,op_exoneradas,op_gratuitas,isc,total_descuentos,total_igv,total_venta,leyenda,estado,idmoneda,idmotivo_doc,codigotipo_pago,igv_asig) VALUES ('$idcliente','$idusuario','$idsoporte','$codigotipo_comprobante','$serie','$correlativo','$fecha_hora','$hora','$fecha_ven','1','$op_gravadas','$op_inafectas','$op_exoneradas','$op_gratuitas','$isc','$total_descuentos','$total_igv','$total_venta','$leyenda','Aceptado','$idmoneda',null, '$codigotipo_pago','$igv_asig')";
    // return ejecutarConsulta($sql);
    $idventanew = ejecutarConsulta_retornarID($sql);

    $num_elementos = 0;
    $sw = true;

    while ($num_elementos < count($idarticulo)) {
      $item = $num_elementos + 1;

      $sql_detalle = "INSERT INTO detalle_venta(idventa, idarticulo,cantidad,precio_venta,descuento,fecha_mas_vendido,item,serie) VALUES ('$idventanew', '$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento[$num_elementos]','$fecha_todo','$item','$serieArticulo[$num_elementos]')";
      ejecutarConsulta($sql_detalle) or $sw = false;
      $num_elementos = $num_elementos + 1;
    }
    /*=============================================
    =            EMPIEZA TXT           =
    =============================================*/
    if ($sw) {
      /*=============================================
      CONSULTA VENTA
      =============================================*/

      $sqlCabeceraPrincipal = "SELECT v.idventa,DATE(v.fecha_hora) as fecha, DATE_FORMAT(v.fecha_hora,\"%H:%I:%S\" ) as hora ,v.idcliente,p.nombre as clienteRazonSocial,p.direccion,p.tipo_documento,p.num_documento,v.idusuario,u.nombre
       as usuario,v.codigotipo_comprobante,v.serie,v.correlativo,date(v.fecha_hora) as fecha,v.fecha_ven,v.impuesto,v.op_gravadas,v.op_inafectas,v.op_exoneradas,v.op_gratuitas,v.isc,v.total_descuentos,v.total_igv,v.total_venta,v.leyenda,v.idmoneda,m.codigo
       as codigoMoneda,v.codigotipo_pago, t.descripcion_tipo_pago FROM venta v 
       INNER JOIN persona p ON v.idcliente=p.idpersona 
       INNER JOIN usuario u ON v.idusuario=u.idusuario 
       INNER JOIN tipo_pago t ON t.codigotipo_pago=v.codigotipo_pago
       INNER JOIN moneda m ON m.idmoneda=v.idmoneda WHERE v.idventa='$idventanew'";
      $rsptaCP = ejecutarConsultaSimpleFila($sqlCabeceraPrincipal);
      if ($rsptaCP["tipo_documento"] == "RUC") {
        $cliente_tipo_documento = "6";
      } else {
        $cliente_tipo_documento = "1";
      }
      // var_dump($rsptaCP["fecha"]);


      /*=============================================
      CONSULTA DETALLE VENTA
      =============================================*/
      $sqlLinea = "SELECT dv.idventa,dv.idarticulo,a.nombre,a.unidad_medida,a.codigo,a.afectacion,dv.cantidad,dv.precio_venta,dv.descuento,dv.item,dv.serie 
      FROM detalle_venta dv inner join articulo a on dv.idarticulo=a.idarticulo where dv.idventa='$idventanew'";
      $rsptaDetalleVenta = ejecutarConsulta($sqlLinea);

      /*=============================================
      =            CONVERTIR LINEA EN JSON          =
      =============================================*/
      $base_imponible = 0;
      $tributo_monto_item = 0;

      $sqlIGV = "SELECT * FROM igv";
      $rsptaIGV = ejecutarConsultaSimpleFila($sqlIGV);
      $igv_db = $rsptaIGV["porcentaje"];
      while ($rpta_linea = $rsptaDetalleVenta->fetch_object()) {
        $rpta_linea->serie = str_replace('"', '\"', $rpta_linea->serie);
        // $valor_venta ="dd";
        if ($rpta_linea->afectacion = "Gravado") {
          $valor_unitario = round(($rpta_linea->precio_venta / (($igv_db / 100) + 1)), 5);
          $base_impo = round($valor_unitario * $rpta_linea->cantidad, 2);
          $valor_venta = round($valor_unitario * $rpta_linea->cantidad, 2);
          $impuestos = round($valor_unitario * ($igv_db / 100) * $rpta_linea->cantidad, 2);

          $codTipoTributo = 1000;
          $afectacion = 10;
          $nomTributo = "IGV";
          $tipoTributo = "VAT";
        } else {
          $valor_unitario = round($rpta_linea->precio_venta, 2);
          $base_impo = $valor_unitario * $rpta_linea->cantidad;
          $valor_venta = ($rpta_linea->precio_venta * $rpta_linea->cantidad);
          $impuestos = 0.00;
          $codTipoTributo = 9997;
          $afectacion = 20;
          $nomTributo = "EXO";
          $tipoTributo = "VAT";
        }

        $base_imponible += $base_impo;
        $tributo_monto_item += $impuestos;
        $igv_db_1 = number_format($igv_db, 1, ".", ",");

        $data_json = '{
                "linea": [
                      {
                      "Código_de_unidad_de_medida_por_ítem":"' . $rpta_linea->unidad_medida . '",
                       "Cantidad_de_unidades_por_ítem":"' . $rpta_linea->cantidad . '", 
                       "Código_de_producto":"' . $rpta_linea->codigo . '",
                       "Codigo_producto_SUNAT":"",
                       "Descripción_detallada_del_servicio_bien_caract":"' . $rpta_linea->nombre . ' ' . $rpta_linea->serie . '",
                       "Valor_Unitario":"' . $valor_unitario . '",
                       "Sumatoria_Tributos_por_item":"' . $impuestos . '",
                       "Códigos_de_tipos_de_tributos_IGV":"' . $codTipoTributo . '",
                       "Monto_de_IGV_por_ítem":"' . $impuestos . '",
                       "Base_imponible_igv_item":"' . $base_impo . '",
                       "Nombre_de_tributo_por_item":"' . $nomTributo . '",
                       "Código_de_tipo_de_tributo_por_Item":"' . $tipoTributo . '",
                       "Afectación_al_IGV_por_ítem":"' . $afectacion . '",
                       "Porcentaje_de_IGV":"' . $igv_db_1 . '",
                       "Códigos_de_tipos_de_tributos_ISC":"-",
                       "Monto_de_ISC_por_ítem":"",
                       "Base_imponibleISC_item":"",
                       "Nombre_de_tributo_por_item_isc":"",
                       "Código_de_tipo_de_tributo_por_Item_isc":"",
                       "Tipo_de_sistema_ISC":"",
                       "Porcentaje_de_ISC":"",
                       "Códigos_de_tipos_de_tributos_OTRO":"-",
                       "Monto_de_tributo_OTRO_por_iItem":"",
                       "Base_Imponible_de_tributo_OTRO_por_Item":"",
                       "Nombre_de_tributo_OTRO_por_item":"",
                       "Código_de_tipo_de_tributo_OTRO_por_Item":"",
                       "Porcentaje_de_tributo_OTRO_por_Item":"",


                       "codTriIcbper":"-",
                       "mtoTriIcbperItem":"",
                       "ctdBolsasTriIcbperItem":"",
                       "nomTributoIcbperItem":"",
                       "codTipTributoIcbperItem":"",
                       "mtoTriIcbperUnidad":"",
                       
                       "Precio_de_venta_unitario":"' . $rpta_linea->precio_venta . '",
                       "Valor_de_venta_por_Item":"' . $valor_venta . '",
                       "Valor_REFERENCIAL_unitario_gratuitos":"0.00"
                       
                      
                        
                      }
                  ]
             }';
        $assocArray = json_decode($data_json, true);

        foreach ($assocArray["linea"] as $key) {

          $valu = array();

          foreach ($key as $key2 => $value2) {
            $valu[] = $value2;
          }
          //$detalle[]=implode("|",$valu);       
          $valu = str_replace('&quot;', '"', $valu);
          $detalle[] = implode('|', $valu);
        }
        $deta = implode(PHP_EOL, $detalle);


        // $assocArray = json_decode($data_json, true);
        // foreach ($assocArray["tributo"] as $key ) {
        //     $evaluar=array();
        //     foreach ($key as $key2 => $value2) {
        //        $evaluar[]=$value2;
        //     }
        //        $detalle_evalu[]=implode("|",$evaluar);
        //  }
        //        $detalle_evalua=implode(PHP_EOL,$detalle_evalu);

      }



      $infoLinea = $deta;
      // $infoLinea_tributo = $detalle_evalua;





      /*=============================================
      CONSULTA PERFIL
      =============================================*/
      $sqlPerfilEmisor = "SELECT * FROM perfil WHERE idperfil='1'";
      $rsptaEmisor = ejecutarConsultaSimpleFila($sqlPerfilEmisor);


      $cabecera_princ_json = '{
        "cabecera_principal":[
          {
            "tipo_de_operacion":"0101",
            "fecha_emision":"' . $rsptaCP["fecha"] . '",
            "hora_emision":"' . $rsptaCP["hora"] . '",
            "fecha_vencimiento":"-",
            "codigo_domicilio_fiscal":"0000",
            "tipo_documento_identidad_adquiriente_usuario":"' . $cliente_tipo_documento . '",
            "numero_identidad_adquiriente_usuario":"' . $rsptaCP["num_documento"] . '",
            "apellidos_nombres_denominacion_razon_social_entidad_adquiriente_usuario":"' . $rsptaCP["clienteRazonSocial"] . '",
            "tipo_moneda_factura_e":"' . $rsptaCP["codigoMoneda"] . '",
            "Sumatoria_Tributos":"' . $rsptaCP["total_igv"] . '",
            "Total_valor_de_venta":"' . $rsptaCP["op_gravadas"] . '",
            "Total_Precio_de_Venta":"' . $rsptaCP["total_venta"] . '",
            "Total_descuentos":"' . $rsptaCP["total_descuentos"] . '",
            "Sumatoria_otros_Cargos":"0.00",
            "Total_Anticipos":"0.00",
            "Importe_total_de_la_venta_cesión_en_uso_o_del_servicio_prestado":"' . $rsptaCP["total_venta"] . '",
            "Versión_UBL":"2.1",
            "Customization_Documento":"2.0"


          }
        ],
        "leyenda": [
          {
             "Código_de_leyenda": "1000",
             "Descripción_de_leyenda": "' . $rsptaCP["leyenda"] . '"
          }
        ],
        "tributo":[
            {
              "Identificador_de_tributo":"1000",
              "Nombre_de_tributo":"IGV",
              "Código_de_tipo_de_tributo":"VAT",
              "Base_imponible":"' . $rsptaCP["op_gravadas"] . '",
              "Monto_de_Tirbuto_por_Item":"' . $rsptaCP["total_igv"] . '"
            }
          ]
      }';

      $arrayPrincipal = json_decode($cabecera_princ_json, true);
      $cabe_principal = $arrayPrincipal["cabecera_principal"][0];
      $values = array();
      foreach ($cabe_principal as $k => $va) {
        $values[] = $va;
      }
      $cabecera_principal = implode('|', $values);

      $arrayPrincipal2 = json_decode($cabecera_princ_json, true);
      $cabe_emisor = $arrayPrincipal2["leyenda"][0];
      $values2 = array();
      foreach ($cabe_emisor as $k => $v) {
        $values2[] = $v;
      }
      $leyenda = implode('|', $values2);


      $tributo_item = $arrayPrincipal2['tributo'][0];
      $tribut_evalue = array();
      foreach ($tributo_item as $key => $value) {
        $tribut_evalue[] = $value;
      }
      $infoLinea_tributo = implode('|', $tribut_evalue);

      // var_dump($tributo_monto_item);

      // $base_imponible 

      //Archivo DPA Y PAG
      $total_venta1 = $rsptaCP["total_venta"];
      $fecha_ven1 = $rsptaCP["fecha_ven"];
      $codigomoneda1 = $rsptaCP["codigoMoneda"];
      $descripcion_tipo_pago1 = $rsptaCP["descripcion_tipo_pago"];
      //$fecha_hora1 = $rsptaCP["fecha"];

      // $variable4 = $total_venta1.'|'.$fecha_ven1.'|'.$codigomoneda1.'|'.$fecha_hora1;
      $variable4 = $total_venta1 . '|' . $fecha_ven1 . '|' . $codigomoneda1;
      $variable5 = $descripcion_tipo_pago1 . '|' . $total_venta1 . '|' . $codigomoneda1;


      //Generar TXT
      $path = "../files/txt/";
      // $path = "../../../../SFS_v1.2/SFS_v1.2/sunat_archivos/sfs/DATA/";
      $nameCAB = $rsptaEmisor["ruc"] . "-0" . $rsptaCP["codigotipo_comprobante"] . "-" . $rsptaCP["serie"] . '-' . $rsptaCP["correlativo"] . ".CAB";
      $nameDET = $rsptaEmisor["ruc"] . "-0" . $rsptaCP["codigotipo_comprobante"] . "-" . $rsptaCP["serie"] . '-' . $rsptaCP["correlativo"] . ".DET";
      $nameLEY = $rsptaEmisor["ruc"] . "-0" . $rsptaCP["codigotipo_comprobante"] . "-" . $rsptaCP["serie"] . '-' . $rsptaCP["correlativo"] . ".LEY";
      $nameTRI = $rsptaEmisor["ruc"] . "-0" . $rsptaCP["codigotipo_comprobante"] . "-" . $rsptaCP["serie"] . '-' . $rsptaCP["correlativo"] . ".TRI";
      $nameDPA = $rsptaEmisor["ruc"] . "-0" . $rsptaCP["codigotipo_comprobante"] . "-" . $rsptaCP["serie"] . '-' . $rsptaCP["correlativo"] . ".DPA";
      $namePAG = $rsptaEmisor["ruc"] . "-0" . $rsptaCP["codigotipo_comprobante"] . "-" . $rsptaCP["serie"] . '-' . $rsptaCP["correlativo"] . ".PAG";

      //Obtener el valor seleccionado del combobox
      $opcion1 = $_POST['codigotipo_comprobante'];
      $opcion2 = $_POST['codigotipo_pago'];
      if ($opcion1 == '3') {
        //Accion
        file_put_contents($path . $nameCAB, $cabecera_principal);
        file_put_contents($path . $nameDET, $infoLinea);
        file_put_contents($path . $nameLEY, $leyenda);
        file_put_contents($path . $nameTRI, $infoLinea_tributo);
      } elseif ($opcion1 == '1' && $opcion2 == '1') {
        //Accion
        file_put_contents($path . $nameCAB, $cabecera_principal);
        file_put_contents($path . $nameDET, $infoLinea);
        file_put_contents($path . $nameLEY, $leyenda);
        file_put_contents($path . $nameTRI, $infoLinea_tributo);
        //file_put_contents($path.$nameDPA, $variable4);
        file_put_contents($path . $namePAG, $variable5);
      } else {
        file_put_contents($path . $nameCAB, $cabecera_principal);
        file_put_contents($path . $nameDET, $infoLinea);
        file_put_contents($path . $nameLEY, $leyenda);
        file_put_contents($path . $nameTRI, $infoLinea_tributo);
        file_put_contents($path . $nameDPA, $variable4);
        file_put_contents($path . $namePAG, $variable5);
      }
    }
    return $sw;
  }

  public function selectTipoComprobante()
  {
    $sql = "SELECT * from tipo_comprobante WHERE codigotipo_comprobante in (1,3) order by codigotipo_comprobante desc";
    return ejecutarConsulta($sql);
  }

  public function selectTipoComprobanteSalida()
  {
    $sql = "SELECT * from tipo_comprobante WHERE codigotipo_comprobante in (22) order by codigotipo_comprobante desc";
    return ejecutarConsulta($sql);
  }

  public function selecTipoIGV()
  {
    $sql = "SELECT * FROM `igv`";
    return ejecutarConsulta($sql);
  }

  public function selectTipoComprobanteReporte()
  {
    $sql = "SELECT * from tipo_comprobante WHERE codigotipo_comprobante in (1,3) order by codigotipo_comprobante desc";
    return ejecutarConsulta($sql);
  }
  public function selectTipoComprobanteReporteU()
  {
    $sql = "SELECT * from tipo_comprobante WHERE codigotipo_comprobante in (1,3,12) order by codigotipo_comprobante desc";
    return ejecutarConsulta($sql);
  }
  public function selectTipoPago()
  {
    // -- $sql="SELECT * from tipo_pago WHERE codigotipo_pago";
    $sql = "SELECT * from tipo_pago";
    return ejecutarConsulta($sql);
  }

  public function selectMoneda()
  {
    $sql = "SELECT * FROM moneda";
    return ejecutarConsulta($sql);
  }

  public function anular($idventa)
  {
    $sql = "UPDATE venta SET estado='Anulado' WHERE idventa='$idventa'";
    return ejecutarConsulta($sql);
  }

  public function mostrar($idventa)
  {
    $sql = "SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,v.codigotipo_comprobante,v.codigotipo_pago, tp.descripcion_tipo_pago,tc.descripcion_tipo_comprobante,v.serie,v.correlativo,v.fecha_ven,v.impuesto,v.op_gravadas,v.op_inafectas,v.op_exoneradas,v.op_gratuitas,v.isc,v.total_descuentos,v.total_venta,v.idmoneda,m.descripcion FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario INNER JOIN tipo_comprobante tc ON v.codigotipo_comprobante=tc.codigotipo_comprobante INNER JOIN moneda m ON v.idmoneda=m.idmoneda INNER JOIN tipo_pago tp ON v.codigotipo_pago=tp.codigotipo_pago WHERE v.idventa='$idventa'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function listarDetalle($idventa)
  {
    $sql = "SELECT dv.idventa,dv.idarticulo,a.nombre,a.unidad_medida,a.afectacion,dv.cantidad,dv.precio_venta,dv.descuento,dv.serie,(dv.cantidad*dv.precio_venta-dv.descuento) as subtotal,v.op_gravadas,v.op_inafectas,v.op_exoneradas,v.op_gratuitas,v.isc,v.total_descuentos,v.total_venta FROM detalle_venta dv inner join articulo a on dv.idarticulo=a.idarticulo inner join venta v on v.idventa=dv.idventa where dv.idventa='$idventa'";
    return ejecutarConsulta($sql);
  }

  // INSERT INTO `detalle_venta` (`iddetalle_venta`, `idventa`, `idarticulo`, `cantidad`, `precio_venta`, `descuento`, `afectacion`) VALUES (NULL, '12', '1', '3', '3', '0', NULL)

  public function listar()
  {
    $sql = "SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,v.codigotipo_comprobante,tc.descripcion_tipo_comprobante,v.serie,v.correlativo,v.total_venta,v.fecha_ven,v.impuesto,v.estado, v.rpta_sunat_codigo FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario INNER JOIN tipo_comprobante tc ON v.codigotipo_comprobante=tc.codigotipo_comprobante where v.estado!='Cotizado' and v.estado!='AnuladoC' and v.codigotipo_comprobante in (1,3) ORDER by v.idventa desc";
    return ejecutarConsulta($sql);
  }

  public function ventacabecera($idventa)
  {
    $sql = "SELECT v.idventa,v.idcliente,p.nombre as cliente,p.direccion,p.tipo_documento,p.num_documento,p.email,v.hora,p.telefono,v.idusuario,u.nombre as usuario,v.codigotipo_comprobante,v.serie,v.correlativo,date(v.fecha_hora) as fecha,v.fecha_hora as fechaCompleta,v.fecha_ven,m.codigo,v.impuesto,v.op_gravadas,v.op_inafectas,v.op_exoneradas,v.op_gratuitas,v.isc,v.total_venta,v.total_igv,v.igv_asig,v.idmoneda,m.descripcion as descmoneda, tp.descripcion_tipo_pago as codigo_pago, v.codigotipo_pago FROM venta v 
            INNER JOIN persona p ON v.idcliente=p.idpersona 
            INNER JOIN usuario u ON v.idusuario=u.idusuario 
            INNER JOIN moneda m ON m.idmoneda=v.idmoneda 
            LEFT JOIN tipo_pago tp ON v.codigotipo_pago = tp.codigotipo_pago
            WHERE v.idventa='$idventa'";
    return ejecutarConsulta($sql);
  }
  public function ventacabeceraxml($idventa)
  {
    $sql = "SELECT v.idventa,v.idcliente,p.nombre as cliente,v.doc_relacionado,v.idmotivo_doc,p.direccion,p.tipo_documento,p.num_documento,p.email,v.hora,p.telefono,v.idusuario,u.nombre as usuario,v.codigotipo_comprobante,v.serie,v.correlativo,date(v.fecha_hora) as fecha,v.fecha_hora as fechaCompleta,v.fecha_ven,m.codigo,v.impuesto,v.op_gravadas,v.op_inafectas,v.op_exoneradas,v.op_gratuitas,v.isc,v.total_venta,v.total_igv,v.igv_asig,v.idmoneda,m.descripcion as descmoneda, tp.descripcion_tipo_pago as codigo_pago, v.codigotipo_pago FROM venta v 
            INNER JOIN persona p ON v.idcliente=p.idpersona 
            INNER JOIN usuario u ON v.idusuario=u.idusuario 
            INNER JOIN moneda m ON m.idmoneda=v.idmoneda 
            LEFT JOIN tipo_pago tp ON v.codigotipo_pago = tp.codigotipo_pago
            WHERE v.idventa='$idventa'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function ventadetallexml($idventa)
  {
    $sql = "SELECT a.nombre as articulo,a.unidad_medida,a.descripcion_otros,a.afectacion,d.cantidad,d.precio_venta,d.descuento,d.serie,(d.cantidad*d.precio_venta-d.descuento) as subtotal, a.codigo as codigoproduct
    FROM detalle_venta d 
    INNER JOIN articulo a ON d.idarticulo=a.idarticulo 
    WHERE d.idventa='$idventa'";
    $result = ejecutarConsulta($sql);

    // Verifica que la consulta haya tenido resultados
    if ($result && $result->num_rows > 0) {
      // Convierte el objeto mysqli_result en un arreglo asociativo
      $detalle = $result->fetch_all(MYSQLI_ASSOC);
      return $detalle;
    } else {
      return array(); // Devuelve un arreglo vacío si no hay resultados
    }
  }


  public function ventadetalle($idventa)
  {
    $sql = "SELECT a.nombre as articulo,a.unidad_medida,a.descripcion_otros,a.afectacion,d.cantidad,d.precio_venta,d.descuento,d.serie,(d.cantidad*d.precio_venta-d.descuento) as subtotal, a.codigo 
    FROM detalle_venta d 
    INNER JOIN articulo a ON d.idarticulo=a.idarticulo 
    WHERE d.idventa='$idventa'";
    return ejecutarConsulta($sql);
  }

  public function cabecera_perfil()
  {
    $sql = "SELECT * FROM perfil";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function perfil_sunat()
  {
    $sql = "SELECT * FROM perfil_sunat";
    return ejecutarConsultaSimpleFila($sql);
  }


  //precio base x IGV (seha el caso) + el impuesto a la bosa. ambos en unidad 1.
  public function priceAmount($precio_base, $codigo_de_tributo, $percent, $icbper, $descuento = 0)
  {
    $precio_base = floatval($precio_base);
    // $precio_base=$precio_base/(1+($percent/100));
    $codigo_de_tributo = floatval($codigo_de_tributo); //=1000
    // $percent = floatval($percent);
    $percent = $percent / 100; //=0.18
    $icbper = floatval($icbper); //=0
    $priceAmount = '';
    if ($codigo_de_tributo == 1000) {
      // $priceAmount = number_format((($precio_base - $descuento) * ( 1 + $percent)),2, '.', '') + $icbper;            
      $priceAmount = $precio_base;
    } else {
      // $priceAmount = $precio_base - $descuento + $icbper;
      $priceAmount = $precio_base - $descuento + $icbper;
    }
    return $priceAmount;
  }

  public function taxAmount($cantidad, $precio_base, $codigo_de_tributo, $percent, $descuento = 0)
  {
    $taxAmount = '';
    $precio_base = $precio_base / (1 + ($percent / 100));
    $precio_base = number_format($precio_base, 2, '.', '');
    $percent = $percent / 100;
    switch ($codigo_de_tributo) {
      case 1000:
        $taxAmount = $cantidad * ($precio_base - $descuento) * $percent;
        break;
      case 9995:
        $taxAmount = 0.0;
        break;
      case 9996:
        $taxAmount = $cantidad * ($precio_base / (1 + $percent)) * $percent;
        break;
      case 9997:
        $taxAmount = 0.0;
        break;
      case 9998:
        $taxAmount = 0.0;
        break;
    }
    return $taxAmount;
  }

  public function price_priceAmount($precio_base, $codigo_de_tributo)
  {
    $precio_base = $precio_base / (1 + (18 / 100));
    $precio_base = number_format($precio_base, 2, '.', '');
    $price_priceAmount = ($codigo_de_tributo == 9996) ? 0.0 : $precio_base;
    return $price_priceAmount;
  }

  public function guardarRptaSunat($idventa, $rpta)
  {

    $conexion = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    mysqli_query($conexion, 'SET NAMES "' . DB_ENCODE . '"');
    $codigo = mysqli_real_escape_string($conexion, $rpta['respuesta_sunat_codigo']);
    $descripcion = mysqli_real_escape_string($conexion, $rpta['respuesta_sunat_descripcion']);
    $sql = "UPDATE venta SET rpta_sunat_codigo = '$codigo', rpta_sunat_descripcion = '$descripcion' WHERE idventa = '$idventa'";
    return ejecutarConsulta($sql);
  }

  function registrar_errores_sunat($codigo, $idventa)
  {
    $datos_rpta = array();

    if ($codigo == '0') {
      $datos_rpta['respuesta_sunat_codigo'] = 1;
      $datos_rpta['respuesta_sunat_descripcion'] = 'Operación exitosa';
    } elseif (((int)$codigo >= 2010) && ((int)$codigo <= 2752)) {
      $datos_rpta['respuesta_sunat_codigo'] = 2;
      $datos_rpta['respuesta_sunat_descripcion'] = 'Operación rechazada';
    } else {
      $datos_rpta['respuesta_sunat_codigo'] = 3;
      $datos_rpta['respuesta_sunat_descripcion'] = 'Entro al else';
    }

    $this->guardarRptaSunat($idventa, $datos_rpta);
  }

  public function venta_documento_relacionado_xml($idventa)
  {
    $sql = "SELECT v.idventa, v.serie, v.correlativo, tc.codigotipo_comprobante, v.sustento
  FROM venta v
  INNER JOIN tipo_comprobante tc ON v.codigotipo_comprobante = tc.codigotipo_comprobante
  WHERE v.idventa = '$idventa'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function tipo_ncredito($id_motivo_doc)
  {
    $sql = "SELECT codigo_motivo, motivo, descripcion 
  FROM motivo_documento
  WHERE idmotivo_documento = '$id_motivo_doc'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function listarTipoNotaCredito()
  {
    $sql = "SELECT * FROM motivo_documento where idmotivo_documento in (1,2,6,7) ";
    return ejecutarConsulta($sql);
  }
}
