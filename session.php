<?php
    require 'includes/db.php';
    require 'includes/auth_check.php';
    require 'includes/nav.php';
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/session.css" />
    <link rel="icon" type="image/png" href="img/logo-favi.png" />
    <title>Inicio</title>
</head>
<body>
    <h1 class="bienvenida"><?php echo 'Bienvenido, ', $_SESSION['nombre']; ?></h1>
</body>
</html>