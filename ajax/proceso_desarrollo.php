<?php 
require_once "../modelos/proceso_desarrollo.php";
$proceso= new Proceso_desarrollo();

$idproc_desarrollo = isset($_POST['idproc_desarrollo']) ? limpiarCadena($_POST['idproc_desarrollo']) : "";
$iddesarrollo=isset($_POST['iddesarrollo']) ? limpiarCadena($_POST['iddesarrollo']) : "";

$AN_fecha_inicio = isset($_POST['AN_fecha_inicio']) ? limpiarCadena($_POST['AN_fecha_inicio']) : "";
$AN_fecha_termino = isset($_POST['AN_fecha_termino']) ? limpiarCadena($_POST['AN_fecha_termino']) : "";
$AN_estado = isset($_POST['AN_estado']) ? limpiarCadena($_POST['AN_estado']) : "";
$AN_comentario = isset($_POST['AN_comentario']) ? limpiarCadena($_POST['AN_comentario']) : "";

$DI_fecha_inicio = isset($_POST['DI_fecha_inicio']) ? limpiarCadena($_POST['DI_fecha_inicio']) : "";
$DI_fecha_termino = isset($_POST['DI_fecha_termino']) ? limpiarCadena($_POST['DI_fecha_termino']) : "";
$DI_estado = isset($_POST['DI_estado']) ? limpiarCadena($_POST['DI_estado']) : "";
$DI_comentario = isset($_POST['DI_comentario']) ? limpiarCadena($_POST['DI_comentario']) : "";

$DE_fecha_inicio = isset($_POST['DE_fecha_inicio']) ? limpiarCadena($_POST['DE_fecha_inicio']) : "";
$DE_fecha_termino = isset($_POST['DE_fecha_termino']) ? limpiarCadena($_POST['DE_fecha_termino']) : "";
$DE_estado = isset($_POST['DE_estado']) ? limpiarCadena($_POST['DE_estado']) : "";
$DE_comentario = isset($_POST['DE_comentario']) ? limpiarCadena($_POST['DE_comentario']) : "";

$IM_fecha_inicio = isset($_POST['IM_fecha_inicio']) ? limpiarCadena($_POST['IM_fecha_inicio']) : "";
$IM_fecha_termino = isset($_POST['IM_fecha_termino']) ? limpiarCadena($_POST['IM_fecha_termino']) : "";
$IM_estado = isset($_POST['IM_estado']) ? limpiarCadena($_POST['IM_estado']) : "";
$IM_comentario = isset($_POST['IM_comentario']) ? limpiarCadena($_POST['IM_comentario']) : "";

$MAN_fecha_inicio = isset($_POST['MAN_fecha_inicio']) ? limpiarCadena($_POST['MAN_fecha_inicio']) : "";
$MAN_fecha_termino = isset($_POST['MAN_fecha_termino']) ? limpiarCadena($_POST['MAN_fecha_termino']) : "";
$MAN_estado = isset($_POST['MAN_estado']) ? limpiarCadena($_POST['MAN_estado']) : "";
$MAN_comentario = isset($_POST['MAN_comentario']) ? limpiarCadena($_POST['MAN_comentario']) : "";

switch ($_GET['op']) {
    case 'editar': 
            $rspta = $proceso->editarproceso(
                $idproc_desarrollo,
                $AN_fecha_inicio, $AN_fecha_termino, $AN_estado, $AN_comentario, 
                $DI_fecha_inicio, $DI_fecha_termino, $DI_estado, $DI_comentario, 
                $DE_fecha_inicio, $DE_fecha_termino, $DE_estado, $DE_comentario, 
                $IM_fecha_inicio, $IM_fecha_termino, $IM_estado, $IM_comentario, 
                $MAN_fecha_inicio, $MAN_fecha_termino, $MAN_estado, $MAN_comentario
            );
            if ($rspta) {
                echo "Actualizado con éxito!";
                // Redirección en JavaScript después de 2 segundos (ajusta el valor según tus necesidades)
                //echo "<script>setTimeout(function() { window.location.href = 'desarrollo-soporte.php'; }, 2000);</script>";
            } else {
                echo "No se pudo actualizar";
            }
            break;
    }

?>
