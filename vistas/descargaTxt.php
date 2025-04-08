<?php

ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
	header("Location: login.html");
} else {

	require_once("../config/Conexion.php");
	require 'header.php';

	//$sqll="SELECT * FROM perfil where idperfil='1' ";
	//$quer=mysqli_query($conexion,$sqll);
	//$row=mysqli_fetch_array($quer);

	$sqll = "SELECT * FROM igv where idIGV='1' ";
	$quer = mysqli_query($conexion, $sqll);
	$row = mysqli_fetch_array($quer);

?>
	<div class="content-wrapper"> <br>
		<div class="col-md-12" id="valorIgv">
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">Descargar archivos</h3>
				</div><BR><BR><BR><BR>

				<div class="container">
					<a><p type="submit"onclick="window.location.href='list_files.php'" class="animated-word">DOWNLOAD</p></a>
				</div><BR><BR><BR><BR><BR><BR>
				    	<!-- <div class="panel-body"> -->

				<!-- <input type="submit" name="ejecutar" value="Ejecutar"> -->
			<!-- 	<button onclick="window.location.href='list_files.php'">Descargar</button>-->


				<!-- <a href="list_files.php">Descargar archivos</a> -->
			</div>
			<style>
				button {
					background: cyan;
				}
			</style>


		</div>
	</div>
	</div>
	</div>
	<!-- </section> -->
	</div>
	<?php
	require 'footer.php';
	?>
	<!-- <script type="text/javascript" src="scripts/igv.js"></script> -->

<?php
}
ob_end_flush();
?>