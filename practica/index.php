<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practicantes - Registro</title>
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
            <img src="img/logo.png" alt="Canvas Logo" style="float: right; margin-top: -30px;">
        </a><br><br><br><br>    
      </div>
      <h1>Registro de  Practicantes</h1>
      <div class="container">
        <form class="form-contact" action="registro.php" method="post" class="form-group">
          <label class="datos" for="">Nombres y Apellidos:</label><br>  
          <input type="text" class="form-contact-input" name="nombres" placeholder="Ingresar nombres y apellidos" required>
          <label for="">Institucion:</label><br> 
          <input type="text" class="form-contact-input" name="institucion" placeholder="Ingresar el nombre de la institucion" required> 
          <label for="">Documento de Identidad DNI:</label><br>            
          <input type="text" id="dni" class="form-contact-input"  name="dni" placeholder="Ingresar numero" maxlength="8" required oninput="this.value = this.value.replace(/[^0-9]/g, ''); checkDNI(this.value);">
          <span id="dni-error" style="color: red; display: none;">DNI ya existente</span><br>  
          <label for="">Sede:</label><br>  
          <input type="text" class="form-contact-input"  name="sede" placeholder="Ingresar la sede de su institucion" required><br>  
          <label for="">Area:</label><br>           
          <select class="form-contact-input" id="especialidad" name="especialidad" onchange="crearSelect()" required="">
            <option disabled selected value="">Selecciona el area de tu practica</option>
            <option value="Desarrollo de Software y Facturación Electrónica">Desarrollo de Software y Facturación Electrónica</option>    
            <option value="Soporte Técnico y Tecnología en Seguridad, Redes & Infraestructura">Soporte Técnico y Tecnología en Seguridad, Redes & Infraestructura</option>
            <option value="Instalaciones Eléctricas y Mantenimiento Eléctrico">Instalaciones Eléctricas y Mantenimiento Eléctrico</option>
            <option value="Diseño Gráfico">Diseño Gráfico</option>
            <option value="Administración">Administración</option>
          </select>
          <label for="">Modalidad:</label><br>  
          <!-- <input type="text" class="form-contact-input"  name="modalidad" placeholder="Ingresar la modalidad de practica (remoto o presencial) " required><br>  -->
          <select class="form-contact-input" id="modalidad" name="modalidad" onchange="crearSelect()" required="">
            <option disabled selected value="">Selecciona la modalidad de tu practica</option>            
            <option value="Remoto">Remoto</option>
            <option value="Presencial">Presencial</option>
          </select> 
          <label for="">Correo:</label><br>  
          <input type="email" class="form-contact-input" name="correo" placeholder="Ingresar su correo" required><br>  
          <label for="">N&deg; Celular:</label><br>  
          <input type="text" class="form-contact-input"  name="celular" placeholder="Ingresar numero " required maxlength="9" oninput="this.value = this.value.replace(/[^0-9]/g, '')"><br>  
          <label for="">Fecha de inicio:</label><br>  
          <input type="date" class="form-contact-input" name="fecha_inicio" placeholder="Ingresar " required><br>  
          <label for="">Fecha de termino:</label><br>  
          <input type="date" class="form-contact-input" name="fecha_termino" placeholder="Ingresar " required><br>  
          <input class="form-contact-button" type="button" id="button" value="Enviar" onclick="checkDNIAndSubmit();">

        </form>
      </div>
    </div>
  </div>
  <script src="js/check_dni.js"></script>
</body>
</html>