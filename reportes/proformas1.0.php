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
		require_once "../modelos/Perfil.php";
		$perfil=new Perfil();
		$rspta=$perfil->cabecera_perfil();
		$reg=$rspta->fetch_object();
		$ruc=$reg->ruc;

		require_once "../modelos/proforma1.0.php";
		$proforma=new Proforma();
		$rsptap= $proforma->ventacabecera($_GET["id"]);
		$regp=$rsptap->fetch_object();
		$idproforma=$regp->idproforma;
		//$serie=$regp->serie;
		$correlativo=$regp->correlativo;

		// Selecionar Planitlla A4- Tikect	
			include dirname(__FILE__).'/Proformas_A4.php';
			//include dirname(__FILE__).'/Proformas_ticket.php';
		    $content = ob_get_clean();

		    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
		    $html2pdf->pdf->SetDisplayMode('fullpage');
		    $html2pdf->writeHTML($content);
		    $html2pdf->output($ruc.'-'.$idproforma.'-'.$serie.'-'.$correlativo.'.pdf');
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
<!-- <center>
 <form action="" method="POST">
 	<input type="text" name="v1" >
 	<input name="btn" type="submit" id="btn" value="Generar PDF">
 </form>
	
</center> -->