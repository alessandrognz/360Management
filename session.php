<?php
    require 'includes/db.php';
    require 'includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/session.css" />
    <link rel="icon" type="image/png" href="Icons/logo.png" />
    <title>Inicio</title>
</head>
<body>
    <?php require 'includes/nav.php'; ?>
    <main>
        <h1 class="bienvenida">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h1>
    </main>
    <footer class="footer-publico">
        <span>© 2026 360Management</span>
        <a href="#">Privacidad</a>
        <a href="#">Contacto</a>
    </footer>
</body>
</html>
