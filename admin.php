<?php
    require 'includes/auth_check.php';
    require 'includes/db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'eliminar') {
        $id_usuario_eliminar = (int) ($_POST['id_usuario'] ?? 0);

        if ($id_usuario_eliminar !== (int) $_SESSION['id_usuario']) {
            ELIMINAR_USUARIO_LOGICO($id_usuario_eliminar);
        }

        header('Location: admin.php');
        exit();
    }

    $usuarios = MOSTRAR_USUARIOS();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/session.css" />
    <link rel="icon" type="image/png" href="Icons/logo.png" />
    <title>Bandeja de entrada</title>
</head>
<body>
    <?php require 'includes/nav.php'; ?>
    <main>
        <h1 class="page-title">Panel de control</h1>

    <div class="user-list">
        <?php if ($usuarios): ?>
        <?php foreach ($usuarios as $usuario): ?>
        <div class="user-row">
            <input type="checkbox" class="user-checkbox" aria-label="Seleccionar usuario">
            <img src="Icons/profile.png" alt="" class="user-avatar">
            <div class="user-info">
                <span class="user-name"><?= htmlspecialchars($usuario['nombre']) ?></span>
                <span class="user-badge"><?= htmlspecialchars($usuario['nombre_puesto']) ?></span>
            </div>
            <span class="user-email"><?= htmlspecialchars($usuario['email']) ?></span>
            <span class="user-id">#<?= htmlspecialchars($usuario['id_usuario']) ?></span>
            <span class="user-date"><?= htmlspecialchars($usuario['fecha_registro']) ?></span>
            <form method="post" action="admin.php" class="user-delete-form" onsubmit="return confirm('¿Eliminar a <?= htmlspecialchars(addslashes($usuario['nombre'])) ?>?');">
                <input type="hidden" name="accion" value="eliminar">
                <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['id_usuario']) ?>">
                <button type="submit" class="btn-icon-danger" aria-label="Eliminar usuario" <?= (int) $usuario['id_usuario'] === (int) $_SESSION['id_usuario'] ? 'disabled' : '' ?>>
                    Eliminar
                </button>
            </form>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <p>No hay usuarios que mostrar.</p>
        <?php endif; ?>
    </div>
    </main>
    
    <?php $layout_part = 'footer'; require 'includes/nav.php'; ?>
</body>
</html>
