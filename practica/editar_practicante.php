<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Practicante</title>
    <link rel="icon" href="img/logo.ico">
    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" href="css/formulario.css" type="text/css" />
    <script type="text/javascript" src="js/reporte-incidencias.js"></script>
</head>
<body>
    <div class="color-fondo">
        <div class="container">
            <div class="marco">
                <div id="logo1" class="clearfix">
                    <a  class="retina-logo" data-dark-logo="images/logo-dark@2x.png">
                        <img src="img/logo.png" alt="Canvas Logo" style="float: left; margin-top: -30px;">
                    </a><br><br><br><br>    
                </div>
                <h1>Editar Registro Practicante</h1>
                <?php
                    require_once "../config/Conexion.php";
                    require_once "../modelos/practicantes.php";

                    $idpracticante = $nombresApellidos = $dni = $institucion = $sede = $especialidad = $modalidad = $correo = $numero = $fechaInicio = $fechaTermino = $estado = $grupo = $tarea= "";

                    if (isset($_GET['id'])) {
                        $idpracticante = $_GET['id'];
                        $practicantes = new Practicantes();
                        $resultado = $practicantes->mostrar($idpracticante);

                        if (is_array($resultado) && count($resultado) > 0) {
                            $nombresApellidos = $resultado['nombres_apellidos'];
                            $institucion = $resultado['institucion'];
                            $dni = $resultado['dni'];
                            $sede = $resultado['sede'];
                            $especialidad = $resultado['especialidad'];
                            $modalidad = $resultado['modalidad'];
                            $numero = $resultado['numero'];
                            $correo = $resultado['correo'];
                            $fechaInicio = $resultado['fecha_inicio'];
                            $fechaTermino = $resultado['fecha_termino'];
                            $estado = $resultado['estado'];
                            $grupo = $resultado['grupo'];
                            $tarea = $resultado['tarea'];

                        }    
                    } else {
                        header("Location: mostrar.php");
                        exit();
                    }
                        
                ?>
                <div class="container">
                    <form class="form-contact" action="procesar_edicion_practicante.php" method="post" class="form-group">
                        <input type="hidden" name="idpracticante" value="<?= $idpracticante ?>">                        
                        <label class="datos" for="">Nombres y Apellidos:</label><br>
                        <input type="text"  class="form-contact-input" name="nombres_apellidos" value="<?= $nombresApellidos ?>">

                        <label class="datos" for="">Institucion:</label><br>
                        <input type="text" class="form-contact-input" name="institucion" class="form-control" value="<?= $institucion ?>" required>
                        <label for="">DNI:</label><br>
                        <input type="text" class="form-contact-input" name="dni" class="form-control" value="<?= $dni ?>" required>
                        <label for="">Sede:</label><br>
                        <input type="text"  class="form-contact-input" name="sede" class="form-control" value="<?= $sede ?>" required>
                        <label for="">Especialidad:</label><br>
                        <input type="text"  class="form-contact-input" name="especialidad" class="form-control" value="<?= $especialidad ?>" required>
                        <label for="">Modalidad:</label><br>
                        <input type="text" class="form-contact-input"  name="modalidad" class="form-control" value="<?= $modalidad ?>" required>
                        <label for="">Correo:</label><br>
                        <input type="text" class="form-contact-input"  name="correo" class="form-control" value="<?= $correo ?>" required>
                        <label for="">N&deg;Celular:</label><br>
                        <input type="text" class="form-contact-input"  name="celular" class="form-control" value="<?= $numero ?>" required max="9">
                        <label for="">Fecha de inicio:</label><br>
                        <input type="date" class="form-contact-input"  name="fecha_inicio" class="form-control" value="<?= $fechaInicio ?>" required>
                        <label for="">Fecha de termino:</label><br>
                        <input type="date" class="form-contact-input"  name="fecha_termino" class="form-control" value="<?= $fechaTermino ?>" required>
                        <label for="">Estado:</label><br>
                        <input type="text" class="form-contact-input"  name="estado" class="form-control" value="<?= $estado ?>" required> 
            
                        <input class="form-contact-button" type="submit" id="button" value="Guardar Cambios" >
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="js/check_dni.js"></script>
</body>
</html>
