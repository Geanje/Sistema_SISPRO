<?php

ob_start();

require_once dirname(__FILE__).'/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
if (strlen(session_id()) < 1) 
  session_start();
 
if (!isset($_SESSION["nombre"]))
{
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
}
else
{
if ($_SESSION['ventas']==1)
{

try {
    ob_start();
  		require_once "../modelos/Perfil.php";
		$perfil=new Perfil();
		$rspta=$perfil->cabecera_perfil();
		$reg=$rspta->fetch_object();
		$ruc=$reg->ruc;
		require_once "../modelos/Pago_servicios.php";
		$pago=new Pagos();
		$rsptap= $pago->comprobantePDF($_GET["id"]);
		$regp=$rsptap->fetch_object();
		$serie=$regp->serie;
		$idcomprobante=$regp->idcomprobante;
		$correlativo=$regp->correlativo; 
        
            include dirname(__FILE__).'/comprobante_pago_servicios.php';
            $content = ob_get_clean();
    
            // Tamaño personalizado, 150 mm de ancho y 200 mm de alto
            $tamanoPersonalizado = array(105, 148);
            $html2pdf = new Html2Pdf('P', $tamanoPersonalizado, 'es', true, 'UTF-8');
            // $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->writeHTML($content);
            $html2pdf->output($ruc.'-'.'0'.$idcomprobante.'-'.$serie.'-'.$correlativo.'.pdf');
            $conexion->close(); 
        
   

} catch (Html2PdfException $e) {
    $html2pdf->clean();

    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}
}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush();
?>