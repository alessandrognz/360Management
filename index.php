<?php
  require_once 'includes/db.php';

  if($_SERVER['REQUEST_METHOD']=== 'POST') {

    $email = $_REQUEST['email'];
    $contrasena = $_POST['contrasena'] ?? '';

    INICIAR_SESION($email, $contrasena);

  }
?>
<?php
    require_once 'includes/db.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

			//Obtencion de variables del formulario
      $nombre = $_POST['nombre']??'';
      $email = $_POST['email']??'';
      $puesto = $_POST['puesto']??'';

      $contrasena = $_POST['contrasena'??''];
      $contrasena2 = $_POST['contrasena2']??'';      

			//Consulta
      if($contrasena === $contrasena2){
        $contrasena = password_hash($_POST['contrasena']??'', PASSWORD_BCRYPT);

			  INSERTAR_USUARIO($nombre,$email,$puesto,$contrasena);
      }
      else{
        $mensaje = 'Las contraseñas introducidas deben coincidir.';
        echo "<script>alert('$mensaje');</script>";
      }
		}    
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="assets/icons/logo.png" />
    <link rel="stylesheet" href="assets/css/index.css">
    <title>360Management</title>
  </head>
  <body>
    <div class="landing">
      <div class="logo"><img src="assets/icons/logo+360Management.png" alt="360Management"></div>
      <div class="buttons">
        <button id="btn-login" class="btn btn-primary">Iniciar Sesión</button>
        <button id="btn-registro" class="btn btn-secondary">Registrarse</button>
      </div>
    </div>

    <div class="modal-overlay" id="modal-login">
      <div class="container">
        <button class="modal-close" data-modal="modal-login">&times;</button>
        <h1>Bienvenido a 360Management!</h1>
        <p class="subtitle">Introduce tus credenciales para acceder</p>

        <form action="index.php" method="POST">
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

          <button type="submit">Iniciar Sesión</button>
        </form>

        <p class="switch-link">¿Aún no tienes cuenta? <a href="#" id="switch-registro">Regístrate</a></p>
      </div>
    </div>

    <div class="modal-overlay" id="modal-registro">
      <div class="container2">
        <button class="modal-close" data-modal="modal-registro">&times;</button>
        <h1>Empieza ahora mismo</h1>

      <form action="index.php" method="POST">
        <div class="field">
          <label for="nombre">Nombre</label>
          <input type="text" id="nombre" name="nombre" placeholder="Introduce tu nombre" />
        </div>

        <div class="field">
          <label for="email">Correo electrónico</label>
          <input type="email" id="email" name="email" placeholder="Introduce tu correo electrónico" />
    </div>

        <div class="field">
          <label for="puesto">Puesto</label>
          <select name="puesto" id="puesto">
            <option value="disabled">Selecciona tu puesto</option>
            <option value="1">Director General (CEO)</option>
            <option value="2">Director de Operaciones (COO)</option>
            <option value="3">Director Financiero (CFO)</option>
            <option value="4">Contador / Contable</option>
            <option value="5">Auxiliar Administrativo</option>
            <option value="6">Director de Recursos Humanos</option>
            <option value="7">Técnico de Selección / Reclutador</option>
            <option value="8">Director de Marketing (CMO)</option>
            <option value="9">Especialista en Marketing Digital</option>
            <option value="10">Community Manager</option>
            <option value="11">Ejecutivo de Ventas / Comercial</option>
            <option value="12">Gerente de Cuentas (Account Manager)</option>
            <option value="13">Director de Tecnología (CTO)</option>
            <option value="14">Desarrollador de Software</option>
            <option value="15">Administrador de Sistemas (SysAdmin)</option>
            <option value="16">Gerente de Producto (Product Manager)</option>
            <option value="17">Responsable de Logística</option>
            <option value="18">Especialista en Atención al Cliente</option>
            <option value="19">Asesor Legal / Abogado Corporativo</option>
            <option value="20">Auditor de Calidad</option>
          </select>
        </div>

        <div class="field">
          <label for="contrasena">Contraseña</label>
          <div class="password-group">
            <input type="password" id="contrasena" name="contrasena" placeholder="Mínimo 8 caracteres" required minlength="8" />
            <input type="password" name="contrasena2" placeholder="Repite la contraseña" />
          </div>
        </div>

        <button type="submit">Registrarse</button>
      </form>

        <p class="switch-link">¿Ya tienes cuenta? <a href="#" id="switch-login">Iniciar Sesión</a></p>
      </div>
    </div>
    <script src="assets/js/index.js"></script>
  </body>
</html>
