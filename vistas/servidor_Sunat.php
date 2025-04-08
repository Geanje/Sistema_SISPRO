<?php

ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
	header("Location: login.html");
} else {

	//require_once("../config/conexion.php");
	require 'header.php';

	$sqll = "SELECT * FROM perfil_sunat where idperfil='1' ";
	$quer = mysqli_query($conexion, $sqll);
	$row = mysqli_fetch_array($quer);

?>
	<div class="content-wrapper">
		<section class="content">
			<div class="row">
				<div class="col-md-12">
					<div class="box">
						<div class="box-header with-border">
							<h1 class="box-title">Configuración de Servidor</h1>
							<!--      <button class="btn btn-success" id="btnAgregar" onclick="mostrarForm(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div> -->
						</div>
						<!-- /.box-header -->
						<!-- centro -->

						<div class="panel-body" id="formularioRegistros">

							<form method="post" id="perfil" enctype="multipart/form-data">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 toppad">


									<div class="panel ">
										<!--  <div class="panel-heading">
				              <h3 class="panel-title"><i class='glyphicon glyphicon-cog'></i> Configuración</h3>
				            </div> -->
										<div class="panel-body">
											<div class="row">

												<div class="col-md-3 col-lg-3 " align="center">
													<div id="load_img">
														<input type="hidden" name="imagenactual" id="imagenactual" value="<?php echo $row['logo']; ?>">
														<img name="logo" class="img-responsive" src="../public/img/Sunat.jpg" alt="Logo" width="200px" id="previsualizar">

													</div>
													<br>

												</div>
												<div class=" col-md-9 col-lg-9 ">
													<table class="table table-condensed">
														<tbody>
															<tr>
																<td>Modo Envio SUNAT:</td>
																<td>
																	<select type="text" class="form-control input-sm" name="modo" required>
																		<option value="0" <?php if ($row['modo'] == 0) echo ' selected'; ?>>Beta</option>
																		<option value="1" <?php if ($row['modo'] == 1) echo ' selected'; ?>>Produccion</option>
																	</select>
																</td>
															</tr>
															<tr>
																<td>Razon Social:</td>
																<td><input type="text" class="form-control input-sm" name="razon_social" value="<?php echo $row['razon_social'] ?>" required></td>
															</tr>

															<tr>
																<td>Nombre Comercial:</td>
																<td><input type="text" class="form-control input-sm" name="nombre_comercial" value="<?php echo $row['nombre_comercial'] ?>" required></td>
															</tr>

															<tr>
																<td>RUC:</td>
																<td><input type="text" class="form-control input-sm" name="ruc" value="<?php echo $row['ruc'] ?>" required></td>
															</tr>

															<tr>
																<td>Dirección:</td>
																<td><input type="text" class="form-control input-sm" name="direccion" value="<?php echo $row['direccion'] ?>" required></td>
															</tr>

															<tr>
																<td>Distrito:</td>
																<td><input type="text" class="form-control input-sm" name="distrito" value="<?php echo $row['distrito'] ?>" required></td>
															</tr>

															<tr>
																<td>Provincia:</td>
																<td><input type="text" class="form-control input-sm" name="provincia" value="<?php echo $row['provincia'] ?>" required></td>
															</tr>

															<tr>
																<td>Departamento:</td>
																<td><input type="text" class="form-control input-sm" name="departamento" value="<?php echo $row['departamento'] ?>" required></td>
															</tr>

															<tr>
																<td>Usuario Sunat:</td>
																<td><input type="text" class="form-control input-sm" name="u_secundario_user" value="<?php echo $row['u_secundario_user'] ?>" required></td>
															</tr>

															<tr>
																<td>Password Sunat:</td>
																<td><input type="password" class="form-control input-sm" name="u_secundario_password" value="<?php echo $row['u_secundario_password'] ?>" required></td>
															</tr>


														</tbody>
													</table>
												</div>
												<div class='col-md-12' id="resultados_ajax"></div><!-- Carga los datos ajax -->
											</div>
										</div>
										<div class="text-center">
											<button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Actualizar datos</button>
										</div>
									</div>

								</div>
							</form>

						</div>

					</div>
				</div>
			</div>
	</div>
	</section><!-- /.content -->

	</div><!-- /.content-wrapper -->


	</form>




	</div>

	</div>
	</div>
	</div>
	</div>
	</section><!-- /.content -->

	</div><!-- /.content-wrapper -->

	<?php
	require 'footer.php';
	?>
	<script type="text/javascript" src="scripts/servidor.js"></script>

<?php
}
ob_end_flush();
?>