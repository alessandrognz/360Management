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
    <link rel="icon" type="image/png" href="img/logo-favi.png" />
    <link rel="stylesheet" href="css/index.css" />
    <title>Registrarse</title>
  </head>
  <body>
    <div class="form">
      <h1>
        <a href="ini.php">Iniciar Sesión</a> /
        <a href="registr.php">Registrarse</a>
      </h1>
      <form action="registr.php" method="POST">

        NOMBRE:
        <br />
        <input type="text" name="nombre"/>
        <br /><br />

        CORREO ELECTRÓNICO:
        <br />
        <input type="text" name="email"/>
        <br />

        <label for="puesto" ></label>
        <br />
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
        <br /><br />

        CONTRASEÑA:
        <br />
        <input type="password" name="contrasena" required minlength="8"/>
        <br /><br />

        REPITE LA CONTRASEÑA:
        <br />
        <input type="password" name="contrasena2" />
        <br /><br />

        <button type="submit" class="registrarse">Registrarse</button>
      </form>
      <br /><br />
    </div>
  </body>
</html>
