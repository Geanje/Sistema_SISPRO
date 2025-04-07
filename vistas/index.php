<?php
ob_start();

session_start();

if (isset($_SESSION['nombre'])) {
  header("Location:escritorio.php");
} else {
?>

  <!DOCTYPE html>
  <html lang="es">

  <head>
    <title>Sistema Gestión Comercial</title>
    <link rel="shortcut icon" href="images/icono.png">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta http-equiv="Content-Language” content=”es" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="js/sweet-alert.min.js"></script>
    <link rel="stylesheet" href="css/sweet-alert.css">
    <link rel="stylesheet" href="css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">
    <!-- <link rel="stylesheet" type="text/css" href="../public/css/sweetalert.css"> -->


    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>
      window.jQuery || document.write('<script src="js/jquery-1.11.2.min.js"><\/script>')
    </script>
    <script src="js/modernizr.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/main.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  </head>

  <body class="full-cover-background" style="background-image:url(images/factura02.png);width: 100%; height: 100%;">

    <div class="box">
      <div id="mens" class="label label-default "></div>
      <?php
      require_once "../modelos/Perfil.php";
      $perfil = new Perfil();
      $rspta = $perfil->cabecera_perfil();
      // $rspta= Perfil::cabecera_perfil();
      $reg = $rspta->fetch_assoc();
      $rucp = $reg['ruc'];
      $razon_social = $reg['razon_social'];
      $nombre_comercial = $reg['nombre_comercial'];
      $direccion = $reg['direccion'];
      $direccion2 = $reg['direccion2'];
      $distrito = $reg['distrito'];
      $provincia = $reg['provincia'];
      $departamento = $reg['departamento'];
      $telefono = $reg['telefono'];
      $email = $reg['email'];
      $logo = $reg['logo'];

      ?>




      <div class="form-container">
        <h2 align="center">SISTEMA GESTIÓN DE COMERCIAL <br>
          <b><?php echo $nombre_comercial; ?><br></b>

          <?php echo $direccion; ?>

        </h2>
        <p class="text-center">
          <i class=""><img src="images/logo5.png"></i>
        </p>
        <h4 class="text-center all-tittles">inicia sesion con tu cuenta</h4>
        <form action="#" method="post" id="frmAcceso">
          <div class="group-material-login">
            <input type="text" id="logina" pattern="[A-Za-z0-9_\-]{1,20}" name="logina" class="material-login-control" autofocus required="" maxlength="70">
            <span class="highlight-login"></span>
            <span class="bar-login"></span>
            <label><i class="zmdi zmdi-account"></i> &nbsp; Usuario</label>
          </div>
          <div class="group-material-login">
            <input type="password" id="clavea" name="clavea" class="material-login-control" required="" maxlength="70">
            <span class="highlight-login"></span>
            <span class="bar-login"></span>
            <label><i class="zmdi zmdi-lock"></i> &nbsp; Clave de Acceso</label>
          </div>

          <!-- <input type="submit" id="submit-btn" class="btn" value="Login"> -->
          <div class="recaptcha-container">
            <div class="g-recaptcha" data-sitekey="6Lc2Z2cmAAAAAPnX3NUuToyIK7XTN80QUGGbwF9F"></div>
          </div><br>


          <button class="btn-login" type="submit">INGRESAR &nbsp; <i class="zmdi zmdi-arrow-right"></i></button>

        </form>
      </div>

      <script type="text/javascript" src="scripts/login.js"></script>
      <!-- <script src="../public/js/sweetalert.min.js"></script> -->
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  </body>

  </html>

<?php
}
?>