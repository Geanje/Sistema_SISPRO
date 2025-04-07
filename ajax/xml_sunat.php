<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../modelos/Venta2.php';

class XMLSunat extends Venta2
{ //herencia
    public function __construct()
    {
        parent::__construct();
    }

    private function crear_xml($idventa)
    {
        $venta_relacionado = '';
        $motivo_nc = '';
        $motivo_nd = '';

        $cuotas = '';
        $anticipos = '';

        $venta = $this->ventacabeceraxml($idventa);
        if ($venta['codigotipo_comprobante'] == 7 || $venta['codigotipo_comprobante'] == 8) {
            $detalle = $this->ventadetallexml($venta['doc_relacionado']);
        } else {
            $detalle = $this->ventadetallexml($idventa);
        }

        $empresa = $this->perfil_sunat();
        if ((($venta['codigotipo_comprobante'] == 7) || ($venta['codigotipo_comprobante'] == 8)) && ($venta['codigotipo_comprobante'] != null)) {
            $venta_relacionado = $this->venta_documento_relacionado_xml($venta['doc_relacionado']);

            if ($venta['codigotipo_comprobante'] == 7) {
                $motivo_nc = $this->tipo_ncredito($venta['idmotivo_doc']);
            }
            if ($venta['codigotipo_comprobante'] == 8) {
                $motivo_nd = $this->tipo_ncredito($venta['idmotivo_doc']);
            }
        }

        $xml = $this->desarrollo_xml($empresa, $venta, $detalle, $venta_relacionado, $motivo_nc, $motivo_nd, $cuotas, $anticipos);
        $nombre_archivo = $empresa['ruc'] . '-' . '0' . $venta['codigotipo_comprobante'] . '-' . $venta['serie'] . '-' . $venta['correlativo'];

        $nombre = "../files/facturacion_electronica/XML/" . $nombre_archivo . ".xml";
        $archivo = fopen($nombre, "w+");
        fwrite($archivo, utf8_decode($xml));
        fclose($archivo);
        return array(
            'nombre_archivo' => $nombre_archivo,
            'modo'          => $empresa['modo'],
            'idventa'      => $idventa,
            'empresa'       => $empresa
        );
    }

    public function firmar_xml($name_file, $entorno, $baja = '')
    {
        $carpeta_baja = ($baja != '') ? 'BAJA/' : '';
        $carpeta = "files/facturacion_electronica/$carpeta_baja";
        $dir = '../' . $carpeta . "XML/" . $name_file;
        $xmlstr = file_get_contents($dir);
        require_once '../public/librerias/efactura.php';
        $domDocument = new \DOMDocument();
        $domDocument->loadXML($xmlstr);
        $factura  = new Facturaxml();
        $xml = $factura->firmar($domDocument, $entorno, '');
        $content = $xml->saveXML();
        file_put_contents("../" . $carpeta . "FIRMA/" . $name_file, $content);
    }

    public function enviar_sunat($nombre_archivo, $idventa)
    {
        //en caso ya esté creado el xml, se enviará directo a la ws
        // $nombre_archivo = $this->uri->segment(4);
        $ruta_xml = "../files/facturacion_electronica/FIRMA/" . $nombre_archivo . ".xml";
        if (file_exists($ruta_xml)) {
            // echo "entro al if: ".$nombre_archivo;
            // exit();
            unlink($ruta_xml);
            // $venta_id = $this->uri->segment(3);                        

            $rpta = $this->crear_xml($idventa);
            $this->firmar_xml($rpta['nombre_archivo'] . ".xml", $rpta['modo']);

            $empresa = $this->perfil_sunat();
            $venta = $this->ventacabeceraxml($idventa);
            $this->ws_sunat($idventa, $empresa, $nombre_archivo);
        } else {
            // echo "entro al else: ".$nombre_archivo;
            $rpta = $this->crear_xml($idventa);
            // echo 'el nombre del archivo es:'.$rpta['nombre_archivo'].'el modo es:'.$rpta['modo'];
            // exit();
            $this->firmar_xml($rpta['nombre_archivo'] . ".xml", $rpta['modo']);
            $this->ws_sunat($rpta['idventa'], $rpta['empresa'], $rpta['nombre_archivo']);
        }
    }

    public function ws_sunat($idventa, $empresa, $nombre_archivo)
    {
        //enviar a Sunat
        //cod_1: Select web Service: 1 factura, boletas --- 9 es para guias
        //cod_2: Entorno:  0 Beta, 1 Produccion
        //cod_3: ruc
        //cod_4: usuario secundario USU(segun seha beta o producción)
        //cod_5: usuario secundario PASSWORD(segun seha beta o producción)
        //cod_6: Accion:   1 enviar documento a Sunat --  2 enviar a anular  --  3 enviar ticket
        //cod_7: serie de documento
        //cod_8: numero ticket
        $user_sec_usu = ($empresa['modo'] == 1) ? $empresa['u_secundario_user'] : $empresa['u_secundario_user'];
        $user_sec_pass = ($empresa['modo'] == 1) ? $empresa['u_secundario_password'] : $empresa['u_secundario_password'];

        // $nombre_archivo = urlencode($nombre_archivo);
        // $empresa['modo'] = urlencode($empresa['modo']);
        // $empresa['ruc'] = urlencode($empresa['ruc']);
        // $user_sec_usu = urlencode($user_sec_usu);
        // $user_sec_pass = urlencode($user_sec_pass);

        // $url = "C:/laragon/www/constructor/ws_sunat/index.php?numero_documento=" . urlencode($nombre_archivo) . "&cod_1=1&cod_2=" . urlencode($empresa['modo']) . "&cod_3=" . urlencode($empresa['ruc']) . "&cod_4=" . urlencode($user_sec_usu) . "&cod_5=" . urlencode($user_sec_pass) . "&cod_6=1";

        // $data = file_get_contents($url);
        // $info = json_decode($data, TRUE);

        $url = "../ws_sunat/index.php";

        // $params = array(
        //     'numero_documento' => $nombre_archivo,
        //     'cod_1' => 1,
        //     'cod_2' => $empresa['modo'],
        //     'cod_3' => $empresa['ruc'],
        //     'cod_4' => $user_sec_usu,
        //     'cod_5' => $user_sec_pass,
        //     'cod_6' => 1,
        //     // ... otros parámetros si es necesario
        // );
        $cod_1 = '1';
        $cod_2 = $empresa['modo'];
        $cod_3 = $empresa['ruc'];
        $cod_4 = $user_sec_usu;
        $cod_5 = $user_sec_pass;
        $cod_6 = '1';
        $numero_documento = $nombre_archivo;
        // var_dump($params);
        // exit;

        // Incluye el script local
        include $url;
        $result =  procesarSunat($cod_1, $cod_2, $cod_3, $cod_4, $cod_5, $cod_6, $numero_documento);
        // var_dump($result);
        // exit;
        $respuesta_codigo = '';
        $respuesta_mensaje = '';
        // echo $result['error_existe'];
        // echo "aqui estoy";
        // exit;

        if ($result['error_existe'] == 0) {
            // echo "si entro en el if";
            // exit;
            $respuesta_sunat = $this->leerRespuestaSunat($result['numero_documento'] . ".xml");
            // var_dump($respuesta_sunat['respuesta_sunat_codigo']);
            // exit;
            if ($respuesta_sunat != null) {
                $this->guardarRptaSunat($idventa, $respuesta_sunat);
                $this->registrar_errores_sunat($respuesta_sunat['respuesta_sunat_codigo'], $idventa);
            }
            //var_dump($respuesta_sunat);
            $respuesta_mensaje = ($respuesta_sunat != null) ? $respuesta_sunat['respuesta_sunat_descripcion'] : '';
            $respuesta_codigo = ($respuesta_sunat != null) ? $respuesta_sunat['respuesta_sunat_codigo'] : '';
        }
        $jsondata = array(
            'success' => true,
            'codigo' => $respuesta_codigo,
            'error_existe' => $result['error_existe'],
            'message' => $respuesta_mensaje . $result['error_mensaje']
        );
        echo json_encode($jsondata['message'], JSON_UNESCAPED_UNICODE);
    }


    function desarrollo_xml($empresa, $venta, $detalles, $venta_relacionado, $motivo_nc, $motivo_nd, $cuotas, $anticipos = array())
    {

        // $totalVenta = explode(".",  $venta['total_a_pagar']);
        $totalVenta = explode(".",  $venta['total_venta']);
        if ($totalVenta[0] == 0) {
            $venta['total_letras'] = '0 ' . $venta['descmoneda'];
        } else {
            require_once '../reportes/numeroALetras.php';
            $num = new NumeroALetras();
            $totalLetras = $num->num2letras($totalVenta[0]);
            $venta['total_letras'] = $totalLetras . ' con ' . $totalVenta[1] . '/100 ' . $venta['descmoneda'];

            // require_once "../reportes/numeroALetras.php";
            // $letras = NumeroALetras::convertir($totalVenta[0]);
            // list($num,$cen)=explode('.',$total_importe);
            // $leyenda = $letras.'Y '.$cen.'/100 SOLES';
        }

        $linea_inicio   = '';
        $linea_fin   = '';
        $tag_total_pago = '';
        $dato_nc = '';
        // cuando la operacion es una venta interna es 0101 para otro tipo de operaciones revisar el manual xd
        $venta['tipo_operacion'] = 0101;
        switch ($venta['codigotipo_comprobante']) {
            case '1':
                $linea_inicio   = '<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                $linea_fin   = 'Invoice';
                $InvoiceTypeCode = '<cbc:InvoiceTypeCode listID="0101" listAgencyName="PE:SUNAT" listName="Tipo de Documento" listSchemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo51" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01" name="Tipo de Operacion">' . "0" . $venta['codigotipo_comprobante'] . '</cbc:InvoiceTypeCode>';
                $tag_total_pago = 'LegalMonetaryTotal';
                break;

            case '3':
                $linea_inicio   = '<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                $linea_fin   = 'Invoice';
                $InvoiceTypeCode = '<cbc:InvoiceTypeCode listID="0101" listAgencyName="PE:SUNAT" listName="Tipo de Documento" listSchemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo51" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01" name="Tipo de Operacion">' . "0" . $venta['codigotipo_comprobante'] . '</cbc:InvoiceTypeCode>';
                $tag_total_pago = 'LegalMonetaryTotal';
                break;

            case '7':
                $linea_inicio   = '<CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">';
                $linea_fin   = 'CreditNote';
                $InvoiceTypeCode = '';

                $dato_nc = '<cac:DiscrepancyResponse>
                <cbc:ReferenceID>' . $venta_relacionado['serie'] . '-' . $venta_relacionado['correlativo'] . '</cbc:ReferenceID>
                <cbc:ResponseCode>' . $motivo_nc['codigo_motivo'] . '</cbc:ResponseCode>
                <cbc:Description>' . $motivo_nc['motivo'] . '</cbc:Description>
            </cac:DiscrepancyResponse>
            <cac:BillingReference>
                <cac:InvoiceDocumentReference>
                    <cbc:ID>' . $venta_relacionado['serie'] . '-' . $venta_relacionado['correlativo'] . '</cbc:ID>
                    <cbc:DocumentTypeCode>' . "0" . $venta_relacionado['codigotipo_comprobante'] . '</cbc:DocumentTypeCode>
                </cac:InvoiceDocumentReference>
            </cac:BillingReference>';
                $tag_total_pago = 'LegalMonetaryTotal';
                break;

            case '8':
                $linea_inicio   = '<DebitNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:DebitNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                $linea_fin   = 'DebitNote';
                $InvoiceTypeCode = '';
                $dato_nc = '<cac:DiscrepancyResponse>
                <cbc:ReferenceID>' . $venta_relacionado['serie'] . '-' . $venta_relacionado['numero'] . '</cbc:ReferenceID>
                <cbc:ResponseCode>' . $motivo_nd['codigo'] . '</cbc:ResponseCode>
                <cbc:Description>' . $motivo_nd['tipo_ndebito'] . '</cbc:Description>
            </cac:DiscrepancyResponse>
            <cac:BillingReference>
                <cac:InvoiceDocumentReference>
                    <cbc:ID>' . $venta_relacionado['serie'] . '-' . $venta_relacionado['numero'] . '</cbc:ID>
                    <cbc:DocumentTypeCode>' . $venta_relacionado['codigo'] . '</cbc:DocumentTypeCode>
                </cac:InvoiceDocumentReference>
            </cac:BillingReference>';
                $tag_total_pago = 'RequestedMonetaryTotal';
                break;
        }
        // $venta['correlativo'] = ltrim($venta['correlativo'], '0');

        $xml =  '<?xml version="1.0" encoding="ISO-8859-1" standalone="no"?>' . $linea_inicio . '<ext:UBLExtensions>
                        <ext:UBLExtension>
                            <ext:ExtensionContent></ext:ExtensionContent>
                        </ext:UBLExtension>
                    </ext:UBLExtensions>
                    <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
                    <cbc:CustomizationID>2.0</cbc:CustomizationID>
                    <cbc:ID>' . $venta['serie'] . '-' . $venta['correlativo'] . '</cbc:ID>
                    <cbc:IssueDate>' . $venta['fechaCompleta'] . '</cbc:IssueDate>
                    <cbc:IssueTime>' . $venta['hora'] . '</cbc:IssueTime>';
        // if (($venta['fecha_ven'] != null) && (($venta['codigotipo_comprobante'] == 1) || ($venta['codigotipo_comprobante'] == 3))) {
        //     $xml .= '<cbc:DueDate>' . $venta['fecha_ven'] . '</cbc:DueDate>';
        // };
        $xml .= $InvoiceTypeCode . '<cbc:Note languageLocaleID="1000"><![CDATA[SON:' . $venta['total_letras'] . ']]></cbc:Note>
                <cbc:DocumentCurrencyCode listID="ISO 4217 Alpha" listName="Currency" listAgencyName="United Nations Economic Commission for Europe">' . $venta['codigo'] . '</cbc:DocumentCurrencyCode>' . $dato_nc;

        //--Para anticipos INICIO--
        // $ij = 1;
        // foreach ($anticipos as $value_anticipos){
        //     //codigo documento relacionado (Catalogo 12 Sunat)
        //     if(substr($value_anticipos['serie'], 0, 1) == 'F'){
        //         $codigo_documento_relacionado = '02';
        //     }elseif(substr($value_anticipos['serie'], 0, 1) == 'B'){
        //         $codigo_documento_relacionado = '03';
        //     }
        //     $xml .= '<cac:AdditionalDocumentReference>
        //             <cbc:ID schemeID="01">'.$value_anticipos['serie'].'-'.$value_anticipos['numero'].'</cbc:ID>
        //             <cbc:DocumentTypeCode listAgencyName="PE:SUNAT" listName="Documento Relacionado" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo12">'.$codigo_documento_relacionado.'</cbc:DocumentTypeCode>
        //             <cbc:DocumentType>ANTICIPO</cbc:DocumentType>
        //             <cbc:DocumentStatusCode listAgencyName="PE:SUNAT" listName="Anticipo">'.$ij.'</cbc:DocumentStatusCode>
        //             <cac:IssuerParty>
        //                     <cac:PartyIdentification>
        //                             <cbc:ID schemeAgencyName="PE:SUNAT" schemeID="'.$venta['codigo_tipo_entidad'].'" schemeName="Documento de Identidad" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$venta['numero_documento'].'</cbc:ID>
        //                     </cac:PartyIdentification>
        //             </cac:IssuerParty>
        //         </cac:AdditionalDocumentReference>';
        //     $ij ++;
        // }
        //--Para anticipos FIN--

        /////--Orden de compra  -- INICIO
        // if(($venta['orden_compra'] != null) && (($venta['codigotipo_comprobante'] == 1) || ($venta['codigotipo_comprobante'] == 3))) {
        //         $xml .= '<cac:OrderReference>
        //         <cbc:ID>'.$venta['orden_compra'].'</cbc:ID>
        //         </cac:OrderReference>';
        // };
        /////--Orden de compra  -- FIN

        $xml .= '<cac:Signature>
                            <cbc:ID>' . $empresa['ruc'] . '</cbc:ID>
                            <cac:SignatoryParty>
                                <cac:PartyName>
                                    <cbc:Name>SUNAT</cbc:Name>
                                </cac:PartyName>
                            </cac:SignatoryParty>
                            <cac:DigitalSignatureAttachment>
                                <cac:ExternalReference>
                                    <cbc:URI>' . $empresa['ruc'] . '</cbc:URI>
                                </cac:ExternalReference>
                            </cac:DigitalSignatureAttachment>
                        </cac:Signature>                        
                        <cac:AccountingSupplierParty>
                            <cac:Party>
                                <cac:PartyIdentification>
                                    <cbc:ID schemeAgencyName="PE:SUNAT" schemeID="6" schemeName="Documento de Identidad" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $empresa['ruc'] . '</cbc:ID>
                                </cac:PartyIdentification>
                                <cac:PartyName>
                                    <cbc:Name><![CDATA[' . $empresa['nombre_comercial'] . ']]></cbc:Name>                                    
                                </cac:PartyName>
                                <cac:PartyLegalEntity>
                                    <cbc:RegistrationName><![CDATA[' . $empresa['razon_social'] . ']]></cbc:RegistrationName>
                                    <cac:RegistrationAddress>
                                        <cbc:AddressTypeCode listAgencyName="PE:SUNAT" listName="Establecimientos anexos">0000</cbc:AddressTypeCode>
                                        <cbc:CityName><![CDATA[' . $empresa['provincia'] . ']]></cbc:CityName>
                                        <cbc:CountrySubentity><![CDATA[' . $empresa['departamento'] . ']]></cbc:CountrySubentity>
                                        <cbc:District><![CDATA[' . $empresa['distrito'] . ']]></cbc:District>
                                        <cac:AddressLine>
                                            <cbc:Line><![CDATA[' . $empresa['direccion'] . ']]></cbc:Line>
                                        </cac:AddressLine>
                                        <cac:Country>
                                            <cbc:IdentificationCode listID="ISO 3166-1" listAgencyName="United Nations Economic Commission for Europe" listName="Country">PE</cbc:IdentificationCode>
                                        </cac:Country>
                                    </cac:RegistrationAddress>
                                </cac:PartyLegalEntity>
                            </cac:Party>
                        </cac:AccountingSupplierParty>                        
                        <cac:AccountingCustomerParty>
                            <cac:Party>
                                <cac:PartyIdentification>
                                    <cbc:ID schemeID="6" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $venta['num_documento'] . '</cbc:ID>
                                </cac:PartyIdentification>
                                <cac:PartyLegalEntity>
                                    <cbc:RegistrationName><![CDATA[' . $venta['cliente'] . ']]></cbc:RegistrationName>
                                </cac:PartyLegalEntity>
                            </cac:Party>
                        </cac:AccountingCustomerParty>';


        ////////////////////////////////////////INICO - DETRACCION
        // if(($venta['detraccion_porcentaje'] != '') && ($venta['detraccion_porcentaje'] != null) && ($venta['detraccion_porcentaje'] > 0)){
        //     $xml .= '<cac:PaymentTerms>
        //             <cbc:ID schemeName="SUNAT:Codigo de detraccion" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo54">'.$venta['detraccion_codigo'].'</cbc:ID>
        //             <cbc:PaymentPercent>'.$venta['detraccion_porcentaje'].'</cbc:PaymentPercent>
        //             <cbc:Amount currencyID="PEN">'.$venta['total_a_pagar']*$venta['detraccion_porcentaje']*(0.01).'</cbc:Amount>
        //         </cac:PaymentTerms>';
        // }                        
        ////////////////////////////////////////FIN - DETRACCION


        /////////////Forma de pago  --  INICIO   - solo para facturas y boletas.
        if (($venta['codigotipo_comprobante'] == 1) || ($venta['codigotipo_comprobante'] == 3) || (($venta['codigotipo_comprobante'] == 7) && $venta['codigotipo_comprobante'] == 13)) {
            if ($venta['codigotipo_pago'] == 1) {
                $xml .= '<cac:PaymentTerms>
                                <cbc:ID>FormaPago</cbc:ID>
                                <cbc:PaymentMeansID>Contado</cbc:PaymentMeansID>
                            </cac:PaymentTerms>';
            }
            if ($venta['codigotipo_pago'] == 2) {
                $total_pagar_credito = ($venta['codigotipo_comprobante'] == '13') ? number_format($cuotas[0]['monto'], 2, '.', '') : number_format($venta['total_venta'], 2, '.', '');

                $xml .= '<cac:PaymentTerms>
                            <cbc:ID>FormaPago</cbc:ID>
                            <cbc:PaymentMeansID>Credito</cbc:PaymentMeansID>
                            <cbc:Amount currencyID="' . $venta['codigo'] . '">' . $total_pagar_credito . '</cbc:Amount>
                        </cac:PaymentTerms>';

                $contar_cuota = 1;
                foreach ($cuotas as $value_cuotas) {
                    $xml .= '<cac:PaymentTerms>
                                    <cbc:ID>FormaPago</cbc:ID>
                                    <cbc:PaymentMeansID>Cuota00' . $contar_cuota . '</cbc:PaymentMeansID>
                                    <cbc:Amount currencyID="' . $venta['abrstandar'] . '">' . number_format($value_cuotas['monto'], 2, '.', '') . '</cbc:Amount>
                                    <cbc:PaymentDueDate>' . $value_cuotas['fecha_cuota'] . '</cbc:PaymentDueDate>
                                </cac:PaymentTerms>';
                    $contar_cuota++;
                }
            }
        }

        /////////////Forma de pago  --  FIN 


        //--Para anticipos INICIO--
        // $ij = 1;
        // foreach ($anticipos as $value_anticipos){
        //     $xml .= '<cac:PrepaidPayment>
        //                 <cbc:ID schemeAgencyName="PE:SUNAT" schemeName="Anticipo">'.$ij.'</cbc:ID>
        //                 <cbc:PaidAmount currencyID="'.$venta['abrstandar'].'">'.$value_anticipos['total_a_pagar'].'</cbc:PaidAmount>
        //             </cac:PrepaidPayment>';                
        // }                        
        //--Para anticipos FIN-- 

        // if( count($anticipos) > 0){
        //     $xml .= '<cac:AllowanceCharge>
        //     <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
        //     <cbc:AllowanceChargeReasonCode listAgencyName="PE:SUNAT" listName="Cargo/descuento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo53">04</cbc:AllowanceChargeReasonCode>
        //     <cbc:Amount currencyID="PEN">'.($value_anticipos['total_a_pagar'] - $value_anticipos['total_igv']).'</cbc:Amount>
        //     <cbc:BaseAmount currencyID="PEN">'.($value_anticipos['total_a_pagar'] - $value_anticipos['total_igv'] + $venta['total_a_pagar'] - $venta['total_igv']).'</cbc:BaseAmount>
        //     </cac:AllowanceCharge>';
        // }
        // $anticipo_igv = isset($value_anticipos['total_igv']) ? $value_anticipos['total_igv'] : 0;
        // $anticipo_grabada = isset($value_anticipos['total_igv']) ? ($value_anticipos['total_a_pagar'] - $value_anticipos['total_igv']) : 0;
        // $anticipo_total = isset($value_anticipos['total_a_pagar']) ? $value_anticipos['total_a_pagar'] : 0;

        //////////////Descuentos Globales  -- INICIO
        // if( ($venta['total_descuentos'] != null) && ($venta['total_descuentos'] > 0)){
        // $codigo_cargos = ( $venta['total_igv'] > 0 ) ? '02' : '03';
        // $xml .= '<cac:AllowanceCharge>
        //     <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
        //     <cbc:AllowanceChargeReasonCode listAgencyName="PE:SUNAT" listName="Cargo/descuento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo53">'.$codigo_cargos.'</cbc:AllowanceChargeReasonCode>
        //     <cbc:MultiplierFactorNumeric>' . number_format(( $venta['total_descuentos'] / ($venta['total_gravada'] + $venta['total_descuentos'])), 5,'.', ''). '</cbc:MultiplierFactorNumeric>
        //     <cbc:Amount currencyID="'.$venta['abrstandar'].'">' . $venta['total_descuentos'] . '</cbc:Amount>
        //     <cbc:BaseAmount currencyID="'.$venta['abrstandar'].'">' . number_format(($venta['total_gravada'] + $venta['total_descuentos']), 2,'.', ''). '</cbc:BaseAmount>
        // </cac:AllowanceCharge>';
        // }                        
        //////////////Descuentos Globales -- FIN

        //////////////Descuentos ITEM  -- INICIO
        $suma_descuento_lineal = 0;
        $tipo_igv_id_item = 1; //suponemos q por defecto será con IGV. igual luego se reemplazará en el caso tenga descuento por Item
        // var_dump($detalles);
        // exit;
        foreach ($detalles as $datos_descuento) {
            $suma_descuento_lineal += $datos_descuento['descuento'];
            // $tipo_igv_id_item = $datos_descuento['tipo_igv_id'];
            $tipo_igv_id_item = 1;
        }

        if ($suma_descuento_lineal > 0) {
            $codigo_cargos = ($tipo_igv_id_item == 1) ? '00' : '01';
            $xml .= '<cac:AllowanceCharge>
                    <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
                    <cbc:AllowanceChargeReasonCode listAgencyName="PE:SUNAT" listName="Cargo/descuento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo53">' . $codigo_cargos . '</cbc:AllowanceChargeReasonCode>
                    <cbc:MultiplierFactorNumeric>' . number_format(($suma_descuento_lineal / ($venta['op_gravadas'] + $suma_descuento_lineal)), 5, '.', '') . '</cbc:MultiplierFactorNumeric>
                    <cbc:Amount currencyID="' . $venta['codigo'] . '">' . $suma_descuento_lineal . '</cbc:Amount>
                    <cbc:BaseAmount currencyID="' . $venta['codigo'] . '">' . number_format(($venta['op_gravadas'] + $suma_descuento_lineal), 2, '.', '') . '</cbc:BaseAmount>
                </cac:AllowanceCharge>';
        }
        //////////////Descuentos ITEM -- FIN                

        ////////////////////////////////////////PERCENCIÓN            
        //     if(($venta['retencion_porcentaje'] != '') && ($venta['retencion_porcentaje'] != null) && ($venta['retencion_porcentaje'] > 0)){                            
        //     $xml .= '<cac:AllowanceCharge> 
        // <cbc:ChargeIndicator>false</cbc:ChargeIndicator> 
        // <cbc:AllowanceChargeReasonCode listAgencyName="PE:SUNAT" listName="Cargo/descuento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo53">62</cbc:AllowanceChargeReasonCode> 
        // <cbc:MultiplierFactorNumeric>' . $venta['retencion_porcentaje'] * (0.01) . '</cbc:MultiplierFactorNumeric>
        // <cbc:Amount currencyID="PEN">' . $venta['total_a_pagar'] * $venta['retencion_porcentaje'] * (0.01) . '</cbc:Amount> 
        // <cbc:BaseAmount currencyID="PEN">' . $venta['total_a_pagar'] . '</cbc:BaseAmount>  
        //     </cac:AllowanceCharge>';
        //     }
        ////////////////////////////////////////            

        // $total_igv = ($venta['total_igv'] != null) ? ($venta['total_igv'] - $anticipo_igv) : 0.0;
        $total_igv = ($venta['total_igv'] != null) ? ($venta['total_igv'] - 0) : 0.0;
        // $total_igv = 0.0;
        $xml .=  '<cac:TaxTotal>
                            <cbc:TaxAmount currencyID="' . $venta['codigo'] . '">' . $total_igv . '</cbc:TaxAmount>';

        if ($venta['op_gravadas'] != null) {
            $xml .=  '<cac:TaxSubtotal>
                                <cbc:TaxableAmount currencyID="' . $venta['codigo'] . '">' . ($venta['op_gravadas'] - 0) . '</cbc:TaxableAmount>
                                <cbc:TaxAmount currencyID="' . $venta['codigo'] . '">' . $total_igv . '</cbc:TaxAmount>
                                <cac:TaxCategory>
                                    <cac:TaxScheme>
                                        <cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">1000</cbc:ID>
                                        <cbc:Name>IGV</cbc:Name>
                                        <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                                    </cac:TaxScheme>
                                </cac:TaxCategory>
                            </cac:TaxSubtotal>';
        };

        // if($venta['total_exportacion'] != null){                                            
        // $xml .=  '<cac:TaxSubtotal>
        //             <cbc:TaxableAmount currencyID="'.$venta['abrstandar'].'">' . $venta['total_exportacion'] . '</cbc:TaxableAmount>
        //             <cbc:TaxAmount currencyID="'.$venta['abrstandar'].'">0.00</cbc:TaxAmount>
        //             <cac:TaxCategory>
        //                 <cac:TaxScheme>
        //                     <cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">9995</cbc:ID>
        //                     <cbc:Name>EXP</cbc:Name>
        //                     <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
        //                 </cac:TaxScheme>
        //             </cac:TaxCategory>
        //         </cac:TaxSubtotal>';
        // };                    
        // if($venta['op_gratuitas'] != null){                                            
        // $xml .=  '<cac:TaxSubtotal>
        //             <cbc:TaxableAmount currencyID="'.$venta['codigo'].'">' . $venta['op_gratuitas'] . '</cbc:TaxableAmount>
        //             <cbc:TaxAmount currencyID="'.$venta['codigo'].'">0.00</cbc:TaxAmount>
        //             <cac:TaxCategory>
        //                 <cac:TaxScheme>
        //                     <cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">9996</cbc:ID>
        //                     <cbc:Name>GRA</cbc:Name>
        //                     <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
        //                 </cac:TaxScheme>
        //             </cac:TaxCategory>
        //         </cac:TaxSubtotal>';
        // };                    
        // if($venta['op_exoneradas'] != null){                                            
        // $xml .=  '<cac:TaxSubtotal>
        //             <cbc:TaxableAmount currencyID="'.$venta['codigo'].'">' . $venta['op_exoneradas'] . '</cbc:TaxableAmount>
        //             <cbc:TaxAmount currencyID="'.$venta['codigo'].'">0.00</cbc:TaxAmount>
        //             <cac:TaxCategory>
        //                 <cac:TaxScheme>
        //                     <cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">9997</cbc:ID>
        //                     <cbc:Name>EXO</cbc:Name>
        //                     <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
        //                 </cac:TaxScheme>
        //             </cac:TaxCategory>
        //         </cac:TaxSubtotal>';
        // };                    
        // if($venta['op_inafectas'] != null){                                            
        // $xml .=  '<cac:TaxSubtotal>
        //             <cbc:TaxableAmount currencyID="'.$venta['codigo'].'">' . $venta['op_inafectas'] . '</cbc:TaxableAmount>
        //             <cbc:TaxAmount currencyID="'.$venta['codigo'].'">0.00</cbc:TaxAmount>
        //             <cac:TaxCategory>
        //                 <cac:TaxScheme>
        //                     <cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">9998</cbc:ID>
        //                     <cbc:Name>INA</cbc:Name>
        //                     <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
        //                 </cac:TaxScheme>
        //             </cac:TaxCategory>
        //         </cac:TaxSubtotal>';
        // };                    
        // if($venta['total_bolsa'] != null){                                            
        // $xml .=  '<cac:TaxSubtotal>              
        //             <cbc:TaxAmount currencyID="'.$venta['abrstandar'].'">' . $venta['total_bolsa'] . '</cbc:TaxAmount>
        //             <cac:TaxCategory>       				  
        //                 <cac:TaxScheme>
        //                     <cbc:ID schemeAgencyName="PE:SUNAT" schemeName="Codigo de tributos" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">7152</cbc:ID>
        //                     <!-- ICBPER: Nombre de ICBPER -->
        //                     <cbc:Name>ICBPER</cbc:Name>
        //                     <!-- ICBPER: Codigo Internacional de ICBPER -->
        //                     <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
        //                 </cac:TaxScheme>
        //             </cac:TaxCategory>
        //         </cac:TaxSubtotal>';
        // };                      
        $total_gravada      = ($venta['op_gravadas'] == null)     ? 0 : $venta['op_gravadas'];
        // $total_exportacion  = ($venta['total_exportacion'] == null) ? 0 : $venta['total_exportacion'];
        $total_exportacion  = 0;
        $total_exonerada    = ($venta['op_exoneradas'] == null)   ? 0 : $venta['op_exoneradas'];
        $total_inafecta     = ($venta['op_inafectas'] == null)    ? 0 : $venta['op_inafectas'];
        $xml .=  '</cac:TaxTotal>';
        $anticipo_total = 0;
        $xml .=  '<cac:' . $tag_total_pago . '>                                
                            <cbc:LineExtensionAmount currencyID="' . $venta['codigo'] . '">' . number_format(($total_gravada + $total_exportacion + $total_exonerada + $total_inafecta), 2, '.', '') . '</cbc:LineExtensionAmount>
                            <cbc:TaxInclusiveAmount currencyID="' . $venta['codigo'] . '">' . number_format($venta['total_venta'], 2, '.', '') . '</cbc:TaxInclusiveAmount>
                            <cbc:PayableAmount currencyID="' . $venta['codigo'] . '">' . number_format($venta['total_venta'] - $anticipo_total, 2, '.', '') . '</cbc:PayableAmount>
                        </cac:' . $tag_total_pago . '>';
        // $venta = $this->ventacabecera($idventa);

        $i = 1;
        $percent = $venta['igv_asig'];
        // var_dump($detalles);
        // exit;
        foreach ($detalles as $value) {
            // var_dump($value['descuento']);
            // exit;

            // $icbper = ($value['impuesto_bolsa'] != null) ? $value['impuesto_bolsa'] : 00.00;
            $icbper = 00.00;
            $codigo_de_tributo = 1000;
            $priceAmount = $this->priceAmount($value['precio_venta'], $codigo_de_tributo, $percent, $icbper, $value['descuento']);
            $PriceTypeCode = ($codigo_de_tributo == 9996) ? '02' : '01';
            $taxAmount = $this->taxAmount($value['cantidad'], $value['precio_venta'], $codigo_de_tributo, $percent, $value['descuento']);
            $price_priceAmount = $this->price_priceAmount($value['precio_venta'], $codigo_de_tributo, $value['descuento']);
            //sale del catalgo16
            //PriceAmount precio unitario (precio base x (1 + IGV)) + impuesto por 1 bolsa. (en caso no se pague IGV sera 1 + 0).

            $linea = '';
            $cantidad = '';

            switch ($venta['codigotipo_comprobante']) {

                case '1':
                    $linea      = 'InvoiceLine';
                    $cantidad   = 'InvoicedQuantity';
                    break;

                case '3':
                    $linea      = 'InvoiceLine';
                    $cantidad   = 'InvoicedQuantity';
                    break;

                case '7':
                    $linea      = 'CreditNoteLine';
                    $cantidad   = 'CreditedQuantity';
                    break;

                case '8':
                    $linea      = 'DebitNoteLine';
                    $cantidad   = 'DebitedQuantity';
                    break;
            }
            $precio_base = $value['precio_venta'] / (1 + (18 / 100));
            $precio_base = number_format($precio_base, 2, '.', '');

            $xml .= '<cac:' . $linea . '>
                            <cbc:ID>' . $i . '</cbc:ID>
                            <cbc:' . $cantidad . ' unitCode="NIU">' . $value['cantidad'] . '</cbc:' . $cantidad . '>
                            <cbc:LineExtensionAmount currencyID="' . $venta['codigo'] . '">' . number_format($value['cantidad'] * ($precio_base - $value['descuento']), 2, '.', '') . '</cbc:LineExtensionAmount>
                            <cac:PricingReference>
                                <cac:AlternativeConditionPrice>
                                    <cbc:PriceAmount currencyID="' . $venta['codigo'] . '">' . abs(number_format($priceAmount, 6, '.', '')) . '</cbc:PriceAmount>
                                    <cbc:PriceTypeCode listName="Tipo de Precio" listAgencyName="PE:SUNAT" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">' . $PriceTypeCode . '</cbc:PriceTypeCode>
                                </cac:AlternativeConditionPrice>
                            </cac:PricingReference>';
            // var_dump($venta['codigotipo_comprobante']);
            // exit;
            if ($value['descuento'] != '' && ($venta['codigotipo_comprobante'] != 7 && $venta['codigotipo_comprobante'] != 8)) {
                //chekar catalogo 53
                $codigo_cargos = ($tipo_igv_id_item == 1) ? '00' : '01';
                $xml .= '<cac:AllowanceCharge>
                                    <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
                                    <cbc:AllowanceChargeReasonCode listAgencyName="PE:SUNAT" listName="Cargo/descuento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo53">' . $codigo_cargos . '</cbc:AllowanceChargeReasonCode>
                                    <cbc:Amount currencyID="' . $venta['codigo'] . '">' . $value['descuento'] . '</cbc:Amount>
                                    <cbc:BaseAmount currencyID="' . $venta['codigo'] . '">' . abs(number_format($precio_base, 2, '.', '')) . '</cbc:BaseAmount>
                                </cac:AllowanceCharge>';
            }
            $percento = $venta['igv_asig'] / 100;
            $tipo_igv_codigo = 10;
            $nombre_tributo = 'IGV';
            $codigo_internacional = 'VAT';
            $xml .=     '<cac:TaxTotal>
                                    <cbc:TaxAmount currencyID="' . $venta['codigo'] . '">' . number_format(($taxAmount + $icbper * $value['cantidad']), 2, '.', '') . '</cbc:TaxAmount>                                                
                                    <cac:TaxSubtotal>
                                        <cbc:TaxableAmount currencyID="' . $venta['codigo'] . '">' . number_format(($precio_base - $value['descuento']) * $value['cantidad'], 2, '.', '') . '</cbc:TaxableAmount>
                                        <cbc:TaxAmount currencyID="' . $venta['codigo'] . '">' . number_format($taxAmount, 2, '.', '') . '</cbc:TaxAmount>
                                        <cac:TaxCategory>
                                            <cbc:Percent>' . $percento * 100 . '</cbc:Percent>
                                            <cbc:TaxExemptionReasonCode>' . $tipo_igv_codigo . '</cbc:TaxExemptionReasonCode>
                                            <cac:TaxScheme>
                                                <cbc:ID>' . $codigo_de_tributo . '</cbc:ID>                                                    
                                                <cbc:Name>' . $nombre_tributo . '</cbc:Name>                                                    
                                                <cbc:TaxTypeCode>' . $codigo_internacional . '</cbc:TaxTypeCode>
                                            </cac:TaxScheme>
                                        </cac:TaxCategory>
                                    </cac:TaxSubtotal>';
            // if($value['impuesto_bolsa'] != null){ 
            //         $xml .= '<cac:TaxSubtotal>
            //                     <cbc:TaxAmount currencyID="'.$venta['abrstandar'].'">' . $icbper * $value['cantidad'] . '</cbc:TaxAmount>
            //                     <cbc:BaseUnitMeasure unitCode="NIU">' . number_format($value['cantidad'], 0, '','') . '</cbc:BaseUnitMeasure>
            //                     <cac:TaxCategory>
            //                         <cbc:PerUnitAmount currencyID="'.$venta['abrstandar'].'">' . $icbper . '</cbc:PerUnitAmount>
            //                         <cac:TaxScheme>
            //                             <cbc:ID schemeAgencyName="PE:SUNAT" schemeName="Codigo de tributos" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">7152</cbc:ID>
            //                             <cbc:Name>ICBPER</cbc:Name>
            //                             <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>	
            //                         </cac:TaxScheme>
            //                     </cac:TaxCategory>
            //                 </cac:TaxSubtotal>';
            // }                     
            $codigo_sunat = '-';
            $xml .=     '</cac:TaxTotal>                                    
                                <cac:Item>                                    
                                    <cbc:Description><![CDATA[' . $value['articulo'] . ']]></cbc:Description>
                                    <cac:SellersItemIdentification>
                                        <cbc:ID>' . $value['codigoproduct'] . '</cbc:ID>
                                    </cac:SellersItemIdentification>
                                </cac:Item>
                                <cac:Price>
                                    <cbc:PriceAmount currencyID="' . $venta['codigo'] . '">' . abs($price_priceAmount) . '</cbc:PriceAmount>
                                </cac:Price>
                        </cac:' . $linea . '>
                        ';
            $i++;
        }
        $xml .=  '</' . $linea_fin . '>';
        return $xml;
    }

    public function leerRespuestaSunat($nombre_archivo)
    {
        // echo "entro al leer respuesta";
        // var_dump($nombre_archivo);
        $nombre = "R-" . $nombre_archivo;
        // echo $nombre;
        // exit;
        $resultado = array();
        //echo "abc";exit;        
        if (file_exists($nombre)) {
            // $library = new SimpleXMLElement($nombre, null, true);
            // echo "entro al if de leerrespuesta";
            $library = new SimpleXMLElement($nombre, 0, true);

            $ns = $library->getDocNamespaces();
            $ext1 = $library->children($ns['cac']);
            $ext2 = $ext1->DocumentResponse;
            $ext3 = $ext2->children($ns['cac']);
            $ext4 = $ext3->children($ns['cbc']);

            $resultado = array(
                'respuesta_sunat_codigo' => trim($ext4->ResponseCode),
                'respuesta_sunat_descripcion' => trim($ext4->Description)
            );
        }
        return $resultado;
    }
}

$nombre_archivo = $_POST['nombre_archivo'];
$idventa = $_POST['idventa'];
$xmlsunat = new XMLSunat();
$respuesta = $xmlsunat->enviar_sunat($nombre_archivo, $idventa);
