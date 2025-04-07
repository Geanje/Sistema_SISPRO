<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require('../ws_sunat/lib/pclzip.lib.php'); // Librería que comprime archivos en .ZIP
//NOMBRE DE ARCHIVO A PROCESAR.
//$numero_documento = '20604051984-01-F001-131';
//$ruta = '../files/facturacion_electronica/FIRMA/';
function procesarSunat($cod_1,$cod_2,$cod_3,$cod_4,$cod_5,$cod_6,$numero_documento) {
    // var_dump($numero_documento);
    // exit;
    // var_dump($cod_1,$cod_2,$cod_3,$cod_4,$cod_5,$cod_6,$numero_documento);
    // exit;
    // $numero_documento = $params['numero_documento'];
    // $cod_1 = $params['cod_1'];
    // $cod_2 = $params['cod_2'];
    // $cod_3 = $params['cod_3'];
    // $cod_4 = $params['cod_4'];
    // $cod_5 = $params['cod_5'];
    // $cod_6 = $params['cod_6'];

// $serie = $_GET['cod_7'];
// $numero_documento = $numero_documento;
//$ruta = ($cod_6 == '0') ? '../files/facturacion_electronica/FIRMA/' : '../files/facturacion_electronica/BAJA/FIRMA/' ;
switch ($cod_6) {
    case 1:
        $ruta = '../files/facturacion_electronica/FIRMA/';
        $metodo = 'sendBill';   //hacer facturas o boletas
        break;
    case 2:
        $ruta = '../files/facturacion_electronica/BAJA/FIRMA/';
        $metodo = 'sendSummary';    //enviar anulación
        break;
    case 3:
        $ruta = '../files/facturacion_electronica/BAJA/RPTA/';
        $metodo = 'getStatus';  //metodo recibie ticket de anulacion
        break;
    case 4:
        $ruta = '../files/facturacion_electronica/FIRMA/';
        $metodo = 'getStatusCdr';  //pregunta estado CDR        
        break;
}

//enviar a Sunat       
//cod_1: Select web Service: 1 factura, boletas --- 9 es para guias
//cod_2: Entorno:  0 Beta, 1 Produccion
//cod_3: ruc
//cod_4: usuario secundario USU(segun seha beta o producción)
//cod_5: usuario secundario PASSWORD(segun seha beta o producción)
//cod_6: Accion:   1 enviar documento a Sunat --  2 enviar a anular  --  3 enviar ticket  -- 4 getStatusCDR
//cod_7: tipo_documento-serie-numero
//cod_8: numero ticket

## =============================================================================

//creamos zip siempre queno recibo tickets
if(($cod_6 == 1) || ($cod_6 == 2) ){
    ## Creación del archivo .ZIP
    $zip = new PclZip($ruta.$numero_documento . ".zip");
    //var_dump($zip);exit;

    if(file_exists($ruta.$numero_documento . ".zip")){
        $r = 1;
    }else{    
        $zip->add($ruta.$numero_documento.".xml", PCLZIP_OPT_REMOVE_PATH, $ruta, PCLZIP_OPT_ADD_PATH, '');
    }

    //$zip->create($numero_documento . ".xml");
    chmod($ruta.$numero_documento . ".zip", 0777);
    //echo $ruta.$numero_documento . ".zip";
    # ==============================================================================
    # Procedimiento para enviar comprobante a la SUNAT
}


switch ($cod_6) {
    case 1:        
        $content = '<fileName>' . $numero_documento . '.zip</fileName><contentFile>' . base64_encode(file_get_contents($ruta.$numero_documento . '.zip')) . '</contentFile>';
        break;
    case 2:        
        $content = '<fileName>' . $numero_documento . '.zip</fileName><contentFile>' . base64_encode(file_get_contents($ruta.$numero_documento . '.zip')) . '</contentFile>';
        break;
    case 3:        
        $content = '<ticket>'.$_GET['cod_8'].'</ticket>';
        break;
    case 4:        
        $documento = explode("-", $numero_documento);        
        $content= '<rucComprobante>'.$documento[0].'</rucComprobante>
        <tipoComprobante>'.$documento[1].'</tipoComprobante>
        <serieComprobante>'.$documento[2].'</serieComprobante>
        <numeroComprobante>'.$documento[3].'</numeroComprobante>';
        break;
}


class feedSoap extends SoapClient {

    public $XMLStr = "";

    public function setXMLStr($value) {
        $this->XMLStr = $value;
    }

    public function getXMLStr() {
        return $this->XMLStr;
    }
  
    public function __doRequest($request, $location, $action, $version, $one_way = 0){//:?string 
        $request = $this->XMLStr;
        $dom = new DOMDocument('1.0');
        try {
            $dom->loadXML($request);
        } catch (DOMException $e) {
            die($e->code);
        }
        $request = $dom->saveXML();
        //Solicitud
        return parent::__doRequest($request, $location, $action, $version, $one_way = 0);
    }

    public function SoapClientCall($SOAPXML) {
        return $this->setXMLStr($SOAPXML);
    }

}

function soapCall($wsdlURL, $XMLString, $callFunction = "") {
    // var_dump($wsdlURL);
    // exit;
    $client = new feedSoap($wsdlURL, array('trace' => true));
    $reply = $client->SoapClientCall($XMLString);
    //echo "REQUEST:\n" . $client->__getFunctions() . "\n";
    $client->__call("$callFunction", array());
    //$request = prettyXml($client->__getLastRequest());
    //echo highlight_string($request, true) . "<br/>\n";
    return $client->__getLastResponse();
}

if($cod_1 == 1){
    //FACTURAS
    //$wsdlURL = ($cod_2 == 1) ? 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl' : 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';
    $wsdlURL = ($cod_2 == 1) ? '../ws_sunat/billService.wsdl' : 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';
}elseif($cod_1 == 9){
    //GUIAS
    $wsdlURL = ($cod_2 == 1) ? 'https://e-guiaremision.sunat.gob.pe/ol-ti-itemision-guia-gem/billService?wsdl' : 'https://e-beta.sunat.gob.pe/ol-ti-itemision-guia-gem-beta/billService?wsdl';
}

//Estructura del XML para la conexión
$XMLString = '<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
 <soapenv:Header>
     <wsse:Security>
         <wsse:UsernameToken>
             <wsse:Username>'.$cod_3.$cod_4.'</wsse:Username>
             <wsse:Password>'.$cod_5.'</wsse:Password>
         </wsse:UsernameToken>
     </wsse:Security>
 </soapenv:Header>
 <soapenv:Body>
     <ser:'.$metodo.'>'.$content.'</ser:'. $metodo .'>
 </soapenv:Body>
</soapenv:Envelope>';
//echo $XMLString;exit;

function getBetween($string, $start = "", $end = ""){
    if (strpos($string, $start)) { // required if $start not exist in $string
        $startCharCount = strpos($string, $start) + strlen($start);
        $firstSubStr = substr($string, $startCharCount, strlen($string));
        $endCharCount = strpos($firstSubStr, $end);
        if ($endCharCount == 0) {
            $endCharCount = strlen($firstSubStr);
        }
        return substr($firstSubStr, 0, $endCharCount);
    } else {
        return '';
    }
}

function buscoTicket($result){
    $library = new SimpleXMLElement($result);
    $ns = $library->getDocNamespaces();
    $ext1 = $library->children($ns['soapenv']);
    $ext2 = $ext1->Body;            
    $ext3 = $ext2->children($ns['ser']);
    $ext4 = $ext3->sendSummaryResponse;        
    $ext5 = $ext4->children();
    $ticket = $ext5->ticket;

    //var_dump($ext5);exit;
    return ($ticket[0]);
}

//echo 'SOAP de envío al servidor de SUNAT con el método sendBill.';
//echo $XMLString;
//Realizamos la llamada a nuestra función
$error_mensaje = '';
$error_existe = 0;
$result = '';

$result = soapCall($wsdlURL, $XMLString, $callFunction = "sendBill");    

try {        
    
    //$result = soapCall($wsdlURL, $callFunction = "sendBill", $XMLString);    
    //echo $result;exit;
    //echo "cc";exit;
    //echo "abcd";exit;
    
    //echo '<span style="color: #000000;">' . $result . '</span>';
    //Descargamos el Archivo Response
    switch ($cod_6) {
        case '1':           
            $archivo = fopen('C' . $numero_documento . '.xml', 'w+');
            fputs($archivo, $result);
            fclose($archivo);
            /* LEEMOS EL ARCHIVO XML */
            $xml = simplexml_load_file('C' . $numero_documento . '.xml');
            foreach ($xml->xpath('//applicationResponse') as $response) {

            }
            /* AQUI DESCARGAMOS EL ARCHIVO CDR(CONSTANCIA DE RECEPCIÓN) */
            $cdr = base64_decode($response);
            $archivo = fopen($ruta.'R-' . $numero_documento . '.zip', 'w+');
            fputs($archivo, $cdr);
            fclose($archivo);
            chmod($ruta.'R-' . $numero_documento . '.zip', 0777);
            $archive = new PclZip($ruta.'R-' . $numero_documento . '.zip');
            if ($archive->extract() == 0) {
                // echo "entro al if de archive en index.php";
                die("Error : " . $archive->errorInfo(true));
            } else {
                chmod($ruta.'R-' . $numero_documento . '.zip', 0777);
            }
            /* Eliminamos el Archivo Response */
            unlink('C' . $numero_documento . '.xml');
            break;
        case '2':
            $result = buscoTicket($result);    
            break;
        case '3':
            //Respuestas del ticket
            //0 = Procesó correctamente
            //98 = En proceso
            //99 = Proceso con errores
            $archivo = fopen('C' . $numero_documento . '.xml', 'w+');
            fputs($archivo, $result);
            fclose($archivo);
            /* LEEMOS EL ARCHIVO XML */
            $xml = simplexml_load_file('C' . $numero_documento . '.xml');
            foreach ($xml->xpath('//content') as $response) {

            }
            /* AQUI DESCARGAMOS EL ARCHIVO CDR(CONSTANCIA DE RECEPCIÓN) */
            $cdr = base64_decode($response);
            $archivo = fopen($ruta.'R-' . $numero_documento . '.zip', 'w+');
            fputs($archivo, $cdr);
            fclose($archivo);
            chmod($ruta.'R-' . $numero_documento . '.zip', 0777);
            $archive = new PclZip($ruta.'R-' . $numero_documento . '.zip');
            if ($archive->extract() == 0) {
                die("Error : " . $archive->errorInfo(true));
            } else {
                chmod('R-' . $numero_documento . '.xml', 0777);
            }
            /* Eliminamos el Archivo Response */
            //unlink('C' . $numero_documento . '.xml');
            break;
        case '4':
            $result = ($result);    
            break;
    } 
} catch (Exception $e) {
    $error_existe = 1;
    $error_mensaje = $e->getMessage();
}
////////////////////////////////////////////////////////////////////////////
$jsondata = array(
    'param_ver'     =>  $result,
    'success'       =>  true,    
    'error_mensaje' =>  $error_mensaje,
    'error_existe'  =>  $error_existe
);
if($cod_6 == 2){
    $jsondata = array_merge($jsondata, array('ticket' => $result));
}

//echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);


$jsondata = array(
    'param_ver'     =>  $result,
    'success'       =>  true,    
    'error_mensaje' =>  $error_mensaje,
    'error_existe'  =>  $error_existe,
    'numero_documento' => $numero_documento
);

if($cod_6 == 2){
    $jsondata = array_merge($jsondata, array('ticket' => $result));
}

return $jsondata;
}

// if (!isset($included)) {
    // procesarSunat($cod_1,$cod_2,$cod_3,$cod_4,$cod_5,$cod_6,$numero_documento);
    // json_encode($result, JSON_UNESCAPED_UNICODE);
// }