<?php 
	// require_once __DIR__.'/vendor/autoload.php';

ob_start();
require 'vendor/autoload.php';
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
	
/*if(isset($_POST['btn'])){
	// use Spipu\Html2Pdf\Html2Pdf;
	ob_start(); 
	require_once 'print.html';
	$html = ob_get_clean();
	$Html2Pdf=new Html2Pdf('P','A4','es','true','UTF-8');
	$Html2Pdf->writeHTML($html);
	// $Html2Pdf->writeHTML('<h1>fee</h1>');
	$Html2Pdf->output();
}*/

	 



	try {
	    ob_start();
		
		require_once "../modelos/NotaCredito.php";
		$venta=new NotaCredito();
		$rsptac= $venta->ventacabecera($_GET["id"]);
		$regc=$rsptac->fetch_object();
		$idmotivo_doc=$regc->idmotivo_doc;
		$idcodigocompro=$regc->codigotipo_comprobante;
		$serie=$regc->serie;
		$correlativo=$regc->correlativo;
		require_once "../modelos/Perfil.php";
		$perfil=new Perfil();
		$rspta=$perfil->cabecera_perfil();
		$reg=$rspta->fetch_object();
		$rucc=$reg->ruc;

		// if($idmotivo_doc=='1'){
		// 	include dirname(__FILE__).'/1anulacionOperacion.php';
		//     $content = ob_get_clean();

		//     $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
		//     $html2pdf->pdf->SetDisplayMode('fullpage');
		//     $html2pdf->writeHTML($content);
		//     // $html2pdf->output('dede.pdf');
		//     $html2pdf->output($rucc.'-'.'0'.$idcodigocompro.'-'.$serie.'-'.$correlativo.'.pdf');
		// 	$conexion->close(); 	  
		// }else if($idmotivo_doc=='2'){
		// 	include dirname(__FILE__).'/2anulacionError.php';
		//     $content = ob_get_clean();

		//     $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
		//     $html2pdf->pdf->SetDisplayMode('fullpage');
		//     $html2pdf->writeHTML($content);
		//     // $html2pdf->output('dede.pdf');
		//     $html2pdf->output($rucc.'-'.'0'.$idcodigocompro.'-'.$serie.'-'.$correlativo.'.pdf');
		// 	$conexion->close(); 	  
		// }else if($idmotivo_doc=='3'){
		// 	include dirname(__FILE__).'/3correcionErrorDescripcion.php';
		//     $content = ob_get_clean();

		//     $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
		//     $html2pdf->pdf->SetDisplayMode('fullpage');
		//     $html2pdf->writeHTML($content);
		//     // $html2pdf->output('dede.pdf');
		//     $html2pdf->output($rucc.'-'.'0'.$idcodigocompro.'-'.$serie.'-'.$correlativo.'.pdf');
		// 	$conexion->close(); 	  
		// }else if($idmotivo_doc=='6'){
		// 	include dirname(__FILE__).'/6devolucionTotal.php';
		//     $content = ob_get_clean();

		//     $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
		//     $html2pdf->pdf->SetDisplayMode('fullpage');
		//     $html2pdf->writeHTML($content);
		//     // $html2pdf->output('dede.pdf');
		//     $html2pdf->output($rucc.'-'.'0'.$idcodigocompro.'-'.$serie.'-'.$correlativo.'.pdf');
		// 	$conexion->close(); 	  
		// }else if($idmotivo_doc=='7'){
		// 	include dirname(__FILE__).'/7devolucionParcial.php';
		//     $content = ob_get_clean();

		//     $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
		//     $html2pdf->pdf->SetDisplayMode('fullpage');
		//     $html2pdf->writeHTML($content);
		//     // $html2pdf->output('dede.pdf');
		//     $html2pdf->output($rucc.'-'.'0'.$idcodigocompro.'-'.$serie.'-'.$correlativo.'.pdf');
		// 	$conexion->close(); 	  
		// }
	
	    include dirname(__FILE__).'/comprobante_A4_NotaCredito.php';
		    $content = ob_get_clean();

		    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
		    $html2pdf->pdf->SetDisplayMode('fullpage');
		    $html2pdf->writeHTML($content);
		    // $html2pdf->output('dede.pdf');
		    $html2pdf->output($rucc.'-'.'0'.$idcodigocompro.'-'.$serie.'-'.$correlativo.'.pdf');
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
