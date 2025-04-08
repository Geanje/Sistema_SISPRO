<?php
if(strlen(session_id()) < 1)
session_start();
// require "../config/Conexion.php";

require_once "../modelos/Ingreso.php";


$ingreso=new Ingreso();

$idingreso=isset($_POST["idingreso"])? limpiarCadena($_POST["idingreso"]):"";
$idproveedor=isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]):"";
$idusuario=$_SESSION["idusuario"];
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$serie_comprobante=isset($_POST["serie_comprobante"])? limpiarCadena($_POST["serie_comprobante"]):"";
$num_comprobante=isset($_POST["num_comprobante"])? limpiarCadena($_POST["num_comprobante"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$total_compra=isset($_POST["total_compra"])? limpiarCadena($_POST["total_compra"]):"";

$credito=isset($_POST["credito"])? limpiarCadena($_POST["credito"]):""; //string si o no
$cuota=isset($_POST["cuota"])? limpiarCadena($_POST["cuota"]):""; // int
$valorcuota=isset($_POST["valorcuota"])? limpiarCadena($_POST["valorcuota"]):""; //int
$tipocredito=isset($_POST["tipocredito"])? limpiarCadena($_POST["tipocredito"]):"";//string
$fechainicio=isset($_POST["fechainicio"])? limpiarCadena($_POST["fechainicio"]):""; // date
$diapago=isset($_POST["diapago"])? limpiarCadena($_POST["diapago"]):""; //int

$idpago=isset($_POST["idpago"])? limpiarCadena($_POST["idpago"]):""; 
$id_ingreso=isset($_POST["id_ingreso"])? limpiarCadena($_POST["id_ingreso"]):""; 
$valor_cuota=isset($_POST["valor_cuota"])? limpiarCadena($_POST["valor_cuota"]):""; 
$fecha_cuota=isset($_POST["fecha_cuota"])? limpiarCadena($_POST["fecha_cuota"]):""; 


switch ($_GET["op"])
{
    case 'guardaryeditar':
      if(empty($idingreso))
      {

        if(isset($_POST["fechasElegidas"])){
            $rspta=$ingreso->insertar($idproveedor,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$impuesto,$total_compra,$_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_compra"],$_POST["precio_venta"],$credito,$cuota,$valorcuota,$tipocredito,$fechainicio,$diapago,$_POST["fechasElegidas"],$_POST["serieIngreso"],$_POST["codigod"]);

        }else{
            $fechasElegidas="";
            $rspta=$ingreso->insertar($idproveedor,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$impuesto,$total_compra,$_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_compra"],$_POST["precio_venta"],$credito,$cuota,$valorcuota,$tipocredito,$fechainicio,$diapago,$fechasElegidas,$_POST["serieIngreso"],$_POST["codigod"]);
          
        }
        echo $rspta ? "Ingreso registrado" : "No se puedieron registrar todos los datos del ingreso";
        // echo $rspta ;
        // echo json_encode($rspta);
      }
      else {
        {

        }
      }
    break;

    case 'guardaryeditarListadoCuotas':
      if(empty($idpago)){
        $rspta = $ingreso->insertarListadoCuotas($id_ingreso,$valor_cuota,$fecha_cuota);
        echo $rspta ? "Registro aceptado" : "No se pudo registrar";
      }else{
         $rspta = $ingreso->editarListadoCuotas($idpago,$id_ingreso,$valor_cuota,$fecha_cuota);
         echo $rspta ? "Registro editado" : "No se pudo editar";
      }
    break;

    case 'anular':
      $rspta=$ingreso->anular($idingreso);
      echo $rspta ? "Ingreso anulado" : "Ingreso no se puede anular";
    break;

    case 'virificarPago':
      $rspta=$ingreso->virificarPago($idingreso);
      $reg = $rspta->fetch_object();
      echo json_encode($reg);
      // echo $rspta ? "Ingreso anulado" : "Ingreso no se puede anular";
    break;

    
    case 'anularCompraCredito':
      $rspta=$ingreso->anularCompraCredito($idingreso);
      echo $rspta ? "Compra Cancelada" : "Compra no se puede cancelar";
    break;

    case 'mostrar':
      $rspta=$ingreso->mostrar($idingreso);
      //codificar el resultado usando json
      echo json_encode($rspta);
    break;

    case 'listarDetalle':
      //RECIBIMOS ID Ingreso
      $id=$_GET['id'];
      $rspta=$ingreso->listarDetalle($id);
      $total=0;
      echo '<thead style="background-color:#A9D0F5">
          <th>Opciones</th>
          <th>Articulo</th>
          <th>Cantidad</th>
          <th>Precio compra</th>
          <th></th>
          <th>Precio venta</th>
          <th>Subtotal</th>

        </thead>';


      while ($reg = $rspta->fetch_object())
  				{
  					echo '<tr class="filas"><td></td><td>'.$reg->nombre.'</td><td>'.$reg->cantidad.'</td><td>'.$reg->precio_compra.'</td><td></td><td>'.$reg->precio_venta.'</td><td>'.$reg->precio_compra*$reg->cantidad.'</td></tr>';
            $total=$total+($reg->precio_compra*$reg->cantidad);
      }

      echo '<tfoot>
        <th>TOTAL</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th><h4 id="total">S/.'.$total.'</h4><input type="hidden" name="total_compra" id="total_compra"></th>
      </tfoot>';
     
    break;


/*    case 'listar':


      $rspta=$ingreso->listar();
      //Vamos a declarar un array
      $data=Array();
      while($reg=$rspta->fetch_object())
      {

        if($reg->estado == "Aceptado"){
          $arg = '<button class="btn btn-success" onclick="mostrar('.$reg->idingreso.')"><i class="fa fa-eye"></i></button>'.
          ' <button class="btn btn-danger" onclick="anular('.$reg->idingreso.')"><i class="fa fa-close"></i></button>';

          $cantcuota = '-';
          $valcuota = '-';

          $argEstado = '<span class="label bg-green">Aceptado<span>';

        }else if($reg->estado == "Pendiente"){
          $arg = '<button class="btn btn-success" onclick="mostrar('.$reg->idingreso.')"><i class="fa fa-eye"></i></button> '.'<button class="btn btn-warning" id="mostrarCuotas" onclick="listarCuotas('.$reg->idingreso.')"><i class="fa fa-shopping-cart"></i></button>'.
          ' <button class="btn btn-danger" onclick="anularCompraCredito('.$reg->idingreso.')"><i class="fa fa-close"></i></button>';
          $cantcuota = $reg->num_cuotas;
          $valcuota = $reg->valor_cuota;
          $argEstado = '<span class="label bg-red">'.$reg->estado.'<span>';
          
        }

        $data[]=array(
          "0"=>$arg,
          "1"=>$reg->fecha,
          "2"=>$reg->proveedor,
          "3"=>$reg->usuario,
          "4"=>$reg->tipo_comprobante,
          "5"=>$reg->serie_comprobante.'-'.$reg->num_comprobante,
          "6"=>$cantcuota,
          "7"=>$valcuota,
          "8"=>$reg->total_compra,
          "9"=>$argEstado
        );
      }
      $results= array(
        "sEcho"=>1, //Informacion para el datatable
        "iTotalRecords"=>count($data),//Enviamos el total de registtros en el datatable
        "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
        "aaData"=>$data);
      echo json_encode($results);

    break;*/
      case 'listar':

      $params = $columns = $totalRecords = $data = array();
      
      $params = $_REQUEST;
        $columns = array(
          0 => 'i.idingreso',
          1 => 'i.fecha_hora', 
          2 => 'p.nombre',
          3 => 'u.nombre',
          4 => 'i.tipo_comprobante',
          5 => 'i.idingreso',
          6 => 'i.idingreso',
          7 => 'i.idingreso',
          8 => 'i.total_compra',
          9 => 'i.estado'
        );

      $where_condition = $sqlTot = $sqlRec = "";

      if( !empty($params['search']['value']) ) {
        $where_condition .= " WHERE ";
        $where_condition .= " ( u.nombre LIKE '%".$params['search']['value']."%' ";    
        $where_condition .= " OR p.nombre LIKE '%".$params['search']['value']."%' ";    
        $where_condition .= " OR DATE(i.fecha_hora) LIKE '%".$params['search']['value']."%' ";
        $where_condition .= " OR i.tipo_comprobante LIKE '%".$params['search']['value']."%' ";
        $where_condition .= " OR concat(i.serie_comprobante,'-',i.num_comprobante) LIKE '%".$params['search']['value']."%' ";
        $where_condition .= " OR i.estado LIKE '%".$params['search']['value']."%' )";
        $where_condition .= " and ( i.estado = 'Aceptado' or i.estado = 'Pendiente')  ";    
      }else{
        $where_condition .= " WHERE ";
        $where_condition .= " ( i.estado = 'Aceptado' or i.estado = 'Pendiente')  ";    
      }

      // $sql_query=$ingreso->listar();

      $sql_query = "SELECT i.idingreso,DATE(i.fecha_hora) as fecha, i.idproveedor,p.nombre as proveedor, u.idusuario, u.nombre as usuario, i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.num_cuotas,i.valor_cuota,i.total_compra,i.impuesto,i.estado FROM ingreso i inner join persona p on i.idproveedor=p.idpersona inner join usuario u on i.idusuario=u.idusuario ";

      $sqlTot .= $sql_query;
      $sqlRec .= $sql_query;

      if(isset($where_condition) && $where_condition != '') {

        $sqlTot .= $where_condition;
        $sqlRec .= $where_condition;
      }

      $sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";
      // $sqlRec .=  "  LIMIT ".$params['start']." ,".$params['length']." ";
      $queryTot = ejecutarConsulta($sqlTot);

      $totalRecords = $queryTot->num_rows;//cantidad de registros
      $queryRecords = ejecutarConsulta($sqlRec);



      // $rspta=$ingreso->listar();
      //Vamos a declarar un array
      $data=Array();
      while($reg=$queryRecords->fetch_object())
      {

        if($reg->estado == "Aceptado"){
          $arg = '<button class="btn btn-success" onclick="mostrar('.$reg->idingreso.')"><i class="fa fa-eye"></i></button>'.
          ' <button class="btn btn-danger" onclick="anular('.$reg->idingreso.')"><i class="fa fa-close"></i></button>'.
          ' <a target="_blank" href="../reportes/ingreso.php?idingreso='.$reg->idingreso.'"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>';

          $cantcuota = '-';
          $valcuota = '-';

          $argEstado = '<span class="label bg-green">Aceptado<span>';

        }else if($reg->estado == "Pendiente"){
          $arg = '<button class="btn btn-success" onclick="mostrar('.$reg->idingreso.')"><i class="fa fa-eye"></i></button> '.'<button class="btn btn-warning" id="mostrarCuotas" onclick="listarCuotas('.$reg->idingreso.')"><i class="fa fa-shopping-cart"></i></button>'.
          ' <button class="btn btn-danger" onclick="anularCompraCredito('.$reg->idingreso.')"><i class="fa fa-close"></i></button>';
          $cantcuota = $reg->num_cuotas;
          $valcuota = $reg->valor_cuota;
          $argEstado = '<span class="label bg-red">'.$reg->estado.'<span>';
          
        }

        $data[]=array(
          "0"=>$arg,
          "1"=>$reg->fecha,
          "2"=>$reg->proveedor,
          "3"=>$reg->usuario,
          "4"=>$reg->tipo_comprobante,
          "5"=>$reg->serie_comprobante.'-'.$reg->num_comprobante,
          "6"=>$cantcuota,
          "7"=>$valcuota,
          "8"=>$reg->total_compra,
          "9"=>$argEstado
        );
      }
      $results= array(
        "draw"            => intval( $params['draw'] ),   
        "recordsTotal"    =>intval($totalRecords),
        "recordsFiltered" => intval($totalRecords),
        "data"            => $data
      );
      echo json_encode($results);

    break;

    case 'listarCuotas':
      $idingreso=$_REQUEST["idingreso"];

      $rspta = $ingreso->listarCuota($idingreso);
      $data = Array();
      $num =0;
      while($reg = $rspta->fetch_object())
      {
        $num++;
        $data[] = array(
          "0"=>'<button class="btn btn-danger" onclick="cancelar('.$reg->idingreso.','.$reg->idpago.')"><i class="fa fa-shopping-cart"></i></button>
                <button class="btn btn-warning" data-toggle="modal" data-target="#modalListadoCuotas" onclick="mostrarListaCuota('.$reg->idpago.')"><i class="fa fa-edit"></i></button>',
          "1"=>$num,
          "2"=>$reg->serie_comprobante."-".$reg->num_comprobante,
          "3"=>$reg->valor_cuota,
          "4"=>$reg->fecha_pago,
          "5"=>($reg->estado=='Pendiente')?'<span class="label bg-red">Pendiente<span>':'<span class="label bg-green">Cancelado<span>'
        );
       
      }
      $results = array(
          "sEcho"=>1, //Informacion para el datatable
          "iTotalRecords"=>count($data),//Enviamos el total de registros en el datatable
          "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
          "aaData"=>$data);
        echo json_encode($results);

    break;

    case 'mostrarSaldoAFavor':
        $saldoPagado=0;
        $rspta = $ingreso->mostrarSaldoAFavor($id_ingreso);

        while($reg = $rspta->fetch_object()){
           if($reg->estado == "Cancelado"){
              $saldoPagado += $reg->valor_cuota;
            }
            $total_compra = $reg->total_compra;
        }
        $saldoDiferencial = $total_compra - $saldoPagado;
        $enviar = array("total_compra"=>$total_compra,"saldoFavor"=>$saldoPagado,"saldoDiferencial"=>$saldoDiferencial);
        echo json_encode($enviar);
    break;

    case 'mostrarListaCuota':
      $rspta = $ingreso->mostrarListaCuota($idpago);
      echo json_encode($rspta);

    break;


    case 'cancelarLetra':
      $idpago = $_POST["idpago"];
      $rspta=$ingreso->cancelarLetra($idpago);
      echo $rspta ? "Cuota cancelada" : "Cuota no se puede cancelar";
    break;

    case 'selectProveedor':
      require_once "../modelos/Persona.php";
      $persona=new Persona();

      $rspta=$persona->listarP();

      while($reg=$rspta->fetch_object())
      {
        echo '<option value='. $reg->idpersona.'>'. $reg->nombre.'</option>';
      }

    break;

    case 'listarArticulos':
    require_once "../modelos/Articulo.php";
    $articulo=new Articulo();

    $rspta=$articulo->listarActivos();
    //Vamos a declarar un array
    $data= Array();

    while ($reg=$rspta->fetch_object()){
      $data[]=array(
        "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\',\''.$reg->codigo.'\')"><span class="fa fa-plus"></span></button>',
        "1"=>$reg->nombre,
        "2"=>$reg->categoria,
        "3"=>$reg->codigo,
        "4"=>$reg->stock,
        "5"=>$reg->afectacion
        // "6"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >"
        );
    }
    $results = array(
      "sEcho"=>1, //Información para el datatables
      "iTotalRecords"=>count($data), //enviamos el total registros al datatable
      "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
      "aaData"=>$data);
    echo json_encode($results);


    break;

}

 ?>
