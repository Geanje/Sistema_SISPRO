<?php

require_once "conexion.php";

class ModeloMotivoTranslado{

    static public function mdlMostrarMotivoTranslado($tabla){
        $stmt = Conexion::conectar()->prepare("SELECT descripcion_traslado FROM $tabla");
        $stmt -> execute();
        return $stmt -> fetchAll();
        $stmt = null;
    }
}

class ModeloDireccionPuntoPartida{
    static public function mdlMostrarDireccionPuntoPartida($tabla){
        $stmt = Conexion::conectar()->prepare("SELECT direccion FROM $tabla");
        $stmt -> execute();
        return $stmt -> fetchAll();
        $stmt = null;
    }
}


class ModeloGia{
    static public function mdlResgistroGia($tabla,$tabla2,$datos){

        $seriemax_guia= Conexion::conectar()->prepare("SELECT serie_correlativo_guia FROM guia ORDER BY serie_correlativo_guia DESC");
        $seriemax_guia -> execute();
        $seriemax_guia = $seriemax_guia -> fetch();
        $seriemax_guia = $seriemax_guia["serie_correlativo_guia"];
        // $seriemax_guia = var_dump($seriemax_guia["serie_correlativo_guia"]);
        // $seriemax_guia = $seriemax_guia["max('serie_correlativo_guia')"];

        if($seriemax_guia){
            $seriemax_guia = str_replace("T001-","",$seriemax_guia);
            $seriemax_guia = intval($seriemax_guia);
            $serieact_guia = $seriemax_guia +1;
        }else{
            $serieact_guia = 1;
        }
        $correlativo_guia =  str_pad($serieact_guia,8,"0",STR_PAD_LEFT);
        $serie_correlativo_guia = "T001-".$correlativo_guia;
        


        $serie= Conexion::conectar()->prepare("SELECT serie, correlativo FROM venta WHERE idventa = :idventa");
        $serie->bindParam(":idventa", $datos["id_venta"], PDO::PARAM_STR);
        $serie -> execute();
        $serie= $serie -> fetch();
        $serie_correlativo = $serie["serie"].'-'.$serie["correlativo"];


        $br= Conexion::conectar();

        $stmt = $br->prepare("INSERT INTO $tabla(id_cliente,id_venta,serie_correlativo,serie_correlativo_guia,id_tranportista,id_moti_translado,dire_partida,dire_llegada,moda_transportista,tipo_translado,peso_bruto,fecha_emision,fecha_translado)
        VALUES (:id_cliente,:id_venta,:serie_correlativo,:serie_correlativo_guia,:id_tranportista,:id_moti_translado,:dire_partida,:dire_llegada,:moda_transportista,:tipo_translado,:peso_bruto,:fecha_emision,:fecha_translado)");

        $stmt->bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_STR);
        $stmt->bindParam(":id_venta", $datos["id_venta"], PDO::PARAM_STR);
        $stmt->bindParam(":serie_correlativo", $serie_correlativo, PDO::PARAM_STR);
        $stmt->bindParam(":serie_correlativo_guia", $serie_correlativo_guia, PDO::PARAM_STR);
        $stmt->bindParam(":id_tranportista", $datos["id_tranportista"], PDO::PARAM_STR);
        $stmt->bindParam(":id_moti_translado", $datos["id_moti_translado"], PDO::PARAM_STR);
        $stmt->bindParam(":dire_partida", $datos["dire_partida"], PDO::PARAM_STR);
        $stmt->bindParam(":dire_llegada", $datos["dire_llegada"], PDO::PARAM_STR);
        $stmt->bindParam(":moda_transportista", $datos["moda_transportista"], PDO::PARAM_STR);
        $stmt->bindParam(":tipo_translado", $datos["tipo_translado"], PDO::PARAM_STR);
        $stmt->bindParam(":peso_bruto", $datos["peso_bruto"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_emision", $datos["fecha_emision"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_translado", $datos["fecha_translado"], PDO::PARAM_STR);
        $stmt->execute();
        $result = $br->lastInsertId();

        for ($x = 0; $x < count($datos["idarticulo"]); $x++) {
            $stmt2 = Conexion::conectar()->prepare("INSERT INTO $tabla2(id_guia,id_articulo,cantidad,serie)
            VALUES (:id_guia,:id_articulo,:cantidad,:serie)");

            $stmt2->bindParam(":id_guia", $result, PDO::PARAM_STR);
            $stmt2->bindParam(":id_articulo", $datos["idarticulo"][$x], PDO::PARAM_STR);
            $stmt2->bindParam(":cantidad", $datos["cantidad"][$x], PDO::PARAM_STR);
            $stmt2->bindParam(":serie", $datos["serie"][$x], PDO::PARAM_STR);
            $stmt2->execute();
        }

        if(true){

			return "ok";

		}else{

			return "error";
		
		}

        $seriemax_guia = null;
        $serie_correlativo = null;
        $stmt = null;
        $serie = null;
        $stmt2 = null;
    }
}
