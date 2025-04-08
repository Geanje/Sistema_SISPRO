<?php

if (strlen(session_id()) < 1)
    session_start();

require_once "../modelos/Pago_servicios.php";

$pago = new Pagos();

// $id_p_servicio = isset($_POST['id_p_servicio'])? limpiarCadena($_POST['id_p_servicio']):"";
$idcomprobante = isset($_POST['idcomprobante']) ? limpiarCadena($_POST['idcomprobante']) : "";
$id_r_servicio = isset($_POST['id_r_servicio']) ? limpiarCadena($_POST['id_r_servicio']) : "";
$idcliente = isset($_POST['idcliente']) ? limpiarCadena($_POST['idcliente']) : "";
//$idusuario = isset($_POST['idusuario'])? limpiarCadena($_POST['idsuario'])
// $periodo = isset($_POST['periodo'])? limpiarCadena($_POST['periodo']):"";
$rscorrelativo = isset($_POST['rs.correlativo']) ? limpiarCadena($_POST['rs.correlativo']) : "";
$rsserie = isset($_POST['rs.serie']) ? limpiarCadena($_POST['rs.serie']) : "";
$fecha_emision = isset($_POST['fecha_emision']) ? limpiarCadena($_POST['fecha_emision']) : "";
$fecha_vencimiento = isset($_POST['fecha_vencimiento']) ? limpiarCadena($_POST['fecha_vencimiento']) : "";
$fecha_corte = isset($_POST['fecha_corte']) ? limpiarCadena($_POST['fecha_corte']) : "";
//$estado = isset($_POST['estado'])? limpiarCadena($_POST['estado']):"";
$idusuario = $_SESSION["idusuario"];
//$periodo = isset($_POST['periodo'])? limpiarCadena($_POST['periodo']):"";



switch ($_GET["op"]) {
    case 'guardaryeditar':

        $rspta = $pago->insertarParaContratosActivos($id_r_servicio, $idusuario);
        echo $rspta ? "Comprobantes registrados con éxito" : "Algo salio mal";

        break;

    case 'mostrar':
        $rspta = $pago->mostrar($idcomprobante);
        echo json_encode($rspta);
        break;

    case 'guardarFechaCorte':
        $rspta = $pago->guardarFechaCorte($fecha_corte, $idcomprobante);
        echo $rspta ? "Fecha actualizada" : "No se pudo actualizar la fecha";
        break;

    case 'listar':
        $rspta = $pago->listar();
        $url_pdf = '../reportes/PDF_Pago_Servicios.php?id=';//23.08.2023
        $url_pdf2 = '../reportes/pdf_individual_ps.php?id=';//😎24.08.2023
        $data = array();
        while ($reg = $rspta->fetch_object()) {
            $buttons = ''; // Inicializamos la variable de botones vacía
                if ($reg->estado == 'Pagado') {
                    $buttons = '<a target="_blank" href="' . $url_pdf . $reg->idcomprobante . '"> <button class="btn btn-danger"><i class="fa fa-file-pdf-o"></i></button></a>' . " " .
                        '<a data-toggle="modal" href="#editComprobante"><button class="btn btn-warning" onclick="editarFechaCorte(' . $reg->idcomprobante . ')"><i class="fa fa-pencil"></i></button></a>';
                } else {
                    $buttons = '<a target="_blank" href="' . $url_pdf . $reg->idcomprobante . '"> <button class="btn btn-danger"><i class="fa fa-file-pdf-o"></i></button></a>' . " " .
                    '<button class="btn btn-primary" onclick="pdfindividual(' . $reg->idcomprobante . ')"><i class="fa fa-envelope"></i></button>' . " " .
                        '<a data-toggle="modal" href="#editComprobante"><button class="btn btn-warning" onclick="editarFechaCorte(' . $reg->idcomprobante . ')"><i class="fa fa-pencil"></i></button></a>';
                }
                
            $data[] = array(
                "0" =>
                '<a target="_blank" href="' . $url_pdf . $reg->idcomprobante . '"> <button class="btn btn-danger" onclick="pruebapdf('.$reg->idcomprobante.')"><i class="fa fa-file-pdf-o"></i></button></a>'." ".
                // '<a target="_blank" href="' . $url_pdf2 . $reg->idcomprobante . '"> <button class="btn btn-primary" onclick="pruebapdf('.$reg->idcomprobante.')"><i class="fa fa-envelope"></i></button></a>'." ".//😎24.08.2023 SE AGREGÓ TODA ESTA LÍNEA
                '<button class="btn btn-primary" onclick="pdfindividual(' . $reg->idcomprobante . ')"><i class="fa fa-envelope"></i></button>' . " " .//😎25.08.2023 toda la línea
                // ' <button class="btn btn-warning" onclick="editar('.$reg->id_r_servicio.')"><i class="fa fa-pencil"></i></button>'.
                '<a data-toggle="modal" href="#editComprobante"><button class="btn btn-warning" onclick="editarFechaCorte('.$reg->idcomprobante.')"><i class="fa fa-pencil"></i></button></a>',
                "1" => $reg->fecha_emision,
                "2" => $reg->cliente,
                "3" => $reg->servicio,
                "4" => $reg->costo,
                "5" => $reg->rs_serie_correlativo,
                "6" => $reg->periodo,
                "7" => $reg->serie . "-" . $reg->correlativo,
                "8" => ($reg->estado == 'Pagado') ? '<span class="label bg-green">Pagado</span>' :
                    '<span class="label bg-red">Pendiente</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Informacion para el datatable
            "iTotalRecords" => count($data), //Enviamos el total de registtros en el datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;

    case 'borrarPDF'://😎25.08.2023 se agregó el case
        $carpeta = 'pdfs';
        if (is_dir($carpeta)) {
            $archivos = glob($carpeta . '/*');
            foreach ($archivos as $archivo) {
                if (is_file($archivo)) {
                    unlink($archivo);
                }
            }
            echo "Archivos borrados exitosamente.";
        } else {
            echo "La ruta no es una carpeta válida.";
        }
        break;

    case 'listarComprobantes':
        $rspta = $pago->listarComprobantes();
        // -- Obtener el ultimo correlativo estatico--
        //Vamos a declarar un array
        $data = array();
        while ($reg = $rspta->fetch_object()) {

            $data[] = array(
                "0" => '<button class="btn btn-warning" onclick="mostrarDetalle(' . $reg->idcomprobante . ',\'' . $reg->cliente . '\');" ><span class="fa fa-plus"></span></button>',
                "1" => $reg->fecha_emision,
                "2" => $reg->cliente,
                "3" => $reg->periodo,
                "4" => $reg->serie . "-" . $reg->correlativo,
                "5" => $reg->costo_total,
                "6" => ($reg->estado == 'Pendiente') ? '<span class="label bg-red">Pendiente</span>' :
                    '<span class="label bg-green">Cancelado</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);
        break;
    case 'listarContrato':
        require_once "../modelos/Registro_servicios.php";
        $registros = new Registro();
        $rspta = $registros->listarContratosActivos();

        $data = array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<input type="checkbox" style="width: 25px; height: 25px;" name="check_list[]" value="' . $reg->id_r_servicio . '">',

                // ' <button class="btn btn-warning" onclick="mostrarVentana()"><i class="fa fa-shopping-cart"></i></button>',
                // '<a target="_blank" href="../reportes/servicios-soporte.php?idsoporte='.$reg->id_p_servicio.'"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>',

                "1" => $reg->cliente,
                "2" => $reg->concepto,
                "3" => $reg->serie . "-" . $reg->correlativo,
                "4" => $reg->costo,
                // "5" => $reg->fecha_termino,
                "5" => '<input type="date" name="fecha_' . $reg->id_r_servicio . '">',
                "6" => '<input type="date" name="ftermino_' . $reg->id_r_servicio . '">'
            );
        }
        $results = array(
            "sEcho" => 1, //Informacion para el datatable
            "iTotalRecords" => count($data), //Enviamos el total de registtros en el datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;

    case 'mostrarDetalle':
        $idcomprobante = $_GET['id'];
        $cont = 0;
        $rspta = $pago->mostrardetalle($idcomprobante);
        echo '<thead style="background-color:#A9D0F5">                          
            <th>Opciones</th>
            <th>CLIENTE</th>
            <th>CONCEPTO</th>
            <th>MONTO</th>
            </thead>';
        while ($reg = $rspta->fetch_object()) {
            echo '<tr class="filas" id="fila' . $cont . '">' .
                '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' . $cont . ')">X</button></td>' .
                '<td><input type="hidden" name="idarticulo[]" value="' . $reg->idcliente . '"><input type="hidden" name="idcomprobante" value="' . $reg->idcomprobante . '">' . $reg->cliente . '</td>' .
                '<td><input type="hidden" name="u_medida[]" value="' . $reg->concepto . '">' . $reg->concepto . '</td>' .
                '<td ><input type="hidden" name="cantidad[]"  id="cantidad' . $cont . '" style="text-align: center;width:50px;" value="' . $reg->monto_pago . '" >' . $reg->monto_pago . '</td>' .
                '</tr>';
            $cont++;
        }
        break;


    case 'guardar':
        $comprobantesSeleccionados = $_POST['comprobantesSeleccionados'];
        $rspta = $pago->actualizarEstadoComprobantesPagados($comprobantesSeleccionados);
        echo $rspta ? "Comprobantes modificados con éxito" : "Algo salio mal";
        break;

    case 'guardarParaContratos':
        $contratos = $_POST['contratosSeleccionados'];
        $fechasSeleccionadas = $_POST['fechasSeleccionadas'];
        $rspta = $pago->insertarParaContratosSeleccionados($contratos,$fechasSeleccionadas, $idusuario);
        echo $rspta ? "Comprobantes generados con éxito" : "No se pudo generar los comprobantes";
        break;

    case 'obtenerFecha':
        $id_r_servicio = $_GET['id_r_servicio'];
        $rspta = $pago->obtenerFecha($id_r_servicio);
        $row = $rspta->fetch_assoc();
        $fecha = $row['fecha_termino'];
        print_r($fecha);
        break;
}
