<?php

require_once "../modelos/Configuracion.php";

$configuracion = new Configuracion();


$razon_social = isset($_POST["razon_social"]) ? limpiarCadena($_POST["razon_social"]) : "";
$nombre_comercial = isset($_POST["nombre_comercial"]) ? limpiarCadena($_POST["nombre_comercial"]) : "";
$ruc = isset($_POST["ruc"]) ? limpiarCadena($_POST["ruc"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
$distrito = isset($_POST["distrito"]) ? limpiarCadena($_POST["distrito"]) : "";
$provincia = isset($_POST["provincia"]) ? limpiarCadena($_POST["provincia"]) : "";
$departamento = isset($_POST["departamento"]) ? limpiarCadena($_POST["departamento"]) : "";
$codigo_postal = isset($_POST["codigo_postal"]) ? limpiarCadena($_POST["codigo_postal"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
$email = isset($_POST["email"]) ? limpiarCadena($_POST["email"]) : "";
$web = isset($_POST["web"]) ? limpiarCadena($_POST["web"]) : "";
$pais = isset($_POST["pais"]) ? limpiarCadena($_POST["pais"]) : "";
$fecha_inicio = isset($_POST["fecha_inicio"]) ? limpiarCadena($_POST["fecha_inicio"]) : "";
$direccion2 = isset($_POST["direccion2"]) ? limpiarCadena($_POST["direccion2"]) : "";
$rubro = isset($_POST["rubro"]) ? limpiarCadena($_POST["rubro"]) : "";
$modo = isset($_POST['modo']) ? limpiarCadena($_POST['modo']) : "";
$imagenactual = isset($_POST['imagenactual']) ? limpiarCadena($_POST['imagenactual']) : "";
$usuario=  isset($_POST['u_secundario_user']) ? limpiarCadena($_POST['u_secundario_user']) : "";
$password= isset($_POST['u_secundario_password']) ? limpiarCadena($_POST['u_secundario_password']) : "";

switch ($_GET["op"]) {
	case 'editar':

		if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
			$imagen = $imagenactual;
		} else {
			$ext = explode(".", $_FILES["imagen"]["name"]);
			if (!empty($imagenactual)) {
				unlink("../files/perfil/" . $imagenactual);
			}
			$imagen = round(microtime(true)) . '.' . $ext[1];
			move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/perfil/" . $imagen);
		}

		$rspta = $configuracion->editar($razon_social, $nombre_comercial, $ruc, $direccion, $distrito, $provincia, $departamento, $codigo_postal, $telefono, $email, $web, $imagen, $pais, $fecha_inicio, $direccion2, $rubro, $modo);
		echo $rspta ? "Datos actualizados satisfactoriamente" : "Lo siento algo ha salido mal intenta nuevamente";
		break;

	case 'editar_sunat':
			$rspta = $configuracion->editar_sunat($modo, $razon_social, $nombre_comercial, $ruc, $direccion, $distrito, $provincia, $departamento, $usuario,$password);
			echo $rspta ? "Datos actualizados satisfactoriamente" : "Lo siento algo ha salido mal intenta nuevamente";
			break;
}
