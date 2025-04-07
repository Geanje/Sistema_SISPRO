

 <?php
require_once "../modelos/Rentabilidad.php";

$reporte=new Rentabilidad();


// $mes=isset($_POST["mes"])? limpiarCadena($_POST["mes"]):"";

switch ($_GET["op"]){
	case 'listarRentabilidad':
	      $mes=$_REQUEST["mes"];
	      $anno=$_REQUEST["anno"];
	      $porcentaje=$_REQUEST["porcentaje"];

	 	  $rspta1=$reporte->listarRentabilidadCompras($mes,$anno,$porcentaje);
	 	  $rspta2=$reporte->listarRentabilidadVentas($mes,$anno,$porcentaje);
	 	  $data=array();
	 	  $data = ["dato1"=>$rspta1,"dato2"=>$rspta2];
	 	  echo json_encode($data);

	      // $data=Array();
	      // $reg1=$rspta1->fetch_object();
	      // $reg2=$rspta2->fetch_object();

	      // while($reg1=$rspta1->fetch_object())
	      // {
	      // 	if($reg1->compras == "0"){
	      // 		  $data[]=array(
		     //      "0"=>"",
		     //      "1"=>"No hay registros en la fecha seleccionada.",
		     //      "2"=>"",
		     //      "3"=>"" );
	      // 	}else{

	      //   $data[]=array(
	      //     "0"=>$reg1->compras,
	      //     "1"=>$reg1->compras,
	      //     "2"=>$reg1->compras,
	      //     "3"=>$reg1->compras );
	      //     // "3"=>$reg2->ventas - ($reg1->compras + $reg2->porcentaje)
	      // 	}
	         
	      //   // );
	      // }
	      // $results= array(
	      //   "sEcho"=>1, //Informacion para el datatable
	      //   "iTotalRecords"=>count($data),//Enviamos el total de registtros en el datatable
	      //   "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
	      //   "aaData"=>$data);
	      // echo json_encode($results);

		
		
	break;

}

