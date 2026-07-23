<?php
    require 'includes/auth_check.php';
    require 'includes/db.php';

    $crud_user = new CRUD_USER();

    // Mensajes de feedback tras redirección (patrón PRG igual que tasks.php)
    $admin_mensaje = $_SESSION['admin_mensaje'] ?? '';
    $admin_error   = $_SESSION['admin_error']   ?? false;
    unset($_SESSION['admin_mensaje'], $_SESSION['admin_error']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $accion = $_POST['accion'] ?? '';

        // ——— Desactivar usuario (borrado lógico) ———
        if ($accion === 'eliminar') {
            $id_usuario_eliminar = (int)($_POST['id_usuario'] ?? 0);

            if ($id_usuario_eliminar !== (int)$_SESSION['id_usuario']) {
                $crud_user->ELIMINAR_USUARIO_LOGICO($id_usuario_eliminar);
            }

            header('Location: admin.php');
            exit();
        }

        // ——— Activar usuario ———
        if ($accion === 'activar') {
            $id_activar = (int)($_POST['id_usuario'] ?? 0);

            if ($id_activar > 0 && $id_activar !== (int)$_SESSION['id_usuario']) {
                $crud_user->ACTIVAR_USUARIO($id_activar);
            }
            header('Location: admin.php');
            exit();
        }

        // ——— Editar detalles de un usuario ———
        // Reutiliza CAMBIAR_NOMBRE_USUARIO, CAMBIAR_EMAIL y CAMBIAR_CONTRASENA_FORZADA
        // Solo actúa sobre los campos que el admin haya rellenado.
        if ($accion === 'editar') {
            $id_editar        = (int)  ($_POST['id_usuario']       ?? 0);
            $nombre           = trim(   $_POST['nombre']            ?? '');
            $email            = trim(   $_POST['email']             ?? '');
            $nueva_contrasena =         $_POST['nueva_contrasena']  ?? '';

            if ($id_editar > 0) {
                if ($nombre !== '') $crud_user->CAMBIAR_NOMBRE_USUARIO($id_editar, $nombre);
                if ($email  !== '') $crud_user->CAMBIAR_EMAIL($id_editar, $email);

                // La contraseña es opcional: si el campo llega vacío no se toca
                if ($nueva_contrasena !== '') {
                    $crud_user->CAMBIAR_CONTRASENA_FORZADA(
                        $id_editar,
                        password_hash($nueva_contrasena, PASSWORD_BCRYPT)
                    );
                }

                // Si el admin editó su propia cuenta, reflejar los cambios en sesión
                // para que el nav muestre el nombre/email actualizados sin relogin.
                if ($id_editar === (int)$_SESSION['id_usuario']) {
                    if ($nombre !== '') $_SESSION['nombre'] = $nombre;
                    if ($email  !== '') $_SESSION['email']  = $email;
                }

                $_SESSION['admin_mensaje'] = 'Usuario actualizado correctamente.';
                $_SESSION['admin_error']   = false;
            } else {
                $_SESSION['admin_mensaje'] = 'No se pudo identificar el usuario.';
                $_SESSION['admin_error']   = true;
            }

            header('Location: admin.php');
            exit();
        }
    }

    // MOSTRAR_USUARIOS ya existía; aprovechamos el mismo array para los data-* del botón editar
    $usuarios = $crud_user->MOSTRAR_USUARIOS();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/session.css" />
    <link rel="icon" type="image/png" href="assets/icons/logo.png" />
    <title>Panel de control</title>
</head>
<body>
    <?php require 'includes/nav.php'; ?>
    <main>
        <h1 class="page-title">Panel de control</h1>

        <?php if ($admin_mensaje !== ''): ?>
        <p class="settings-message <?= $admin_error ? 'settings-message--error' : 'settings-message--exito' ?>"
           style="margin-bottom: 16px;">
            <?= htmlspecialchars($admin_mensaje) ?>
        </p>
        <?php endif; ?>

        <h2>Usuarios:</h2><br>
        <div class="user-list">
            <?php if ($usuarios): ?>
            <?php foreach ($usuarios as $usuario):
                $inactivo = (int)$usuario['eliminado'] === 1;
                $es_yo    = (int)$usuario['id_usuario'] === (int)$_SESSION['id_usuario'];
            ?>
            <div class="user-row<?= $inactivo ? ' user-row--inactive' : '' ?>">
                <img src="assets/icons/profile.png" alt="" class="user-avatar<?= $inactivo ? ' user-avatar--inactive' : '' ?>">
                <div class="user-info">
                    <span class="user-name"><?= htmlspecialchars($usuario['nombre']) ?></span>
                    <span class="user-badge"><?= htmlspecialchars($usuario['nombre_puesto']) ?></span>
                </div>
                <span class="user-email"><?= htmlspecialchars($usuario['email']) ?></span>
                <span class="user-id">#<?= htmlspecialchars($usuario['id_usuario']) ?></span>
                <span class="user-date"><?= htmlspecialchars($usuario['fecha_registro']) ?></span>

                <!-- Indicador activo / inactivo -->
                <span class="user-status <?= $inactivo ? 'user-status--off' : 'user-status--on' ?>">
                    <span class="user-status-dot"></span>
                    <?= $inactivo ? 'Inactivo' : 'Activo' ?>
                </span>

                <!-- Wrapper de acciones: editar + eliminar -->
                <div class="user-actions">
                    <button
                        type="button"
                        class="btn-icon-edit btn-editar-usuario"
                        data-id="<?= htmlspecialchars($usuario['id_usuario']) ?>"
                        data-nombre="<?= htmlspecialchars($usuario['nombre']) ?>"
                        data-email="<?= htmlspecialchars($usuario['email']) ?>"
                        data-is-self="<?= $es_yo ? '1' : '0' ?>"
                        <?= $inactivo ? 'disabled' : '' ?>>
                        Editar detalles
                    </button>

                    <?php if ($inactivo): ?>
                    <form method="post" action="admin.php"
                          onsubmit="return confirm('¿Activar a <?= htmlspecialchars(addslashes($usuario['nombre'])) ?>?');">
                        <input type="hidden" name="accion" value="activar">
                        <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['id_usuario']) ?>">
                        <button type="submit" class="btn-icon-edit">Activar</button>
                    </form>
                    <?php else: ?>
                    <form method="post" action="admin.php" class="user-delete-form"
                          onsubmit="return confirm('¿Desactivar a <?= htmlspecialchars(addslashes($usuario['nombre'])) ?>?');">
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['id_usuario']) ?>">
                        <button type="submit" class="btn-icon-danger"
                                <?= $es_yo ? 'disabled' : '' ?>>
                            Desactivar
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <p>No hay usuarios que mostrar.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- ============================================================
         Modal "Editar usuario"
         Estructura idéntica al modal "Crear tarea" de tasks.php.
         El JS de abajo lo rellena con los data-* del botón pulsado.
         ============================================================ -->
    <div class="modal-overlay" id="modal-editar-usuario">
        <div class="modal-container">
            <button type="button" class="modal-close" data-modal="modal-editar-usuario" aria-label="Cerrar">&times;</button>

            <div class="modal-header">
                <div class="modal-header-icon">
                    <img src="assets/icons/profile.png" alt="">
                </div>
                <div class="modal-header-info">
                    <h2>Editar usuario</h2>
                    <span class="modal-header-sub" id="edit-nombre-sub"></span>
                </div>
                <button type="button" id="modal-edit-btn" class="btn-modal-edit">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                    Editar datos
                </button>
                <button type="button" id="modal-cancel-btn" class="btn-modal-cancel" hidden>
                    <svg class="btn-cancel-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Dejar de editar datos
                </button>
            </div>

            <!-- Formulario de edición: nombre, email y contraseña opcional -->
            <form class="task-form" action="admin.php" method="POST">
                <input type="hidden" name="accion" value="editar">
                <!-- ID del usuario que se está editando; JS lo rellena al abrir el modal -->
                <input type="hidden" name="id_usuario" id="edit-id">

                <div class="form-field">
                    <label class="form-label">Nombre</label>
                    <input class="form-input" type="text" name="nombre" id="edit-nombre"
                           required autocomplete="off" readonly>
                </div>

                <div class="form-field">
                    <label class="form-label">Email</label>
                    <input class="form-input" type="email" name="email" id="edit-email"
                           required autocomplete="off" readonly>
                </div>

                <!-- Ocultos hasta que el usuario pulse "Editar datos" -->
                <div class="modal-section--optional" id="modal-password-section" hidden>
                    <span class="modal-section-label">Contraseña opcional</span>
                    <div class="form-field">
                        <label class="form-label">
                            Nueva contraseña
                            <span style="font-weight:400; color:#9ca3af;">(dejar vacío para no cambiar)</span>
                        </label>
                        <input class="form-input" type="password" name="nueva_contrasena" id="edit-password"
                               placeholder="••••••••" autocomplete="new-password">
                    </div>
                </div>

                <input type="submit" value="Confirmar cambios" class="add" id="modal-submit" hidden>
            </form>

            <div class="modal-danger-zone">
            <div class="settings-danger-bar">
                <div class="settings-danger-copy">
                    <span class="settings-danger-title">Desactivar usuario</span>
                    <span class="settings-danger-text">
                        El usuario perderá el acceso. Podrás reactivarlo desde el panel.
                    </span>
                </div>
                <form method="post" action="admin.php"
                      onsubmit="return confirm('¿Desactivar este usuario?');">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id_usuario" id="edit-id-eliminar">
                    <button type="submit" class="btn btn-danger btn-sm" id="btn-eliminar-en-modal">
                        Desactivar cuenta
                    </button>
                </form>
            </div>
            </div><!-- /.modal-danger-zone -->
        </div>
    </div>

    <?php $layout_part = 'footer'; require 'includes/nav.php'; ?>

    <script>
    /* Modal "Editar usuario" — mismo patrón IIFE que tasks.js */
    (function () {
        var modal = document.getElementById('modal-editar-usuario');
        if (!modal) return;

        function openModal()  { modal.classList.add('active'); }
        function closeModal() { modal.classList.remove('active'); }

        /* Botón × — reutiliza el selector genérico de tasks.js */
        document.querySelectorAll('.modal-close').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var target = document.getElementById(btn.dataset.modal);
                if (target) target.classList.remove('active');
            });
        });

        /* Clic en el fondo oscuro cierra el modal */
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });

        /* Escape cierra el modal */
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeModal();
        });

        /* ——— Valores originales para detección de cambios ——— */
        var originalValues = { nombre: '', email: '' };

        function hasChanges() {
            return document.getElementById('edit-nombre').value   !== originalValues.nombre
                || document.getElementById('edit-email').value    !== originalValues.email
                || document.getElementById('edit-password').value !== '';
        }

        function syncSubmit() {
            document.getElementById('modal-submit').disabled = !hasChanges();
        }

        /* ——— Modos: lectura (locked) / edición (unlocked) ——— */
        function lockModal() {
            document.getElementById('edit-nombre').setAttribute('readonly', '');
            document.getElementById('edit-email').setAttribute('readonly', '');
            document.getElementById('modal-password-section').style.display = 'none';
            document.getElementById('modal-submit').style.display           = 'none';
            document.getElementById('modal-edit-btn').style.display         = 'flex';
            document.getElementById('modal-cancel-btn').style.display       = 'none';
        }

        function unlockModal() {
            document.getElementById('edit-nombre').removeAttribute('readonly');
            document.getElementById('edit-email').removeAttribute('readonly');
            document.getElementById('modal-password-section').style.display = 'block';
            document.getElementById('modal-submit').style.display           = 'block';
            document.getElementById('modal-submit').disabled                = true;
            document.getElementById('modal-edit-btn').style.display         = 'none';
            document.getElementById('modal-cancel-btn').style.display       = 'flex';
            document.getElementById('edit-nombre').focus();
        }

        function cancelEdit() {
            document.getElementById('edit-nombre').value   = originalValues.nombre;
            document.getElementById('edit-email').value    = originalValues.email;
            document.getElementById('edit-password').value = '';
            lockModal();
        }

        document.getElementById('modal-edit-btn').addEventListener('click', unlockModal);
        document.getElementById('modal-cancel-btn').addEventListener('click', cancelEdit);

        ['edit-nombre', 'edit-email', 'edit-password'].forEach(function (id) {
            document.getElementById(id).addEventListener('input', syncSubmit);
        });

        /* Al pulsar "Editar detalles": rellena el modal y lo abre en modo lectura */
        document.querySelectorAll('.btn-editar-usuario').forEach(function (btn) {
            btn.addEventListener('click', function () {
                originalValues.nombre = btn.dataset.nombre;
                originalValues.email  = btn.dataset.email;

                document.getElementById('edit-id').value          = btn.dataset.id;
                document.getElementById('edit-nombre').value      = btn.dataset.nombre;
                document.getElementById('edit-email').value       = btn.dataset.email;
                document.getElementById('edit-id-eliminar').value = btn.dataset.id;
                document.getElementById('edit-password').value    = '';
                document.getElementById('edit-nombre-sub').textContent = btn.dataset.email;

                document.getElementById('btn-eliminar-en-modal').disabled = btn.dataset.isSelf === '1';

                lockModal();
                openModal();
            });
        });
    })();
    </script>
</body>
</html>
