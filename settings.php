<?php
    require 'includes/auth_check.php';
    require 'includes/db.php';

    $mensaje = '';
    $error = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'cambiar_nombre') {
        $nuevo_nombre = trim($_POST['nombre'] ?? '');

        if ($nuevo_nombre === '') {
            $error = true;
            $mensaje = 'El nombre no puede estar vacío.';
        } else {
            CAMBIAR_NOMBRE_USUARIO((int) $_SESSION['id_usuario'], $nuevo_nombre);
            $_SESSION['nombre'] = $nuevo_nombre;
            $mensaje = 'Nombre actualizado correctamente.';
        }
    }

    $usuario = MOSTRAR_USUARIO((int) $_SESSION['id_usuario']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/session.css" />
    <link rel="icon" type="image/png" href="assets/icons/logo.png" />
    <title>Ajustes</title>
</head>
<body>
    <?php require 'includes/nav.php'; ?>
    <main>
        <h1 class="page-title">Ajustes</h1>

        <div class="settings-profile-card">
            <img src="assets/icons/profile.png" alt="" class="settings-avatar">
            <div class="settings-profile-info">
                <span class="settings-profile-name"><?= htmlspecialchars($usuario['nombre']) ?></span>
                <span class="settings-profile-email"><?= htmlspecialchars($usuario['email']) ?></span>
                <span class="user-badge"><?= htmlspecialchars($usuario['nombre_puesto']) ?></span>
            </div>
        </div>

        <section class="settings-section">
            <h2 class="settings-section-title">Editar perfil</h2>

            <?php if ($mensaje !== ''): ?>
            <p class="settings-message<?= $error ? ' settings-message--error' : ' settings-message--exito' ?>"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <form method="post" action="settings.php" class="settings-form">
                <input type="hidden" name="accion" value="cambiar_nombre">
                <label class="form-label" for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-input" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                <div class="settings-actions">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <button type="submit" form="form-eliminar-usuario" class="btn btn-danger">Eliminar usuario</button>
                </div>
            </form>
            <form id="form-eliminar-usuario" method="post" action="includes/delete_user.php" onsubmit="return confirm('¿Seguro que quieres eliminar tu cuenta? Esta acción no se puede deshacer.');"></form>
        </section>
    </main>
    <?php $layout_part = 'footer'; require 'includes/nav.php'; ?>
</body>
</html>
