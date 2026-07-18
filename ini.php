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
    <link rel="icon" type="image/png" href="img/logo-favi.png" />
    <link rel="stylesheet" href="css/index.css" />
    <title>Iniciar Sesión</title>
  </head>
  <body>
    <div class="form">
      <h1>
        <a href="ini.php">Iniciar Sesión</a> /
        <a href="registr.php">Registrarse</a>
      </h1>
      <form action="ini.php" method="POST">

        CORREO ELECTRÓNICO:
        <br /><br />
        <input type="email" name="email" placeholder="Introduce tu dirección de correo" />
        <br /><br />

        CONTRASEÑA:
        <br /><br />
        <input type="password" name="contrasena" placeholder="Introduce tu contraseña" />
        <br /><br />

        <button type="submit" class="iniciar-sesion">Iniciar Sesión</button>
      </form>
      <br /><br />
    </div>
  </body>
</html>