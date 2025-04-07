<?php
date_default_timezone_set('America/Lima');
require_once('../config/Conexion.php');


class Factura
{

	public function __construct()
	{
	}
	public function insertar($idusuario, $idcliente, $codigotipo_comprobante, $codigotipo_pago, $fecha_hora, $fecha_ven, $idmoneda, $impuesto, $op_gravadas, $igv_total, $total_venta, $leyenda, $codigo_prod, $descripcion_prod, $unidad_medida, $cantidad, $precio_venta, $igv_asig, $Tipo)
	{

		$saber = "SELECT serie,correlativo FROM factura WHERE codigotipo_comprobante='$codigotipo_comprobante'";
		$saberExiste = ejecutarConsultaSimpleFila($saber);
		if ($saberExiste["serie"] == null and $saberExiste["correlativo"] == null) {
			if ($codigotipo_comprobante != 3) {
				$serie = 'F003';
			} else {
				$serie = 'B003';
			}
			$correlativo = '00000001';
		} else {
			$sqlmaxserie = "SELECT max(serie) as maxSerie FROM factura WHERE codigotipo_comprobante='$codigotipo_comprobante' ";
			$maxserie = ejecutarConsultaSimpleFila($sqlmaxserie);
			$serie = $maxserie["maxSerie"];
			$ultimoCorrelativo = "SELECT max(correlativo) as ultimocorrelativo,serie,correlativo FROM factura WHERE codigotipo_comprobante='$codigotipo_comprobante' and serie='$serie'";
			$ultimo = ejecutarConsultaSimpleFila($ultimoCorrelativo);
			if ($ultimo["ultimocorrelativo"] == '99999999') {
				$ser = substr($serie, 1) + 1;
				$seri = str_pad((string)$ser, 3, "0", STR_PAD_LEFT);
				if ($codigotipo_comprobante != 3) {
					$serie = "F" . $seri;
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

		$sql = "INSERT INTO factura(idusuario,idcliente,codigotipo_comprobante,serie,correlativo,fecha_hora,fecha_ven,idmoneda,codigotipo_pago,impuesto,op_gravadas,igv_total,total_venta,leyenda,estado,hora,igv_asig,Tipo) VALUES ('$idusuario','$idcliente','$codigotipo_comprobante','$serie','$correlativo','$fecha_hora','$fecha_ven','$idmoneda','$codigotipo_pago','$impuesto','$op_gravadas','$igv_total','$total_venta','$leyenda','Aceptado','$hora','$igv_asig','$Tipo')";
		$id_facturanew = ejecutarConsulta_retornarID($sql);
		$num_elementos = 0;
		$sw = true;
		while ($num_elementos < count($cantidad)) {
			$sql_detalle = "INSERT INTO detalle_factura(id_factura,codigo_prod,descripcion_prod,unidad_medida,cantidad,precio_venta,fecha_vendido) VALUES('$id_facturanew','$codigo_prod[$num_elementos]','$descripcion_prod[$num_elementos]','$unidad_medida[$num_elementos]','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$fecha_todo')";
			ejecutarConsulta($sql_detalle) or $sw = false;
			$num_elementos++;
		}

		/*=============================================
   				=            EMPIEZA TXT           =
    		=============================================*/
		if ($sw) {
			/*=============================================
	      CONSULTA VENTA
	      =============================================*/

			$sqlCabeceraPrincipal = "SELECT v.id_factura,DATE(v.fecha_hora) as fecha, DATE_FORMAT(v.fecha_hora,\"%H:%i:%S\" ) as hora ,v.idcliente,
		  p.nombre as clienteRazonSocial,p.direccion,
		  p.tipo_documento,p.num_documento,v.idusuario,u.nombre as usuario,
		  v.codigotipo_comprobante,v.serie,v.correlativo,v.impuesto,v.op_gravadas,v.igv_total,v.total_venta,v.leyenda,v.moneda,v.codigotipo_pago,t.descripcion_tipo_pago, v.fecha_ven
		  FROM factura v 
		  INNER JOIN persona p ON v.idcliente=p.idpersona 
		  INNER JOIN usuario u ON v.idusuario=u.idusuario
		  INNER JOIN tipo_pago t ON t.codigotipo_pago=v.codigotipo_pago
		  WHERE v.id_factura='$id_facturanew'";
			$rsptaCP = ejecutarConsultaSimpleFila($sqlCabeceraPrincipal);
			if ($rsptaCP["tipo_documento"] == "RUC") {
				$cliente_tipo_documento = "6";
			} else {
				$cliente_tipo_documento = "1";
			}


			/*=============================================
	      CONSULTA DETALLE VENTA
	      =============================================*/
			$sqlLinea = "SELECT dv.id_factura,dv.descripcion_prod,dv.unidad_medida,dv.codigo_prod,dv.cantidad,dv.precio_venta FROM detalle_factura dv where dv.id_factura='$id_facturanew'";
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

				$valor_unitario = round(($rpta_linea->precio_venta / 1), 5);
				$base_impo = round($valor_unitario * $rpta_linea->cantidad, 2);
				$valor_venta = round($valor_unitario * $rpta_linea->cantidad, 2);
				$impuestos = round($valor_unitario * ($igv_db / 100) * $rpta_linea->cantidad, 2);
				$codTipoTributo = 1000;
				$afectacion = 10;
				$nomTributo = "IGV";
				$tipoTributo = "VAT";

				$prueba = floatval($rpta_linea->precio_venta) + floatval($impuestos);


				$base_imponible += $base_impo;
				$tributo_monto_item += $impuestos;

				// $igv_db;
				$igv_db_1 = number_format($igv_db, 1, ".", ",");

				$data_json = '{
	                "linea": [
	                      {
	                      "Código_de_unidad_de_medida_por_ítem":"' . $rpta_linea->unidad_medida . '",
	                       "Cantidad_de_unidades_por_ítem":"' . $rpta_linea->cantidad . '",
	                       "Código_de_producto":"' . $rpta_linea->codigo_prod . '",
	                       "Codigo_producto_SUNAT":"",
	                       "Descripción_detallada_del_servicio_bien_características":"' . $rpta_linea->descripcion_prod . '",
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
	



	                       "Precio_de_venta_unitario":"' . number_format($prueba, 2, ".", ",") . '",
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
	            "tipo_moneda_factura_e":"PEN",
	            "Sumatoria_Tributos":"' . $rsptaCP["igv_total"] . '",
	            "Total_valor_de_venta":"' . $rsptaCP["op_gravadas"] . '",
	            "Total_Precio_de_Venta":"' . $rsptaCP["total_venta"] . '",
	            "Total_descuentos":"0.00",
	            "Sumatoria_otros_Cargos":"0.00",
	            "Total_Anticipos":"0.00",
	            "Importe_total_de_la_venta_cesión_en_uso_o_del_servicio_prestado":"' . $rsptaCP["total_venta"] . '",
	            "Versión_UBL":"2.1",
	            "Customization Documento":"2.0"


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

			$total_venta1 = $rsptaCP["total_venta"];
			$fecha_ven1 = $rsptaCP["fecha_ven"];
			$codigomoneda1 = $rsptaCP["moneda"];
			$descripcion_tipo_pago1 = $rsptaCP["descripcion_tipo_pago"];

			$variable4 = $total_venta1 . '|' . $fecha_ven1 . '|' . $codigomoneda1;
			$variable5 = $descripcion_tipo_pago1 . '|' . $total_venta1 . '|' . $codigomoneda1;


			//Generar TXT
			$path = "../files/txt/";
			$nameCAB = $rsptaEmisor["ruc"] . "-0" . $rsptaCP["codigotipo_comprobante"] . "-" . $rsptaCP["serie"] . '-' . $rsptaCP["correlativo"] . ".CAB";
			$nameDET = $rsptaEmisor["ruc"] . "-0" . $rsptaCP["codigotipo_comprobante"] . "-" . $rsptaCP["serie"] . '-' . $rsptaCP["correlativo"] . ".DET";
			$nameLEY = $rsptaEmisor["ruc"] . "-0" . $rsptaCP["codigotipo_comprobante"] . "-" . $rsptaCP["serie"] . '-' . $rsptaCP["correlativo"] . ".LEY";
			$nameTRI = $rsptaEmisor["ruc"] . "-0" . $rsptaCP["codigotipo_comprobante"] . "-" . $rsptaCP["serie"] . '-' . $rsptaCP["correlativo"] . ".TRI";
			$nameDPA = $rsptaEmisor["ruc"] . "-0" . $rsptaCP["codigotipo_comprobante"] . "-" . $rsptaCP["serie"] . '-' . $rsptaCP["correlativo"] . ".DPA";
			$namePAG = $rsptaEmisor["ruc"] . "-0" . $rsptaCP["codigotipo_comprobante"] . "-" . $rsptaCP["serie"] . '-' . $rsptaCP["correlativo"] . ".PAG";


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


	public function maxVenta($codigotipo_comprobante)
	{
		$saber = "SELECT serie,correlativo FROM factura WHERE codigotipo_comprobante='$codigotipo_comprobante'";
		$saberExiste = ejecutarConsultaSimpleFila($saber);
		if ($saberExiste["serie"] == null and $saberExiste["correlativo"] == null) {
			if ($codigotipo_comprobante == 'Factura') {
				$serie = 'F001';
			} else if ($codigotipo_comprobante == 'Boleta') {
				$serie = 'B001';
			}
			$correlativo = '00000001';
		} else {
			$sql = "SELECT max(serie) as maxSerie FROM factura WHERE codigotipo_comprobante='$codigotipo_comprobante'";
			$maxserie = ejecutarConsultaSimpleFila($sql);
			$serie = $maxserie["maxSerie"];
			$ultimoCorrelativo = "SELECT max(correlativo) as maxCorrelativo FROM factura WHERE codigotipo_comprobante='$codigotipo_comprobante' and serie='$serie'";
			$ultimo = ejecutarConsultaSimpleFila($ultimoCorrelativo);


			if ($ultimo["maxCorrelativo"] == '99999999') {
				$seri = substr($serie, 1) + 1;
				$llenadoSerie = str_pad((string)$seri, 3, "0", STR_PAD_LEFT);

				$correlativo = '00000001';
				if ($codigotipo_comprobante == "Factura") {
					$serie = 'F' . $llenadoSerie;
				} else if ($codigotipo_comprobante == "Boleta") {
					$serie = 'B' . $llenadoSerie;
				}
			} else {
				$corre = $ultimo["maxCorrelativo"] + 1;
				$correlativo = str_pad($corre, 8, "0", STR_PAD_LEFT);
			}
		}
		$retornar = ["maxSerie" => $serie, "maxCorrelativo" => $correlativo];
		return $retornar;
	}

	public function listar($listado)
	{
		$tipoValue = ($listado == 1) ? 'Con_IGV' : 'Sin_IGV';

		$sql = "SELECT v.id_factura, DATE(v.fecha_hora) as fecha, v.idcliente, p.nombre as cliente, u.idusuario, u.nombre as usuario, v.codigotipo_comprobante, v.serie, v.correlativo, v.total_venta, v.impuesto, v.estado, v.Tipo,v.rpta_sunat_codigo
    FROM factura v
    INNER JOIN persona p ON v.idcliente=p.idpersona
    INNER JOIN usuario u ON v.idusuario=u.idusuario
    WHERE v.estado!='AnuladoC' AND v.estado!='AnuladoNC' AND v.estado!='AceptadoNC' AND v.estado!='AnuladoND' AND v.estado!='AceptadoND' AND v.Tipo='$tipoValue'
    ORDER BY v.id_factura DESC";

		return ejecutarConsulta($sql);
	}

	public function anular($id_factura)
	{
		$sql = "UPDATE factura SET estado='Anulado' WHERE id_factura='$id_factura'";
		return ejecutarConsulta($sql);
	}

	public function eliminar($id_factura)
	{
		$sql = "DELETE FROM factura WHERE id_factura='$id_factura'";
		return ejecutarConsulta($sql);
	}

	public function mostrar($id_factura)
	{
		$sql = "SELECT * FROM factura WHERE id_factura='$id_factura'";
		$sql = "SELECT v.id_factura,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,p.num_documento,p.tipotipo_documento_doc,p.direccion,v.idusuario,v.codigotipo_comprobante,v.serie,v.correlativo,v.impuesto,v.moneda,v.op_gravadas,v.igv_total,v.total_venta FROM factura v INNER JOIN persona p ON v.idcliente=p.idpersona WHERE v.id_factura='$id_factura'";
		$query = ejecutarConsulta($sql);
		return $query->fetch_assoc();
	}

	public function ventacabeceraxml($id_factura)
	{
		$sql = "SELECT f.id_factura,f.idcliente,p.nombre as cliente,f.idmotivo_doc,f.doc_relacionado,p.direccion,p.tipo_documento,p.num_documento,p.email,f.hora,p.telefono,f.idusuario,u.nombre as usuario,f.codigotipo_comprobante,f.serie,f.correlativo,date(f.fecha_hora) as fecha,f.fecha_hora as fechaCompleta,f.fecha_ven,m.codigo,f.impuesto,f.op_gravadas,f.op_inafectas,f.op_exoneradas,f.total_venta,f.igv_total,f.igv_asig,f.idmoneda,m.descripcion as descmoneda, tp.descripcion_tipo_pago as codigo_pago, f.codigotipo_pago FROM factura f INNER JOIN persona p ON f.idcliente=p.idpersona INNER JOIN usuario u ON f.idusuario=u.idusuario INNER JOIN moneda m ON m.idmoneda=f.idmoneda LEFT JOIN tipo_pago tp ON f.codigotipo_pago = tp.codigotipo_pago WHERE f.id_factura='$id_factura'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function ventadetallexml($id_factura)
	{
		$sql = "SELECT df.descripcion_prod as servicio,df.unidad_medida,df.cantidad,df.precio_venta,(df.cantidad*df.precio_venta) as subtotal, df.codigo_prod as codigoServ, df.descuento,f.Tipo FROM detalle_factura df INNER JOIN factura f on df.id_factura= f.id_factura WHERE df.id_factura='$id_factura'";
		$result = ejecutarConsulta($sql);
		if ($result && $result->num_rows > 0) {
			$detalle = $result->fetch_all(MYSQLI_ASSOC);
			return $detalle;
		} else {
			return array();
		}
	}
	public function codigoSerie()
	{
		$sql = "SELECT MAX(codigo_prod) AS maximo_codigo_prod FROM detalle_factura";
		return ejecutarConsulta($sql);
	}

	public function venta_documento_relacionado_xml($id_factura)
	{
		$sql = "SELECT f.id_factura, f.serie, f.correlativo, tc.codigotipo_comprobante,f.sustento from factura f 
		INNER JOIN tipo_comprobante tc ON f.codigotipo_comprobante = tc.codigotipo_comprobante
		WHERE f.id_factura = '$id_factura'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function tipo_ncredito($id_motivo_doc)
	{
		$sql = "SELECT codigo_motivo, motivo, descripcion FROM motivo_documento WHERE idmotivo_documento = '$id_motivo_doc'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function guardarRptaSunat($id_factura, $rpta)
	{
		$conexion = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		mysqli_query($conexion, 'SET NAMES "' . DB_ENCODE . '"');
		$codigo = mysqli_real_escape_string($conexion, $rpta['respuesta_sunat_codigo']);
		$descripcion = mysqli_real_escape_string($conexion, $rpta['respuesta_sunat_descripcion']);
		$sql = "UPDATE factura SET rpta_sunat_codigo = '$codigo', rpta_sunat_descripcion = '$descripcion' WHERE id_factura = '$id_factura'";
		return ejecutarConsulta($sql);
	}

	function registrar_errores_sunat($codigo, $id_factura)
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
		$this->guardarRptaSunat($id_factura, $datos_rpta);
	}

	public function priceAmount($precio_base, $codigo_de_tributo, $percent, $icbper, $descuento = 0)
	{
		$precio_base = floatval($precio_base);
		$codigo_de_tributo = floatval($codigo_de_tributo);
		$percent = $percent / 100;
		$icbper = floatval($icbper);
		$priceAmount = '';
		if ($codigo_de_tributo == 1000) {
			$priceAmount = $precio_base;
		} else {
			$priceAmount = $precio_base - $descuento + $icbper;
		}
		return $priceAmount;
	}

	public function taxAmount($cantidad, $precio_base, $codigo_de_tributo, $percent, $descuento = 0, $Tipo)
	{
		$taxAmount = '';
		if ($Tipo == 'Con_IGV') {
			$precio_base = $precio_base / (1 + ($percent / 100));
		} else {
			$precio_base = $precio_base;
		}
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

	public function price_priceAmount($precio_base, $codigo_de_tributo, $Tipo)
	{
		if ($Tipo == 'Con_IGV') {
			$precio_base = $precio_base / (1 + (18 / 100));
		} else {
			$precio_base = $precio_base;
		}
		$precio_base = number_format($precio_base, 2, '.', '');
		$price_priceAmount = ($codigo_de_tributo == 9996) ? 0.0 : $precio_base;
		return $price_priceAmount;
	}

	public function perfil_sunat()
	{
		$sql = "SELECT * FROM perfil_sunat";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetalle($id_factura)
	{
		$sql = "SELECT dv.id_factura,dv.codigo_prod,dv.descripcion_prod,dv.unidad_medida,dv.cantidad,dv.precio_venta,(dv.cantidad*dv.precio_venta) as importe,(dv.cantidad*dv.precio_venta)/1.18 as subtotal,(dv.cantidad*dv.precio_venta)/1.18*0.18 as igvt,v.op_gravadas,v.igv_total,v.total_venta FROM detalle_factura dv inner join factura v on v.id_factura=dv.id_factura where dv.id_factura='$id_factura'";
		return ejecutarConsulta($sql);
	}

	public function cabecera_perfil()
	{
		$sql = "SELECT * FROM perfil";
		return ejecutarConsultaSimpleFila($sql);
	}


	public function ventacabecera($id_factura)
	{
		$sql = "SELECT f.id_factura,f.idcliente,p.nombre as cliente,p.direccion,p.tipo_documento,p.num_documento,p.email,f.hora,p.telefono,f.idusuario,u.nombre as usuario,f.codigotipo_comprobante,f.serie,f.correlativo,date(f.fecha_hora) as fecha,f.fecha_hora as fechaCompleta,f.fecha_ven,m.codigo,f.impuesto,f.op_gravadas,f.op_inafectas,f.op_exoneradas,f.op_gratuitas,f.isc,f.total_venta,f.igv_total,f.igv_asig,f.idmoneda,m.descripcion as descmoneda, tp.descripcion_tipo_pago as codigo_pago, f.codigotipo_pago FROM factura f INNER JOIN persona p ON f.idcliente=p.idpersona INNER JOIN usuario u ON f.idusuario=u.idusuario INNER JOIN moneda m ON m.idmoneda=f.idmoneda LEFT JOIN tipo_pago tp ON f.codigotipo_pago = tp.codigotipo_pago WHERE f.id_factura='$id_factura'";
		return ejecutarConsulta($sql);
	}

	public function ventadetalle($id_factura)
	{
		$sql = "SELECT df.descripcion_prod as servicio,df.unidad_medida,df.cantidad,df.precio_venta,df.descuento,df.serie,(df.cantidad*df.precio_venta-df.descuento) as subtotal, df.codigo_prod as codigoServ, df.descuento FROM detalle_factura df WHERE df.id_factura='$id_factura'";
		return ejecutarConsulta($sql);
	}

	public function selectTipoPago()
	{
		$sql = "SELECT * from tipo_pago";
		return ejecutarConsulta($sql);
	}

	public function selectTipoComprobante()
	{
		$sql = "SELECT * from tipo_comprobante WHERE codigotipo_comprobante in (1,3) order by codigotipo_comprobante desc";
		return ejecutarConsulta($sql);
	}

	public function selecTipoIGV()
	{
		$sql = "SELECT * FROM `igv`";
		return ejecutarConsulta($sql);
	}


	public function facturadetalle($id_factura)
	{
		$sql = "SELECT * FROM detalle_factura WHERE id_factura='$id_factura'";
		return ejecutarConsulta($sql);
	}
	public function selectMoneda()
	{
		$sql = "SELECT * FROM moneda";
		return ejecutarConsulta($sql);
	}

	public function listarTipoNotaCredito()
	{
		$sql = "SELECT * FROM motivo_documento where idmotivo_documento in (1,2,6,7) ";
		return ejecutarConsulta($sql);
	}
}
