<?php
    require 'includes/db.php';
    require 'includes/auth_check.php';

    $crud = new CRUD_TAREAS();
    $tareas_departamento = $crud->SELECT_TAREAS_PERSONALES($_SESSION["id_usuario"]);

    function badge_estado_class($estado = '') {
        $e = mb_strtolower((string) $estado, 'UTF-8');
        if (strpos($e, 'complet') !== false) return 'task-status--done';
        if (strpos($e, 'vencid') !== false || strpos($e, 'atras') !== false) return 'task-status--overdue';
        if (strpos($e, 'progreso') !== false || strpos($e, 'curso') !== false) return 'task-status--progress';
        return 'task-status--pending';
    }

    function fecha_corta($fecha = '') {
        if (!$fecha) return '—';
        $ts = strtotime($fecha);
        return $ts ? date('d/m/Y H:i', $ts) : htmlspecialchars($fecha);
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/session.css" />
    <link rel="icon" type="image/png" href="assets/icons/logo.png" />
    <title>Inicio</title>
</head>
<body>
    <?php require 'includes/nav.php'; ?>
    <main>
        <div class="inicio-hero">
            <img src="assets/icons/logo+360Management.png" alt="360Management" class="inicio-logo">
            <h1 class="bienvenida">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h1>
        </div>
            <h2 class="tareas">Tus tareas:</h2>
        <section class="task-widget-section">
            <div class="task-section-header">
            </div>
            <div class="task-widget-list">
                <?php if (!$tareas_departamento): ?>
                    <p class="task-empty">No hay tareas aún.</p>
                <?php else: foreach ($tareas_departamento as $tarea): ?>
                    <div class="task-widget-card">
                        <div class="task-widget-body">
                            <span class="task-widget-title"><?php echo htmlspecialchars($tarea["titulo"]); ?></span>
                            <span class="task-widget-desc"><?php echo htmlspecialchars($tarea["descripcion_tarea"]); ?></span>
                        </div>
                        <div class="task-widget-footer">
                            <div class="task-widget-dates">
                                <span><strong>Límite:</strong> <?php echo fecha_corta($tarea["fecha_limite"]); ?></span>
                                <span>Creada: <?php echo fecha_corta($tarea["fecha_creacion"]); ?></span>
                            </div>
                            <span class="task-status <?php echo badge_estado_class($tarea["descripcion_est_tarea"]); ?>">
                                <?php echo htmlspecialchars($tarea["descripcion_est_tarea"]); ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </section>
    </main>
    <?php $layout_part = 'footer'; require 'includes/nav.php'; ?>
</body>
</html>