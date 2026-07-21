<?php require 'includes/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/session.css" />
    <link rel="icon" type="image/png" href="assets/icons/logo.png" />
    <title>Tareas</title>
</head>
<body>
    <?php require 'includes/nav.php'; ?>
    <main>
        <h1 class="page-title">Tareas</h1>
        <button class="add">Añadir Tarea +</button>
    </main>
    <?php $layout_part = 'footer'; require 'includes/nav.php'; ?>
</body>
</html>