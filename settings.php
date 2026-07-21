<?php
    require 'includes/auth_check.php';
    require 'includes/db.php';

    $crud_user = new CRUD_USER();
    $mensaje = '';
    $error = false;
    $seccion_mensaje = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'cambiar_nombre') {
        $seccion_mensaje = 'perfil';
        $nuevo_nombre = trim($_POST['nombre'] ?? '');

        if ($nuevo_nombre === '') {
            $error = true;
            $mensaje = 'El nombre no puede estar vacío.';
        } else {
            $crud_user->CAMBIAR_NOMBRE_USUARIO((int) $_SESSION['id_usuario'], $nuevo_nombre);
            $_SESSION['nombre'] = $nuevo_nombre;
            $mensaje = 'Nombre actualizado correctamente.';
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'cambiar_contrasena') {
        $seccion_mensaje = 'contrasena';
        $contrasena_actual    = $_POST['contrasena_actual'] ?? '';
        $new_contrasena       = $_POST['contrasena_nueva'] ?? '';
        $contrasena_confirmar = $_POST['contrasena_confirmar'] ?? '';

        if ($new_contrasena !== $contrasena_confirmar) {
            $error = true;
            $mensaje = 'La nueva contraseña y su confirmación no coinciden.';
        } elseif ($crud_user->CAMBIAR_CONTRASENA($contrasena_actual, $new_contrasena)) {
            $mensaje = 'Contraseña actualizada correctamente.';
        } else {
            $error = true;
            $mensaje = 'No se pudo cambiar: la contraseña actual no es correcta.';
        }
    }

    $usuario = $crud_user->MOSTRAR_USUARIO((int) $_SESSION['id_usuario']);
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

            <?php if ($mensaje !== '' && $seccion_mensaje === 'perfil'): ?>
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
            <br>

            <h2 class="settings-section-title">Cambiar contraseña</h2>

            <?php if ($mensaje !== '' && $seccion_mensaje === 'contrasena'): ?>
            <p class="settings-message<?= $error ? ' settings-message--error' : ' settings-message--exito' ?>"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <form method="post" action="settings.php" class="settings-form">
                <input type="hidden" name="accion" value="cambiar_contrasena">

                <label class="form-label" for="contrasena_actual">Contraseña actual</label>
                <input type="password" id="contrasena_actual" name="contrasena_actual" class="form-input" placeholder="Introduce tu contraseña actual" required>

                <label class="form-label" for="contrasena_nueva">Nueva contraseña</label>
                <input type="password" id="contrasena_nueva" name="contrasena_nueva" class="form-input" placeholder="Mínimo 8 caracteres" required minlength="8">

                <label class="form-label" for="contrasena_confirmar">Repite la nueva contraseña</label>
                <input type="password" id="contrasena_confirmar" name="contrasena_confirmar" class="form-input" placeholder="Repite la nueva contraseña" required minlength="8">

                <div class="settings-actions">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </section>
    </main>
    <?php $layout_part = 'footer'; require 'includes/nav.php'; ?>
</body>
</html>
