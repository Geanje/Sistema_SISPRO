<?php
header ('Content-type: text/html; charset=utf-8'); 
$ruta_relativa = '../public/librerias/see/lib/xmlseclibs-master/xmlseclibs.php';
$rutaAbsoluta = realpath($ruta_relativa);

// if ($rutaAbsoluta) {
//     echo 'Ruta absoluta: ' . $rutaAbsoluta;
// } else {
//     echo 'La ruta relativa no es válida.'.$rutaAbsoluta;
// }
// exit();
require $rutaAbsoluta;
class Facturaxml{
	public function firmar(DOMDocument $domDocument,$modo, $ruc=""){
                //echo "modo:".$modo;

		$ReferenceNodeName = 'ExtensionContent';
//              $privateKey = file_get_contents('prueba/server_key.pem');
//		$publicKey = file_get_contents('prueba/server.pem');
                
                $modo = ($modo == 1) ? 'produccion' : 'prueba';
				// $documentRoot = $_SERVER['DOCUMENT_ROOT'];

				// Construir la ruta absoluta a los archivos
				$privateKey = file_get_contents("../public/librerias/certificado_digital/$modo/server_key.pem");
				$publicKey = file_get_contents("../public/librerias/certificado_digital/$modo/server.pem");
		$objSign = new XMLSecurityDSig($ruc);
		$objSign->setCanonicalMethod(XMLSecurityDSig::C14N);
		$objSign->addReference(
			$domDocument,
			XMLSecurityDSig::SHA1, 
			array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'),
			$options = array('force_uri' => true)
		);
		
		$objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'private'));
		$objKey->loadKey($privateKey);
		
		// Sign the XML file
		$Node = $domDocument->getElementsByTagName($ReferenceNodeName)->item(1);
		if (!($Node)) $Node = $domDocument->getElementsByTagName($ReferenceNodeName)->item(0);
		$objSign->sign($objKey, $Node);
		// Add the associated public key to the signature
		$objSign->add509Cert($publicKey);
		return $domDocument;
	}
}
//$xmlstr = file_get_contents("20602535933-01-F001-56.xml");
//$name_file = "20604051984-01-F001-131.xml";
