<?php
require_once "../modelos/Pedido.php";

$pedido = new Pedido();

$idventa=isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";


switch ($_GET["op"]){
    case "listar" :
        $rspta=$pedido->listar();
        $data = array();
        while ($reg = $rspta->fetch_object()) {
            // $reg->idventa
            $url = "../reportes/TicketPedido.php?id=$reg->idventa";
            $data[] = array(
                '0' => '
                        <a class="btn btn-info" target="_blank" href="'.$url.'"><i class="fa fa-file"></i></a>',
                '1' => $reg->fecha,
                '2' => $reg->cliente,
                '3' => $reg->usuario,
                '4' => $reg->documento,
                '5' => $reg->numero,
                '6' => $reg->total_venta,
                '7' => ($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
                '<span class="label bg-red">Anulado</span>',
            );
        }
        echo json_encode($data);
        break;
    case "mostrar" : 
        $data = $pedido->mostrar($idventa);
        echo json_encode($data);
        break;
    case 'selectCliente':
            require_once "../modelos/Persona.php";
            $persona = new Persona();
            $rspta = $persona->listarC();
            while ($reg = $rspta->fetch_object())
                    {
                    echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
                    }
        break;
    
}

?>