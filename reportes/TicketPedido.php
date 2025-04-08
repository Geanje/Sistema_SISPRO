<?php

ob_start();


require_once dirname(__FILE__).'/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

try{
    
    ob_start();
    require_once dirname(__FILE__) . '/ticketPedidoTemplate.php';
    $content = ob_get_clean();
    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    $html2pdf->output('ticket-pedido.pdf');


} catch (Html2PdfException $e) {
    $html2pdf->clean();
    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}

ob_end_flush();