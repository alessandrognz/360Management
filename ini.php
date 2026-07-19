<?php
  require_once 'includes/db.php';

  if($_SERVER['REQUEST_METHOD']=== 'POST') {

    $email = $_REQUEST['email'];
    $contrasena = $_POST['contrasena'] ?? '';

    INICIAR_SESION($email, $contrasena);

  }
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="Icons/logo.png" />
    <link rel="stylesheet" href="css/index.css" />
    <title>Iniciar Sesión</title>
  </head>
  <body>
    <div class="container">
      <h1>Bienvenido a 360Management!</h1>
      <p class="subtitle">Introduce tus credenciales para acceder</p>

      <form action="ini.php" method="POST">
        <div class="field">
          <label for="email">Correo electrónico</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" />
        </div>

        <div class="field">
          <div class="label-row">
            <label for="contrasena">Contraseña</label>
            <a href="#" class="forgot-link">He olvidado mi contraseña</a>
          </div>
          <input type="password" id="contrasena" name="contrasena" placeholder="Introduce tu contraseña" />
        </div>

        <div class="remember-row">
          <input type="checkbox" id="recuerdame" name="recuerdame" />
          <label for="recuerdame">Recuérdame</label>
        </div>

        <button type="submit">Iniciar Sesión</button>
      </form>

      <p class="switch-link">¿Aún no tienes cuenta? <a href="registr.php">Regístrate</a></p>
    </div>
  </body>
</html>