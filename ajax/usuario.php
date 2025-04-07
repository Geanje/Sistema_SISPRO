<?php
session_start();
date_default_timezone_set('America/Lima');
require_once "../modelos/Usuario.php";

$usuario = new Usuario();

$idusuario = isset($_POST["idusuario"]) ? limpiarCadena($_POST["idusuario"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$tipo_documento = isset($_POST["tipo_documento"]) ? limpiarCadena($_POST["tipo_documento"]) : "";
$num_documento = isset($_POST["num_documento"]) ? limpiarCadena($_POST["num_documento"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
$email = isset($_POST["email"]) ? limpiarCadena($_POST["email"]) : "";
$cargo = isset($_POST["cargo"]) ? limpiarCadena($_POST["cargo"]) : "";
$login = isset($_POST["login"]) ? limpiarCadena($_POST["login"]) : "";
$clave = isset($_POST["clave"]) ? limpiarCadena($_POST["clave"]) : "";
$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";


switch ($_GET["op"]) {
	case 'guardaryeditar':

		if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
			$imagen = $_POST["imagenactual"];
		} else {
			$ext = explode(".", $_FILES["imagen"]["name"]);
			if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png") {
				$imagen = round(microtime(true)) . '.' . end($ext);
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuarios/" . $imagen);
			}
		}

		//HASH SHA256
		$clavehash = hash("SHA256", $clave);

		if (empty($idusuario)) {
			$rspta = $usuario->insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clavehash, $imagen, $_POST['permiso']);
			echo $rspta ? "Usuario registrado" : "No se puedieron registrar todos los datos del usuario";
		} else {
			$rspta = $usuario->editar($idusuario, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clavehash, $imagen, $_POST['permiso']);
			echo $rspta ? "Usuario actualizado" : "Usuario no se pudo actualizar";
		}
		break;

	case 'desactivar':
		$rspta = $usuario->desactivar($idusuario);
		echo $rspta ? "Usuario Desactivado" : "Usuario no se puede desactivar";
		break;
		break;

	case 'activar':
		$rspta = $usuario->activar($idusuario);
		echo $rspta ? "Usuario activado" : "Usuario no se puede activar";
		break;
		break;

	case 'mostrar':
		$rspta = $usuario->mostrar($idusuario);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
		// break;
		break;

	case 'listar':
		$rspta = $usuario->listar();
		//Vamos a declarar un array
		$data = array();

		while ($reg = $rspta->fetch_object()) {
			$data[] = array(
				"0" => ($reg->condicion) ? '<button class="btn btn-warning" onclick="mostrar(' . $reg->idusuario . ')"><i class="fa fa-pencil"></i></button>' .
					' <button class="btn btn-danger" onclick="desactivar(' . $reg->idusuario . ')"><i class="fa fa-close"></i></button>' :
					'<button class="btn btn-warning" onclick="mostrar(' . $reg->idusuario . ')"><i class="fa fa-pencil"></i></button>' .
					' <button class="btn btn-primary" onclick="activar(' . $reg->idusuario . ')"><i class="fa fa-check"></i></button>',
				"1" => $reg->nombre,
				"2" => $reg->tipo_documento,
				"3" => $reg->num_documento,
				"4" => $reg->telefono,
				"5" => $reg->email,
				"6" => $reg->login,

				"7" => "<img src='../files/usuarios/" . $reg->imagen . "' style='height: 70px; width: 70px; object-fit: cover;' >",
				"8" => ($reg->condicion) ? '<span class="label bg-green">Activado</span>' :
					'<span class="label bg-red">Desactivado</span>'
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

	case 'permisos':

		require_once "../modelos/Permiso.php";
		$permiso = new Permiso();
		$rspta = $permiso->listar();

		//Obtener los permisos asignados al usuarios
		$id = $_GET['id'];
		$marcados = $usuario->listarmarcados($id);
		//DEcalaramos el array para alamacenar todos los permisos marcados
		$valores = array();

		//Almacenar los permisos asignados al usuario en el Array
		while ($per = $marcados->fetch_object()) {
			array_push($valores, $per->idpermiso);
		}


		//MOstramos la lsita de permisos en la vista y si están o no marcados
		while ($reg = $rspta->fetch_object()) {
			$sw = in_array($reg->idpermiso, $valores) ? 'checked' : '';
			echo '<li><input type="checkbox" ' . $sw . ' name="permiso[]" value="' . $reg->idpermiso . '">' . $reg->nombre . '</li>';
		}


		break;

	case 'verificar':
		$logina = $conexion->real_escape_string($_POST['logina']);
		$clavea = $conexion->real_escape_string($_POST['clavea']);
		$query = $usuario->buscarUsuario($logina);
		$obj = $query->fetch_object();
		$tiempo_bloqueo = $obj->tiempo_bloqueo;
		$condicion = $obj->condicion;

		
		// die();
		if ($tiempo_bloqueo !== null && strtotime($tiempo_bloqueo) > time()) {
			$tiempo_restante = strtotime($tiempo_bloqueo) - time();
			echo json_encode(array("success" => false, "message" => "El usuario está bloqueado. Por favor, inténtelo nuevamente despues de " . gmdate("H:i:s", $tiempo_restante)));
			break;
		}elseif($condicion == 0){
			echo json_encode(array("success" => false, "message" => "Usuario está bloqueado. Por favor, contacte con el administrador del sistema"));
			break;
		}

		$clavehash = hash("SHA256", $clavea);
		$rspta = $usuario->verificar($logina, $clavehash);
		$fetch = $rspta->fetch_object();



		if (isset($fetch)) {
			// Declaramos las variables de sesión

			$_SESSION['idusuario'] = $fetch->idusuario;
			$_SESSION['nombre'] = $fetch->nombre;
			$_SESSION['imagen'] = $fetch->imagen;
			$_SESSION['login'] = $fetch->login;


			$usuario->resetIntentosFallidos($logina);

			// Obtener los permisos del usuario
			$marcados = $usuario->listarmarcados($fetch->idusuario);
			$valores = array();

			while ($per = $marcados->fetch_object()) {
				array_push($valores, $per->idpermiso);
			}

			// Determinar los accesos del usuario
			$_SESSION['escritorio'] = in_array(1, $valores) ? 1 : 0;
			$_SESSION['almacen'] = in_array(2, $valores) ? 1 : 0;
			$_SESSION['compras'] = in_array(3, $valores) ? 1 : 0;
			$_SESSION['ventas'] = in_array(4, $valores) ? 1 : 0;
			$_SESSION['servicio'] = in_array(5, $valores) ? 1 : 0;
			$_SESSION['sucursal'] = in_array(6, $valores) ? 1 : 0;
			$_SESSION['consultac'] = in_array(7, $valores) ? 1 : 0;
			$_SESSION['consultav'] = in_array(8, $valores) ? 1 : 0;
			$_SESSION['consultal'] = in_array(9, $valores) ? 1 : 0;
			$_SESSION['acceso'] = in_array(10, $valores) ? 1 : 0;
			$_SESSION['administracion'] = in_array(11, $valores) ? 1 : 0;
			$_SESSION['contabilidad'] = in_array(12, $valores) ? 1 : 0;
			$_SESSION['configuracion'] = in_array(13, $valores) ? 1 : 0;
			$_SESSION['soporte'] = in_array(14, $valores) ? 1 : 0;
			$_SESSION['desarrollo'] = in_array(15, $valores) ? 1 : 0;
			$_SESSION['pag_servicio'] = in_array(16, $valores) ? 1 : 0;
			$_SESSION['mantenimiento'] = in_array(17, $valores) ? 1 : 0;
			$_SESSION['descarga'] = in_array(18, $valores) ? 1 : 0;

			echo json_encode(array("success" => true, "usuario" => $fetch));
		} else {
			$usuario->incrementarIntentosFallidos($logina);


			if ($usuario->desactivarusuario($logina)) {
				$usuario->desact_usuario($logina);
				
			}elseif($usuario->excedeIntentosFallidos($logina)) {
				$usuario->bloquearUsuario($logina);
			}
			

			echo json_encode(array("success" => false, "message" => "Usuario o contraseña incorrectos"));
		}


		break;

	case 'salir':
		//limpiamos las variables de session
		session_unset();
		//destruimos la sesion
		session_destroy();
		//redireccionamos al login
		header("Location: ../index.php");

		break;
}
