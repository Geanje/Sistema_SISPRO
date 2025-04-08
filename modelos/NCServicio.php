<?php
require_once "../config/Conexion.php";

class NServicio
{

  // Implementamos nuestro constructor
  public function __construct()
  {
  }

  // Implementamos un método para insertar registros
  public function insertarNC($idcliente, $idusuario, $serie, $correlativo, $fecha_hora, $impuesto, $op_gravadas, $op_inafectas, $op_exoneradas, $op_gratuitas, $isc, $total_venta, $idmoneda, $idtiponotacredito, $sustento, $doc_relacionado, $igv_asig)
  {
    $sql = "INSERT INTO factura (idcliente,idusuario,codigotipo_comprobante,serie,correlativo,fecha_hora,impuesto,op_gravadas,op_inafectas,op_exoneradas,op_gratuitas,isc,total_venta,estado,idmoneda,idmotivo_doc,sustento,doc_relacionado,igv_asig) VALUES ('$idcliente','$idusuario','7','$serie','$correlativo','$fecha_hora','$impuesto','$op_gravadas','$op_inafectas','$op_exoneradas','$op_gratuitas','$isc','$total_venta','Aceptado','$idmoneda','$idtiponotacredito','$sustento','$doc_relacionado','$igv_asig')";
    return ejecutarConsulta($sql);
  }


  public function insertar($idcliente, $idusuario, $serie, $correlativo, $fecha_hora, $impuesto, $igv_asig, $op_gravadas, $op_inafectas, $op_exoneradas, $op_gratuitas, $isc, $total_igv, $total_venta, $leyenda, $idmoneda, $idtiponotacredito, $sustento, $doc_relacionado, $nombre, $cantidad, $precio_venta, $descuento, $seried)
  {
    $hora = date("H:i:s");
    $sql = "INSERT INTO factura (idcliente,idusuario,codigotipo_comprobante,serie,correlativo,fecha_hora,hora,impuesto,igv_asig,op_gravadas,op_inafectas,op_exoneradas,op_gratuitas,isc,igv_total,total_venta,leyenda,estado,idmoneda,idmotivo_doc,sustento,doc_relacionado) VALUES ('$idcliente','$idusuario','7','$serie','$correlativo','$fecha_hora','$hora','$impuesto','$igv_asig','$op_gravadas','$op_inafectas','$op_exoneradas','$op_gratuitas','$isc','$total_igv','$total_venta','$leyenda','AceptadoNC','$idmoneda','$idtiponotacredito','$sustento','$doc_relacionado')";
    $idfacturanew = ejecutarConsulta_retornarID($sql);
    $num_elementos = 0;
    $sw = true;

    while ($num_elementos < count($nombre)) {
      $sql_detalle = "INSERT INTO nc_servicio (id_factura, id_detalle_factura, cantidad, precio_venta, descuento, serie) VALUES ('$idfacturanew', '$nombre[$num_elementos]', '$cantidad[$num_elementos]', '$precio_venta[$num_elementos]', '$descuento[$num_elementos]', '$seried[$num_elementos]')";
      $result = ejecutarConsulta($sql_detalle);
      if (!$result) {
        $sw = false;
        break;
      }
      $num_elementos++;
    }

    //Consulta de Factura
    if ($sw) {
      $sqlCabeceraPrincipal = "SELECT v.id_factura,DATE(v.fecha_hora) as fecha, DATE_FORMAT(v.fecha_hora,\"%H:%I:%S\" ) as hora ,v.idcliente,p.nombre as clienteRazonSocial,p.direccion,p.tipo_documento,p.num_documento,v.idusuario,u.nombre as usuario,v.codigotipo_comprobante,v.serie,v.correlativo,date(v.fecha_hora) as fecha,v.impuesto,v.op_gravadas,v.op_inafectas,v.op_exoneradas,v.op_gratuitas,v.isc,v.total_descuentos,v.igv_total,v.total_venta,v.leyenda,v.idmoneda,v.idmotivo_doc,mdo.codigo_motivo,v.sustento,vr.codigotipo_comprobante as tipoDocModifica,vr.serie as serieModifica,vr.correlativo as correlativoModifica,m.codigo as codigoMoneda FROM factura v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario INNER JOIN moneda m ON m.idmoneda=v.idmoneda INNER JOIN motivo_documento mdo ON mdo.idmotivo_documento=v.idmotivo_doc INNER JOIN factura vr ON v.doc_relacionado = vr.id_factura WHERE v.id_factura='$idfacturanew'";
      $rsptaCP = ejecutarConsultaSimpleFila($sqlCabeceraPrincipal);
      if ($rsptaCP["tipo_documento"] == "RUC") {
        $cliente_tipo_documento = "6";
      } else {
        $cliente_tipo_documento = "1";
      }

      $rsptaCP["igv_total"];



      //Detalle Factura
      $sqlLinea = "SELECT ns.idnota_credito,ns.id_factura,ns.id_detalle_factura,ns.cantidad,ns.precio_venta,ns.descuento,ns.correccion_descripcion, ns.serie, dv.codigo_prod,dv.descripcion_prod,dv.unidad_medida,dv.afectacion FROM nc_servicio ns INNER JOIN detalle_factura dv on ns.id_detalle_factura = dv.id_detalle_factura WHERE ns.id_factura='$idfacturanew'";
      $rsptaDetalleVenta = ejecutarConsulta($sqlLinea);

      $base_imponible = 0;
      $tributo_monto_item = 0;
      while ($rpta_linea = $rsptaDetalleVenta->fetch_object()) {
        // $valor_venta ="dd";
        if ($rpta_linea->afectacion = "Gravado") {

          $valor_unitario = round(($rpta_linea->precio_venta / 1.18), 5);
          $base_impo = round($valor_unitario * $rpta_linea->cantidad, 2);
          $valor_venta = round($valor_unitario * $rpta_linea->cantidad, 2);
          $impuestos = round($valor_unitario * 0.18 * $rpta_linea->cantidad, 2);

          $Tipo = $rsptaCP["Tipo"];

          if ($Tipo == "Con_IVG") {
            $impuestos = round($valor_unitario * 0.18 * $rpta_linea->cantidad, 2);
          } else {
            $impuestos = round($valor_unitario * 1.18 * $rpta_linea->cantidad, 2);
          }

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
        $data_json = '{
                "linea": [
                      {
                      "Código_de_unidad_de_medida_por_ítem":"' . $rpta_linea->unidad_medida . '",
                       "Cantidad_de_unidades_por_ítem":"' . $rpta_linea->cantidad . '",
                       "Código_de_producto":"' . $rpta_linea->codigo_prod . '",
                       "Codigo_producto_SUNAT":"",
                       "Descripción_detallada_del_servicio_bien_características":"' . $rpta_linea->descripcion_prod . ' ' . $rpta_linea->serie . '",
                       "Valor_Unitario":"' . $valor_unitario . '",
                       "Sumatoria_Tributos_por_item":"' . $impuestos . '",
                       "Códigos_de_tipos_de_tributos_IGV":"' . $codTipoTributo . '",
                       "Monto_de_IGV_por_ítem":"' . $rsptaCP["igv_total"] . '",
                       "Base_imponible_igv_item":"' . $base_impo . '",
                       "Nombre_de_tributo_por_item":"' . $nomTributo . '",
                       "Código_de_tipo_de_tributo_por_Item":"' . $tipoTributo . '",
                       "Afectación_al_IGV_por_ítem":"' . $afectacion . '",
                       "Porcentaje_de_IGV":"18.0",
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
          $detalle[] = implode("|", $valu);
        }
        $deta = implode(PHP_EOL, $detalle);
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
            "codigo_domicilio_fiscal":"0000",
            "tipo_documento_identidad_adquiriente_usuario":"' . $cliente_tipo_documento . '",
            "numero_identidad_adquiriente_usuario":"' . $rsptaCP["num_documento"] . '",
            "apellidos_nombres_denominacion_razon_social_entidad_adquiriente_usuario":"' . $rsptaCP["clienteRazonSocial"] . '",
            "tipo_moneda_factura_e":"' . $rsptaCP["codigoMoneda"] . '",

            "Código_del_tipo_de_Nota_de_débito_electrónica": "' . $rsptaCP["codigo_motivo"] . '",
            "Descripción_de_motivo_o_sustento": "' . $rsptaCP["sustento"] . '",
            "Tipo_de_documento_del_documento_que_modifica":"0' . $rsptaCP["tipoDocModifica"] . '",
            "Serie_y_número_del_documento_que_modifica": "' . $rsptaCP["serieModifica"] . '-' . $rsptaCP["correlativoModifica"] . '",

            "Sumatoria_Tributos":"' . $rsptaCP["igv_total"] . '",
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
              "Monto_de_Tirbuto_por_Item":"' . $rsptaCP["igv_total"] . '"
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




      //Generar TXT
      $path = "../files/txt/";
      // $path = "../../../../SFS_v1.2/SFS_v1.2/sunat_archivos/sfs/DATA/";
      $nameCAB = $rsptaEmisor["ruc"] . "-0" . $rsptaCP["codigotipo_comprobante"] . "-" . $rsptaCP["serie"] . '-' . $rsptaCP["correlativo"] . ".NOT";
      $nameDET = $rsptaEmisor["ruc"] . "-0" . $rsptaCP["codigotipo_comprobante"] . "-" . $rsptaCP["serie"] . '-' . $rsptaCP["correlativo"] . ".DET";
      $nameLEY = $rsptaEmisor["ruc"] . "-0" . $rsptaCP["codigotipo_comprobante"] . "-" . $rsptaCP["serie"] . '-' . $rsptaCP["correlativo"] . ".LEY";
      $nameTRI = $rsptaEmisor["ruc"] . "-0" . $rsptaCP["codigotipo_comprobante"] . "-" . $rsptaCP["serie"] . '-' . $rsptaCP["correlativo"] . ".TRI";

      if (file_exists($path . $nameCAB)) {
        $name = $rsptaEmisor["ruc"] . "-0" . $rsptaCP["codigotipo_comprobante"] . "-" . $rsptaCP["serie"] . '-' . $rsptaCP["correlativo"] . "-error.txt";
        file_put_contents($path . $name, "Error al guardar los datos, hubo una duplicidad en la serie y el correlativo");
      } else {
        file_put_contents($path . $nameCAB, $cabecera_principal);
        file_put_contents($path . $nameDET, $infoLinea);
        file_put_contents($path . $nameLEY, $leyenda);
        file_put_contents($path . $nameTRI, $infoLinea_tributo);
      }
    }


    return $sw;
  }




















  // Implementamos un método para listar tipo nota credito
  public function listarTipoNotaCredito()
  {
    $sql = "SELECT * FROM motivo_documento where idmotivo_documento in (1,2,6,7) ";
    return ejecutarConsulta($sql);
  }

  public function listar()
  {
    $sql = "SELECT f.id_factura,DATE(f.fecha_hora) as fecha,f.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,f.codigotipo_comprobante,tc.descripcion_tipo_comprobante,f.serie,f.correlativo,f.estado,f.idmotivo_doc,md.motivo,f.doc_relacionado,f.rpta_sunat_codigo,f.rpta_sunat_descripcion FROM factura f INNER JOIN persona p ON f.idcliente=p.idpersona INNER JOIN usuario u ON f.idusuario=u.idusuario INNER JOIN tipo_comprobante tc ON f.codigotipo_comprobante=tc.codigotipo_comprobante INNER JOIN motivo_documento md ON f.idmotivo_doc=md.idmotivo_documento where f.estado='AceptadoNC' or f.estado='AnuladoNC'and f.codigotipo_comprobante='7' ORDER by f.id_factura desc";
    return ejecutarConsulta($sql);
  }

  public function mostrarDocRelacionado($id_factura, $idfacturarelacionado)
  {
    $sql = "SELECT f.id_factura, DATE(f.fecha_hora) as fecha, f.idcliente, p.nombre as cliente, u.idusuario, u.nombre as usuario, f.codigotipo_comprobante, tc.descripcion_tipo_comprobante, f.serie, f.correlativo, f.estado, (SELECT sustento FROM factura WHERE id_factura='$id_factura') as sustento, (SELECT md.motivo FROM factura f INNER JOIN motivo_documento md ON f.idmotivo_doc=md.idmotivo_documento WHERE f.id_factura='$id_factura') as tipoNotaV FROM factura f INNER JOIN persona p ON f.idcliente=p.idpersona INNER JOIN usuario u ON f.idusuario=u.idusuario INNER JOIN tipo_comprobante tc ON f.codigotipo_comprobante=tc.codigotipo_comprobante WHERE f.id_factura='$idfacturarelacionado'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function listarComprobantes()
  {
    $sql = "SELECT v.id_factura,v.idcliente,p.nombre as cliente,p.num_documento, v.idusuario,u.nombre as usuario,v.estado as
    estado,v.codigotipo_comprobante,tc.descripcion_tipo_comprobante,v.serie,v.correlativo,DATE(v.fecha_hora) as
    fecha,v.op_gravadas,v.op_exoneradas,v.total_venta,v.impuesto,v.idmoneda,m.descripcion 
    FROM factura v 
    INNER JOIN tipo_comprobante tc ON v.codigotipo_comprobante=tc.codigotipo_comprobante 
    INNER JOIN persona p ON v.idcliente=p.idpersona 
    INNER JOIN usuario u ON v.idusuario=u.idusuario 
    INNER JOIN moneda m ON m.idmoneda=v.idmoneda 
    WHERE v.estado!='Cotizado' and v.estado not in('AnuladoNC','Anulado')
    and v.codigotipo_comprobante in (1,3) ORDER BY id_factura desc";
    return ejecutarConsulta($sql);
  }

  public function ultimoCorrelativo()
  {
    $sql = "SELECT id_factura,codigotipo_comprobante,correlativo,serie 
    from factura where codigotipo_comprobante=7
    order by id_factura desc limit 1";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function anular($id_factura)
  {
    $sql = "UPDATE factura SET estado='AnuladoNC' where id_factura='$id_factura'";
    return ejecutarConsulta($sql);
  }

  public function listardetallecomprobantes($id_factura)
  {
    $sql = "SELECT df.id_factura,df.id_detalle_factura,df.serie as seried ,df.codigo_prod,df.descripcion_prod as nombre,df.cantidad,df.afectacion,df.precio_venta,df.descuento,(df.cantidad*df.precio_venta-df.descuento) as subtotal,f.op_gravadas,f.op_inafectas,f.op_exoneradas,f.op_gratuitas,f.isc,f.total_venta, f.Tipo FROM detalle_factura df inner join factura f on f.id_factura=df.id_factura where df.id_factura=$id_factura";
    return ejecutarConsulta($sql);
  }

  public function ventacabeceraxml($id_factura)
  {
    $sql = "SELECT v.id_factura,v.idcliente,p.nombre as cliente,p.direccion,p.tipo_documento,p.num_documento,p.email,p.telefono,v.idusuario,u.nombre as usuario,v.codigotipo_comprobante,v.serie,v.correlativo,date(v.fecha_hora) as fecha,v.impuesto,v.op_gravadas,v.op_inafectas,v.op_exoneradas,v.op_gratuitas,v.isc,v.total_venta,v.idmoneda,v.idmotivo_doc,md.motivo,v.sustento,v.doc_relacionado, m.descripcion as descmoneda, ( SELECT serie FROM factura where id_factura=v.doc_relacionado) as serie_rela, ( SELECT correlativo FROM factura where id_factura=v.doc_relacionado) as correlativo_rela FROM factura v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario INNER JOIN moneda m ON m.idmoneda=v.idmoneda INNER JOIN motivo_documento md ON md.idmotivo_documento=v.idmotivo_doc WHERE v.id_factura='$id_factura'";
    return ejecutarConsulta($sql);
  }

  public function ventadetallexml($id_factura)
  {
    $sql = "SELECT dv.descripcion_prod as articulo, dv.unidad_medida, dv.descripcion_otros, dv.afectacion, nc.cantidad, nc.precio_venta, nc.descuento, nc.correccion_descripcion, nc.serie, (nc.cantidad*nc.precio_venta-nc.descuento) as subtotal FROM nc_servicio nc INNER JOIN detalle_factura dv ON dv.id_detalle_factura=nc.id_detalle_factura WHERE nc.id_factura='$id_factura'";
    return ejecutarConsulta($sql);
  }

  public function ventacabecera($id_factura)
  {
    $sql = "SELECT v.id_factura,v.idcliente,p.nombre as cliente,p.direccion,p.tipo_documento,p.num_documento,p.email,p.telefono,v.idusuario,u.nombre as usuario,v.codigotipo_comprobante,v.serie,v.correlativo,date(v.fecha_hora) as fecha,v.impuesto,v.op_gravadas,v.op_inafectas,v.op_exoneradas,v.op_gratuitas,v.isc,v.total_venta,v.idmoneda,v.idmotivo_doc,md.motivo,v.sustento,v.doc_relacionado, m.descripcion as descmoneda,
    ( SELECT serie FROM factura where id_factura=v.doc_relacionado) as serie_rela,
    ( SELECT correlativo FROM factura where id_factura=v.doc_relacionado) as correlativo_rela 
    FROM factura v 
    INNER JOIN persona p ON v.idcliente=p.idpersona 
    INNER JOIN usuario u ON v.idusuario=u.idusuario 
    INNER JOIN moneda m ON m.idmoneda=v.idmoneda 
    INNER JOIN motivo_documento md ON md.idmotivo_documento=v.idmotivo_doc 
    WHERE v.id_factura='$id_factura'";
    return ejecutarConsulta($sql);
  }

  public function ventadetalle($id_factura)
  {
    $sql = "SELECT dv.descripcion_prod as articulo, dv.unidad_medida, dv.descripcion_otros, dv.afectacion, nc.cantidad, nc.precio_venta, nc.descuento, nc.correccion_descripcion, nc.serie, (nc.cantidad*nc.precio_venta-nc.descuento) as subtotal FROM nc_servicio nc INNER JOIN detalle_factura dv ON dv.id_detalle_factura=nc.id_detalle_factura WHERE nc.id_factura='$id_factura'";
    return ejecutarConsulta($sql);
  }
}
