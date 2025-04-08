<?php
ob_start();

require_once dirname(__FILE__).'/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

if (strlen(session_id()) < 1) 
  session_start();
 
if (!isset($_SESSION["nombre"])) {
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
  if ($_SESSION['ventas'] == 1) {
    try {
      // Incluye el archivo necesario
      require_once 'comprobante_p_servicios.php';
      require 'comprobante_p_servicios.php';
      $html = ob_get_clean();

      require_once "../modelos/Perfil.php";
      $perfil = new Perfil();
      $rspta = $perfil->cabecera_perfil();
      $reg = $rspta->fetch_object();
      $ruc = $reg->ruc;
      require_once "../modelos/Pago_servicios.php";
      $pago = new Pagos();
      var_dump($idcomprobante);
      $rsptap = $pago->comprobantePDF($idcomprobante);
      $regp = $rsptap->fetch_object();
      $serie = $regp->serie;
      $idcomprobante = $regp->idcomprobante;
      $correlativo = $regp->correlativo; 
      $correo = $regp -> correo;
      $nombre_archivo = $ruc . '-' . '0' . $idcomprobante . '-' . $serie . '-' . $correlativo . '.pdf'; // Nombre del archivo que deseas darle

      $ruta_pdf = 'pdfs/' . $nombre_archivo;
      $ruta_completa = __DIR__ . '/' . $ruta_pdf;

      $html2pdf = new Html2Pdf('P', array(105, 148), 'es', 'true', 'UTF-8');

      $html2pdf->writeHTML($html);
      $html2pdf->output($ruta_completa, "f"); 
      // <!-- $html2pdf->output($ruta_completa, "I");  -->
      // ob_end_flush();
// require_once 'enviarAlCorreo.php';
// require 'enviarAlCorreo.php';

    } catch (Html2PdfException $e) {
      // Manejo de excepciones
      $html2pdf->clean();
      $formatter = new ExceptionFormatter($e);
      echo $formatter->getHtmlMessage();
      echo 'Hay un error';
    }
  } else {
    echo 'No tiene permiso para visualizar el reporte';
   }
}
// require_once 'enviarAlCorreo.php';
require 'enviarAlCorreo.php';


?>
