<?php require 'includes/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/session.css" />
    <link rel="icon" type="image/png" href="Icons/logo.png" />
    <title>Ajustes</title>
</head>
<body>
    <?php require 'includes/nav.php'; ?>
    <main>
        <h1 class="page-title">Ajustes</h1>

        <h4>Cambiar nombre de usuario: </h4>

        <form action="includes/db.php">
            <input type="text">
        </form>

        <div class="buttons">
            <br><br>
            <a href="db.php" class="btn btn-danger">Eliminar usuario</a>
        </div>
    </main>
    <?php $layout_part = 'footer'; require 'includes/nav.php'; ?>
</body>
</html>
