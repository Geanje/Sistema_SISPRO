<?php
require_once "../modelos/Persona.php";

$persona=new Persona();


$idpersona=isset($_POST["idpersona"])? limpiarCadena($_POST["idpersona"]):"";
$tipo_persona=isset($_POST["tipo_persona"])? limpiarCadena($_POST["tipo_persona"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$tipo_documento=isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
$num_documento=isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$razon_social=isset($_POST["razon_social"])? limpiarCadena($_POST["razon_social"]):"";
$marca=isset($_POST["marca"])? limpiarCadena($_POST["marca"]):"";
$placa=isset($_POST["placa"])? limpiarCadena($_POST["placa"]):"";
$licencia_conducir=isset($_POST["licencia_conducir"])? limpiarCadena($_POST["licencia_conducir"]):"";


$numRUCSunat=isset($_POST["numRUCSunat"])? limpiarCadena($_POST["numRUCSunat"]):"";
$numDNISunat=isset($_POST["numDNISunat"])? limpiarCadena($_POST["numDNISunat"]):"";

switch ($_GET["op"])
{
    case 'guardaryeditar':
      if(empty($idpersona))
      {
        $rspta=$persona->insertar($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$razon_social,$marca,$placa,$licencia_conducir);
        echo intval($rspta);
        // echo $rspta ? "Usuario registrado" : "No se puedieron registrar todos los datos del usuario";
      }
      else {
        
          $rspta=$persona->editar($idpersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$razon_social,$marca,$placa,$licencia_conducir);
          echo intval($rspta);
        //   echo $rspta ? "Usuario actualizado" : "Usuario no se pudo actualizar";
        }
      
    break;
      

    case 'eliminar':
      $rspta=$persona->eliminar($idpersona);
      echo $rspta ? "Persona eliminada" : "Persona no se pudo eliminar";
    break;



    case 'mostrar':
      $rspta=$persona->mostrar($idpersona);
      //codificar el resultado usando json
      echo json_encode($rspta);
    break;

    case 'listarp':
      $rspta=$persona->listarp();
      //Vamos a declarar un array
      $data=Array();
      while($reg=$rspta->fetch_object())
      {
        $data[]=array(
          "0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.
          ' <button class="btn btn-danger" onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash"></i></button>',
          "1"=>$reg->nombre,
          "2"=>$reg->tipo_documento,
          "3"=>$reg->num_documento,
          "4"=>$reg->telefono,
          "5"=>$reg->direccion,
          "6"=>$reg->email,
          "7"=>$reg->razon_social
        );
      }
      $results= array(
        "sEcho"=>1, //Informacion para el datatable
        "iTotalRecords"=>count($data),//Enviamos el total de registtros en el datatable
        "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
        "aaData"=>$data);
      echo json_encode($results);



    break;

  case 'listarc':
    $rspta=$persona->listarc();
    //Vamos a declarar un array
    $data=Array();
    while($reg=$rspta->fetch_object())
    {
      $data[]=array(
        "0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.
        ' <button class="btn btn-danger" onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash"></i></button>',
        "1"=>$reg->nombre,
        "2"=>$reg->tipo_documento,
        "3"=>$reg->num_documento,
        "4"=>$reg->telefono,
        "5"=>$reg->direccion,
        "6"=>$reg->email,
        "7"=>$reg->razon_social
      );
    }
    $results= array(
      "sEcho"=>1, //Informacion para el datatable
      "iTotalRecords"=>count($data),//Enviamos el total de registtros en el datatable
      "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
      "aaData"=>$data);
    echo json_encode($results);



  break;
  
  case 'listarcs':
    $rspta=$persona->listarcs();
    //Vamos a declarar un array
    $data=Array();
    while($reg=$rspta->fetch_object())
    {
      $data[]=array(
        "0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.
        ' <button class="btn btn-danger" onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash"></i></button>',
        "1"=>$reg->nombre,
        "2"=>$reg->tipo_documento,
        "3"=>$reg->num_documento,
        "4"=>$reg->telefono,
        "5"=>$reg->direccion,
        "6"=>$reg->email,
        "7"=>$reg->razon_social
      );
    }
    $results= array(
      "sEcho"=>1, //Informacion para el datatable
      "iTotalRecords"=>count($data),//Enviamos el total de registtros en el datatable
      "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
      "aaData"=>$data);
    echo json_encode($results);
  break;

  case 'listart':
    $rspta=$persona->listart();
    //Vamos a declarar un array
    $data=Array();
    while($reg=$rspta->fetch_object())
    {
      $data[]=array(
        "0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.
        ' <button class="btn btn-danger" onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash"></i></button>',
        "1"=>$reg->nombre,
        "2"=>$reg->tipo_documento,
        "3"=>$reg->num_documento,
        "4"=>$reg->telefono,
        "5"=>$reg->direccion,
        "6"=>$reg->email,
        "7"=>$reg->razon_social,
        "8"=>$reg->marca,
        "9"=>$reg->placa,
        "10"=>$reg->licencia_conducir

      );
    }
    $results= array(
      "sEcho"=>1, //Informacion para el datatable
      "iTotalRecords"=>count($data),//Enviamos el total de registtros en el datatable
      "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
      "aaData"=>$data);
    echo json_encode($results);



  break;


  case 'listars':
    $rspta=$persona->listarsucursal();
    //Vamos a declarar un array
    $data=Array();
    while($reg=$rspta->fetch_object())
    {
      $data[]=array(
        "0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.
        ' <button class="btn btn-danger" onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash"></i></button>',
        "1"=>$reg->nombre,
        "2"=>$reg->tipo_documento,
        "3"=>$reg->num_documento,
        "4"=>$reg->telefono,
        "5"=>$reg->direccion,
        "6"=>$reg->email,
        "7"=>$reg->razon_social
      );
    }
    $results= array(
      "sEcho"=>1, //Informacion para el datatable
      "iTotalRecords"=>count($data),//Enviamos el total de registtros en el datatable
      "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
      "aaData"=>$data);
    echo json_encode($results);



  break;

  case 'consultaSunat':
    // require_once("../public/sunat/src/autoload.php");
    
    // $company = new \Sunat\Sunat( true, true );

    if($numRUCSunat != ""){
        $ruc = $numRUCSunat;
        // --
        $data = file_get_contents('https://api.sunat.cloud/ruc/' . $ruc);
        // --
        try {
            if ($data) {
                // --
                header("Content-type: application/json; charset=utf-8");
                echo json_encode($data);
            } else {
                echo "error";
            }
        } catch (Exception  $e) {
            echo "error";
        }

    //   $search = $company->search( $ruc );

    //   if( $search )
    //   {
    //     echo json_encode($search);
    //   }else{
    //     echo "error";
    //   }
    }

    if($numDNISunat != ""){
        $dni = $numDNISunat;
        // --
        $data = file_get_contents('https://api.reniec.cloud/dni/' . $dni);
        // --
        try {
            if ($data) {
                // --
                // $data =  json_encode($data, true);
                header("Content-type: application/json; charset=utf-8");
                echo json_encode($data, true);
            } else {
                echo "error";
            }
        } catch (Exception  $e) {
            echo "error";
        }
    //   $search = $company->search( $dni );
    //   if( $search )
    //   {
    //     echo json_encode($search);
    //   }else{
    //     echo "error";

    //   }
    }

      
    
    
    // $search1 = $company->search( $ruc );
    // $search2 = $company->search( $dni );
    
    
    // if( $search1->success == true )
    // {
    //   echo "Empresa: " . $search1->result->RazonSocial;
    // }
    
    // if( $search2->success == true )
    // {
    //   echo "Persona: " . $search1->result->RazonSocial;
    // }
    
    // echo $search1->json();
    // echo $search1->xml('empresa');

}

 ?>
