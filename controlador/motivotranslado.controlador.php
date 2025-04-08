<?php

require_once "../modelo/gia.modelo.php";
class ControladorMotivoTranslado{
    static public function ctrMostrarMotivoTranslado(){
        $tabla = "motivo_traslado";
        $respuesta = ModeloMotivoTranslado::mdlMostrarMotivoTranslado($tabla);
        return $respuesta;
    }
}


class ControladorDireccionPuntoPartida{
    static public function ctrMostrarDireccionPuntoPartida(){
        $tabla = "perfil";
        $respuesta = ModeloDireccionPuntoPartida::mdlMostrarDireccionPuntoPartida($tabla);
        return $respuesta;
    }
}

class ControladorGia{
    public function ctrRegistroGia(){        
        // var_dump($_POST["cantidad"]);
        // echo '<script language="javascript">alert('.$_POST["idcliente"].');</script>';

        if(isset($_POST["idpuntofinal"])){
            $datos=array(
                "id_cliente"=>$_POST["idcliente"],
                "id_venta"=>$_POST["listacomprobante"],
                "id_tranportista"=>$_POST["sltransportista"],
                "id_moti_translado"=>$_POST["codigo_traslado"],
                "dire_partida"=>$_POST["idpartida"],
                "dire_llegada"=>$_POST["idpuntofinal"],
                "moda_transportista"=>$_POST["mondalidad_transporte"],
                "tipo_translado"=>$_POST["tipo_translado"],
                "peso_bruto"=>$_POST["peso"],
                "fecha_emision"=>$_POST["fecha_emision"],
                "fecha_translado"=>$_POST["fecha_traslado"],
                "idarticulo"=>$_POST["idarticulo"],
                "cantidad"=>$_POST["cantidad"],
                "serie"=>$_POST["seriearticulo"]
            );

            $tabla = 'guia';
            $tabla2 = 'detalle_guia';
            $respuesta = ModeloGia::mdlResgistroGia($tabla,$tabla2,$datos);
            if(true){
                echo '<script>
                history.back();
                </script>';                
            }        

        }
    }
}

?>