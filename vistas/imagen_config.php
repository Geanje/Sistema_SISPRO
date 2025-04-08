<?php
	require_once ("../config/conexion.php");
			
	
				
	if (isset($_FILES["imagefile"])){
			
			$imageFileZise=$_FILES["imagefile"]["size"];


			$logo=$_FILES["imagefile"]["name"];
			$ext = explode(".", $_FILES["imagefile"]["name"]);
			if($imageFileZise>1048576){
					$errors[]= "<p>Lo sentimos, pero el archivo es demasiado grande. Selecciona logo de menos de 1MB</p>";
			}elseif ($_FILES['imagefile']['type'] == "imagefile/jpg" || $_FILES['imagefile']['type'] == "image/jpeg" || $_FILES['imagefile']['type'] == "image/png")
			{
				$imagen =  $_FILES["imagefile"]["name"];
				move_uploaded_file($_FILES["imagefile"]["tmp_name"], "../files/perfil/" . $imagen);
			

				$consulta="UPDATE perfil SET logo='$logo' where idperfil='1'";
				$query_new_insert=mysqli_query($conexion,$consulta);


				if ($query_new_insert) {
                        ?>
						<img class="img-responsive" src="../files/perfil/<?php echo $logo;?>" alt="Logo" width="300px">
						<?php
                    } else {
                        $errors[]= "Lo sentimos, actualización falló. Intente nuevamente. ".mysqli_error($conexion);
                    }
            }
            else{
            	$errors[]= "<p>Lo sentimos, sólo se permiten archivos JPG , JPEG y PNG </p>";
            }
// round(microtime(true)). '.' . end($ext);

}

		if (isset($errors)){
	?>
		<div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Error! </strong>
		<?php
			foreach ($errors as $error){
				echo $error;
			}
		?>
		</div>	
	<?php
			}
?>