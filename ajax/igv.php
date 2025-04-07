<?php

    require_once "../modelos/igv.php";

    $igv = new Igv();

    $idIGV=isset($_POST["idIGV"])?limpiarCadena($_POST["idIGV"]) : "";
    $porcentaje=isset($_POST["porcentaje"])?limpiarCadena($_POST["porcentaje"]):"";

switch ($_GET['op']){
    case 'agregarIgv':
        if (empty($idIGV)) {
            $rspta=$igv->insertarIgv($porcentaje);
                echo $rspta ? "Igv Actualizado" : "Algo a salido mal, vuelve a intentarlo";
        }else{
            echo ("No entra a la condicion");
        }
}
   
            
    


