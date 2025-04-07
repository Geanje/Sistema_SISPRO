<?php

require_once '../config/Conexion.php';
date_default_timezone_set("America/Lima");

class Pagos
{
    public function __construct()
    {
    }

    public function insertar($id_r_servicio, $idusuario, $costo_total, $mesTermino, $fecha_termino, $fecha_corte, $fecha_emision)
    {
        $saber = "SELECT serie, correlativo FROM comprobantes";
        $saberExiste = ejecutarConsultaSimpleFila($saber);
        if ($saberExiste["serie"] == null && $saberExiste["correlativo"] == null) {
            $serie = 'R0001';
            $correlativo = '00000001';
        } else {
            $sqlmaxserie = "SELECT max(serie) as maxSerie FROM comprobantes";
            $maxserie = ejecutarConsultaSimpleFila($sqlmaxserie);
            $serie = $maxserie["maxSerie"];
            $ultimoCorrelativo = "SELECT max(correlativo) as ultimocorrelativo FROM comprobantes WHERE serie='$serie'";
            $ultimo = ejecutarConsultaSimpleFila($ultimoCorrelativo);
            if ($ultimo["ultimocorrelativo"] == '99999999') {
                $ser = substr($serie, 1) + 1;
                $seri = str_pad((string)$ser, 4, "0", STR_PAD_LEFT);
                $serie = "S" . $seri;
                $correlativo = '00000001';
            } else {
                $corre = $ultimo["ultimocorrelativo"] + 1;
                $correlativo = str_pad($corre, 8, "0", STR_PAD_LEFT);
            }
        }
 
        $sql = "INSERT INTO comprobantes (id_r_servicio, idusuario, periodo, costo, fecha_emision, serie, correlativo, estado, fecha_termino, fecha_corte)
            VALUES ('$id_r_servicio', '$idusuario', '$mesTermino','$costo_total', '$fecha_emision', '$serie', '$correlativo', 'Pendiente', '$fecha_termino', '$fecha_corte')";
       $idcomprobante = ejecutarConsulta_retornarID($sql);
 
    //    var_dump($idcomprobante);
        require '../reportes/Comprobante_PDF_Pago_Servicios.php';
   
    }

    public function insertarParaContratosActivos($id_r_servicio, $idusuario)
    {
        // Crear una fecha con el formato Y-m-d 
        $fechaAct = date('Y-m-d');
        $fechaActual = date_create(date('Y-m-d'));
        $timestampActual = strtotime($fechaAct);
        // Obtener el día y el mes de la fecha actual 
        $diaActual = date_format($fechaActual, 'd');
        $mesActual = date_format($fechaActual, 'm');
        $sql = "SELECT id_r_servicio, fecha_inicio, fecha_termino, costo_total FROM registro_servicio WHERE estado = '1'";
        $result = ejecutarConsulta($sql);
        // Iterar sobre los registros obtenidos y llamar a la función insertar
        $nombreMeses = array(
            '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
            '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
            '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
        );
        while ($row = mysqli_fetch_assoc($result)) {
            $id_r_servicio = $row['id_r_servicio'];
            $fecha_inicio = $row['fecha_inicio'];
            $fecha_termino = $row['fecha_termino'];
            $costo_total = $row['costo_total'];
            // Crear una fecha con el valor de fecha_termino
            $fecha_cort = new DateTime($fecha_termino);
            $fecha_emision = date('Y m d');

            // Sumar 10 días a la fecha
            $fecha_cort->modify('+10 days');
            // Obtener la nueva fecha en el formato deseado ('Y-m-d')
            $fecha_corte = $fecha_cort->format('Y-m-d');
            $fechaTermino = date_create($fecha_termino);
            // Obtener el día y el mes de la fecha termino
            $diaTermino = date_format($fechaTermino, 'd');
            $mesTermino = date_format($fechaTermino, 'm');
            $timestampTermino = strtotime($fecha_termino);
            // Si el día actual es 10 y el mes actual es igual al mes termino, entonces llamar a las funciones
            if (($diaActual >= '1' && $mesActual == $mesTermino) || $timestampTermino <= $timestampActual) {
                $nombreMesTermino = $nombreMeses[$mesTermino];
                // Llamar a la función insertar con los valores obtenidos
                $this->insertar($id_r_servicio, $idusuario, $costo_total, $nombreMesTermino, $fecha_termino, $fecha_corte, $fecha_emision);
                $this->cambiarEstado($id_r_servicio, $fecha_inicio, $fecha_termino);
            }
        }
        // var_dump($fecha_termino);
        return $result;
    }
    public function insertarParaContratosSeleccionados($contratos, $fechasSeleccionadas, $idusuario)
    {
        $fechaAct = date('Y-m-d');
        $fechaActual = date_create(date('Y-m-d'));
        $timestampActual = strtotime($fechaAct);
        $diaActual = date_format($fechaActual, 'd');
        $mesActual = date_format($fechaActual, 'm');
        $idsComprobantes = implode(',', $contratos);
        $sql = "SELECT id_r_servicio, fecha_inicio, fecha_termino, costo_total FROM registro_servicio WHERE id_r_servicio IN ($idsComprobantes)";
        $result = ejecutarConsulta($sql);
        $nombreMeses = array(
            '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
            '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
            '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
        );
        while ($row = mysqli_fetch_assoc($result)) {
            $id_r_servicio = $row['id_r_servicio'];
            $fecha_inicio = $row['fecha_inicio'];
            $fecha_termino = $row['fecha_termino'];
            $costo_total = $row['costo_total'];    
            if (isset($fechasSeleccionadas[$id_r_servicio])) {
                $fecha_corte = $fechasSeleccionadas[$id_r_servicio]['fechaInicio'];
                $fecha_emision = $fechasSeleccionadas[$id_r_servicio]['fechaEmision'];
                $fechaTermino = date_create($fecha_termino);
                $diaTermino = date_format($fechaTermino, 'd');
                $mesTermino = date_format($fechaTermino, 'm');
                $timestampTermino = strtotime($fecha_termino);
                if (($diaActual >= '1' && $mesActual == $mesTermino) || $timestampTermino <= $timestampActual) {
                    $nombreMesTermino = $nombreMeses[$mesTermino];
                    $this->insertar($id_r_servicio, $idusuario, $costo_total, $nombreMesTermino, $fecha_termino, $fecha_corte, $fecha_emision);
                    $this->cambiarEstado($id_r_servicio, $fecha_inicio, $fecha_termino);
                }
                // var_dump($fechasSeleccionadas);
            }
        }
        return $result;
    }


    public function actualizarEstadoComprobantesPagados($comprobantesSeleccionados)
    {
        $idsComprobantes = implode(',', $comprobantesSeleccionados);
        $sql = "UPDATE comprobantes SET estado = 'Pagado' WHERE idcomprobante IN ($idsComprobantes)";
        return ejecutarConsulta($sql);
    }

    public function cambiarEstado($id_r_servicio, $fecha_inicio, $fecha_termino)
    {
        // Crear un objeto DateTime a partir de la cadena fecha_inicio
        $fechaInicio = new DateTime($fecha_inicio);
        // Sumar un mes al objeto DateTime
        // $fechaInicio->modify('+1 month');
        // Obtener la cadena con el formato Y-m-d
        $fechaInicio->modify('first day of next month');
        $newFechaInicio = $fechaInicio->format('Y-m-d');

        // Crear un objeto DateTime a partir de la cadena fecha_termino
        $fechaTermino = new DateTime($fecha_termino);
        // Sumar un mes al objeto DateTime
        $fechaTermino->modify('+1 month');
        // Ajustar al último día del mes
        $fechaTermino = $fechaInicio->modify('last day of this month');
        // Obtener la cadena con el formato Y-m-d
        $newFechaTermino = $fechaTermino->format('Y-m-d');

        // Actualizar la base de datos con los nuevos valores
        $sql = "UPDATE registro_servicio AS rs
        INNER JOIN servicio AS s ON s.idservicio = rs.idservicio
        SET rs.costo_total = s.costo,
            rs.fecha_inicio = '$newFechaInicio',
            rs.fecha_termino = '$newFechaTermino'
        WHERE rs.id_r_servicio = '$id_r_servicio'";
        return ejecutarConsulta($sql);
    }

    public function editar($idcomprobante, $id_r_servicio, $idusuario, $serie, $correlativo, $periodo, $fecha_emision, $estado)
    {
        $sql = "UPDATE comprobantes SET 
        id_r_servicio = '$id_r_servicio',
        idusuario = '$idusuario',
        serie = '$serie',
        correlativo = '$correlativo',
        periodo = '$periodo',
        fecha_emision = '$fecha_emision',
        estado = '$estado'
        WHERE idcomprobante='$idcomprobante'";
        return ejecutarConsulta($sql);
    }

    public function listar()
    {
        $sql = "SELECT c.idcomprobante, c.fecha_emision, p.nombre AS cliente, s.nombre AS servicio, c.costo,rs.fecha_inicio, c.periodo, c.serie, c.correlativo, c.estado, rs.id_r_servicio,    CONCAT(rs.serie, '-', rs.correlativo) AS rs_serie_correlativo

        FROM comprobantes c
        INNER JOIN registro_servicio rs ON rs.id_r_servicio = c.id_r_servicio
        INNER JOIN servicio s ON s.idservicio = rs.idservicio
        INNER JOIN persona p ON rs.idcliente = p.idpersona";
        return ejecutarConsulta($sql);
    }

    public function comprobantePDF($idcomprobante)
    {
        $sql = "SELECT c.idcomprobante, c.fecha_emision, p.nombre AS cliente, s.nombre AS servicio, c.costo,rs.fecha_inicio, c.periodo, c.serie, c.correlativo, c.estado, rs.id_r_servicio, p.direccion, p.num_documento, p.tipo_documento, p.telefono, p.email as correo, u.nombre as usuario, s.nombre as concepto, c.fecha_termino, c.fecha_corte
        FROM comprobantes c
        INNER JOIN registro_servicio rs ON rs.id_r_servicio = c.id_r_servicio
        INNER JOIN servicio s ON s.idservicio = rs.idservicio
        INNER JOIN usuario u ON rs.idusuario=u.idusuario 
        INNER JOIN persona p ON rs.idcliente = p.idpersona
        WHERE c.idcomprobante = '$idcomprobante'";
        return ejecutarConsulta($sql);
    }

    public function guardarFechaCorte($fecha_corte, $idcomprobante)
    {
        // var_dump($fecha_corte);
        $sql = "UPDATE comprobantes SET fecha_corte = '$fecha_corte' WHERE idcomprobante = '$idcomprobante'";
        return ejecutarConsulta($sql);
    }

    public function mostrar($idcomprobante)
    {
        $sql = "SELECT c.fecha_emision, p.nombre, s.nombre AS servicio, rs.costo_total, c.periodo, c.serie, c.correlativo, c.estado, rs.id_r_servicio,c.fecha_corte
        FROM comprobantes c
        INNER JOIN registro_servicio rs ON rs.id_r_servicio = c.id_r_servicio
        INNER JOIN servicio s ON s.idservicio = rs.idservicio
        INNER JOIN persona p ON rs.idcliente = p.idpersona
        WHERE c.idcomprobante = '$idcomprobante'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function mostrardetalle($idcomprobante)
    {
        $sql = "SELECT c.id_r_servicio, c.idusuario, c.serie, c.correlativo, c.periodo, c.fecha_emision, c.estado, rs.idcliente, s.nombre AS concepto, rs.costo_total AS monto_pago, p.nombre as cliente
        FROM comprobantes c
        INNER JOIN registro_servicio rs ON rs.id_r_servicio = c.id_r_servicio
        INNER JOIN persona p ON p.idpersona = rs.idcliente
        INNER JOIN servicio s ON s.idservicio = rs.idservicio
        WHERE idcomprobante = '$idcomprobante'";
        return ejecutarConsulta($sql);
    }

    //funcion para listar los contratos del cliente seleccionado
    public function listarContratos()
    {
        $cliente = $_REQUEST["idcliente"];
        $sql = "SELECT rs.id_r_servicio, rs.concepto, rs.fecha_inicio, rs.estado, p.nombre as cliente, p.direccion, p.telefono, p.num_documento, rs.idcliente
        FROM registro_servicio rs
        INNER JOIN persona p ON p.idpersona = rs.idcliente
        WHERE rs.idcliente = '$cliente'";
        return ejecutarConsulta($sql);
    }

    public function listarComprobantes()
    {
        $cliente = $_REQUEST["idcliente"];
        $sql = "SELECT rs.id_r_servicio, rs.fecha_inicio, rs.estado, p.nombre as cliente, p.direccion, p.telefono, p.num_documento, rs.idcliente, rs.costo_total, c.periodo, c.fecha_emision, c.estado, c.serie, c.correlativo, c.idcomprobante
        FROM registro_servicio rs
        INNER JOIN persona p ON p.idpersona = rs.idcliente
        INNER JOIN servicio s ON s.idservicio = rs.idservicio
        INNER JOIN comprobantes c ON rs.id_r_servicio = c.id_r_servicio
        WHERE rs.idcliente = '$cliente'
        AND c.estado = 'Pendiente'";
        return ejecutarConsulta($sql);
    }

    public function obtenerFecha($id_r_servicio)
    {
        $sql = "SELECT fecha_termino FROM registro_servicio WHERE id_r_servicio = '$id_r_servicio'";
        return ejecutarConsulta($sql);
        // $fecha_termino = ejecutarConsulta($sql);
        // print_r($fecha_termino);
    }

    public function actualizarFechaCosto($fecha_termino, $costo, $ids_r_servicio)
    {
        $sql = "UPDATE registro_servicio SET costo = '$costo', fecha_termino = '$fecha_termino' WHERE id_r_servicio IN ($ids_r_servicio)";
        return ejecutarConsulta($sql);
    }
}
