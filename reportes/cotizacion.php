<?php


ob_start();
require 'vendor/autoload.php';

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

			ob_start();
			require_once "../modelos/Perfil.php";
			$perfil = new Perfil();
			$rspta = $perfil->cabecera_perfil();
			$reg = $rspta->fetch_object();
			$ruc = $reg->ruc;


			require_once "../modelos/Cotizacion.php";
			$cotizacion = new Cotizacion();

			$rsptac = $cotizacion->ventacabecera($_GET["id"]);

			$regc = $rsptac->fetch_object();
			$idcotizacion = $regc->idcotizacion;
			$tipo_proforma = $regc->tipo_proforma;


			if ($tipo_proforma == 'Productos') {
				include dirname(__FILE__) . '/Cotizacion_Productos.php';
				$content = ob_get_clean();

				$html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
				$html2pdf->pdf->SetDisplayMode('fullpage');
				$html2pdf->writeHTML($content);
				$html2pdf->output('Cotizacion_productos_' . $idcotizacion . '.pdf');
				$conexion->close();
			} else {
				include dirname(__FILE__) . '/Cotizacion_Servicios.php';
				$content = ob_get_clean();

				$html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
				$html2pdf->pdf->SetDisplayMode('fullpage');
				$html2pdf->writeHTML($content);
				$html2pdf->output('Cotizacion_servicios_' . $idcotizacion . '.pdf');
				$conexion->close();
			}
		} catch (Html2PdfException $e) {
			$html2pdf->clean();

			$formatter = new ExceptionFormatter($e);
			echo $formatter->getHtmlMessage();
		}
	} else {
		echo 'No tiene permiso para visualizar el reporte';
	}
}
ob_end_flush();
