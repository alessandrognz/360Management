<?php 
    // requires
    require 'includes/auth_check.php';
    require 'includes/db.php';

    //variables
    $personas_a_enviar = [];
    $crud = new CRUD_TAREAS();
    $usuarios = $crud->MOSTRAR_USUARIOS();
    $departamentos = $crud->LISTAR_DEPARTAMENTOS();

    $tareas_generales = $crud->SELECT_TAREAS_GENERALES($_SESSION["id_puesto"]);
    $tareas_departamento = $crud->SELECT_TAREAS_PERSONALES($_SESSION["id_usuario"]);

    $mensaje_tarea = $_SESSION['tarea_mensaje'] ?? '';
    $error_tarea = $_SESSION['tarea_error'] ?? false;
    unset($_SESSION['tarea_mensaje'], $_SESSION['tarea_error']);

    function badge_estado_class($estado) {
        $e = mb_strtolower((string) $estado, 'UTF-8');
        if (strpos($e, 'complet') !== false) return 'task-status--done';
        if (strpos($e, 'vencid') !== false || strpos($e, 'atras') !== false) return 'task-status--overdue';
        if (strpos($e, 'progreso') !== false || strpos($e, 'curso') !== false) return 'task-status--progress';
        return 'task-status--pending';
    }

    function fecha_corta($fecha) {
        if (!$fecha) return '—';
        $ts = strtotime($fecha);
        return $ts ? date('d/m/Y H:i', $ts) : htmlspecialchars($fecha);
    }

    $todas_tareas = array_merge($tareas_generales, $tareas_departamento);
    $conteo_estados = ['task-status--pending' => 0, 'task-status--progress' => 0, 'task-status--done' => 0, 'task-status--overdue' => 0];
    foreach ($todas_tareas as $tarea) {
        $conteo_estados[badge_estado_class($tarea['descripcion_est_tarea'])]++;
    }

    $stat_tiles = [
        ['label' => 'Total',        'value' => count($todas_tareas),                    'class' => ''],
        ['label' => 'Pendientes',   'value' => $conteo_estados['task-status--pending'],  'class' => 'stat-tile--pending'],
        ['label' => 'En progreso',  'value' => $conteo_estados['task-status--progress'], 'class' => 'stat-tile--progress'],
        ['label' => 'Completadas',  'value' => $conteo_estados['task-status--done'],     'class' => 'stat-tile--done'],
        ['label' => 'Vencidas',     'value' => $conteo_estados['task-status--overdue'],  'class' => 'stat-tile--overdue'],
    ];

    //Metodos
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $action = $_GET['action']??"";
        if($action == "crear"){
            $id_emisor = $_SESSION["id_usuario"];
            $titulo = $_POST["titulo"]??'';
            $descipcion = $_POST["descipcion"]??'';
            $fecha_raw = $_POST["fecha_limite"]??'';
            $destino_tipo = $_POST["destino_tipo"]??'';

            $departamentos_raw = $_POST["departamentos_a_enviar"]??'';
            $personas_raw = $_POST["personas_a_enviar"]??'';

            $departamentos_a_enviar = [];
            if ($departamentos_raw !== '') {
                $parts = array_filter(array_map('trim', explode(',', $departamentos_raw)), 'strlen');
                $parts = array_map('intval', $parts);
                $departamentos_a_enviar = array_values(array_unique($parts));
            }

            $personas_a_enviar = [];
            if ($personas_raw !== '') {
                $parts = array_filter(array_map('trim', explode(',', $personas_raw)), 'strlen');
                $parts = array_map('intval', $parts);
                $personas_a_enviar = array_values(array_unique($parts));
            }

            // Normalizar fecha_limite a 'Y-m-d H:i:s'
            $fecha_limite = '';
            if ($fecha_raw !== '') {
                $formats = ['Y-m-d\\TH:i:s','Y-m-d\\TH:i','Y-m-d H:i:s','Y-m-d H:i'];
                $dt = null;
                foreach ($formats as $fmt) {
                    $d = DateTime::createFromFormat($fmt, $fecha_raw);
                    if ($d !== false) { $dt = $d; break; }
                }
                if ($dt === null) {
                    $ts = strtotime($fecha_raw);
                    if ($ts !== false) { $fecha_limite = date('Y-m-d H:i:s', $ts); }
                } else {
                    $fecha_limite = $dt->format('Y-m-d H:i:s');
                }
            }

            //Saber si es un mensaje general
            if($destino_tipo == "persona"){
                $destino_tipo = 0;
            }elseif($destino_tipo == "departamento"){
                $destino_tipo=1;
            }

            // Validar fecha límite: obligatoria y no anterior a la fecha actual
            if ($fecha_raw === '' || $fecha_limite === '') {
                $error_tarea = true;
                $mensaje_tarea = 'Debes indicar una fecha límite.';
            } else {
                $fecha_limite_dt = DateTime::createFromFormat('Y-m-d H:i:s', $fecha_limite);
                if ($fecha_limite_dt === false || $fecha_limite_dt < new DateTime()) {
                    $error_tarea = true;
                    $mensaje_tarea = 'La fecha límite no puede ser anterior a la fecha actual.';
                }
            }

            //crear tarea
            if (!$error_tarea) {
                if($destino_tipo == 0){
                    $crud->INSERTAR_TAREA($id_emisor,$titulo,$descipcion,$fecha_limite,$destino_tipo,$personas_a_enviar);
                }
                if($destino_tipo == 1){
                    $crud->INSERTAR_TAREA($id_emisor,$titulo,$descipcion,$fecha_limite,$destino_tipo,$departamentos_a_enviar);
                }
            }

            $_SESSION['tarea_mensaje'] = $mensaje_tarea;
            $_SESSION['tarea_error'] = $error_tarea;
            header('Location: tasks.php');
            exit();

        }

    }
    

    
    



?>
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
        <div class="tasks-header">
            <h1 class="page-title">Tareas</h1>
            <button type="button" class="btn btn-primary" id="btn-crear-tarea">+ Crear tarea</button>
        </div>

        <div class="tasks-stats">
            <?php foreach ($stat_tiles as $tile): ?>
                <div class="stat-tile <?php echo $tile['class']; ?>">
                    <span class="stat-value"><?php echo $tile['value']; ?></span>
                    <span class="stat-label"><?php echo $tile['label']; ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <section class="task-section">
            <div class="task-section-header task-section-header--tabs">
                <div class="task-tabs" role="tablist">
                    <button type="button" class="task-tab is-active" data-tab="generales" role="tab" aria-selected="true">
                        Tareas generales <span class="task-count"><?php echo count($tareas_generales); ?></span>
                    </button>
                    <button type="button" class="task-tab" data-tab="personales" role="tab" aria-selected="false">
                        Tareas personales <span class="task-count"><?php echo count($tareas_departamento); ?></span>
                    </button>
                </div>
            </div>

            <div class="task-list" data-tab-panel="generales">
                <?php if (!$tareas_generales): ?>
                    <p class="task-empty">No hay tareas generales asignadas a tu puesto.</p>
                <?php else: foreach ($tareas_generales as $tarea): ?>
                    <div class="task-row">
                        <div class="task-main">
                            <span class="task-title"><?php echo htmlspecialchars($tarea["titulo"]); ?></span>
                            <span class="task-desc"><?php echo htmlspecialchars($tarea["descripcion_tarea"]); ?></span>
                        </div>
                        <span class="task-tag"><?php echo htmlspecialchars($tarea["nombre_departamento"]); ?></span>
                        <div class="task-dates">
                            <span><strong>Límite:</strong> <?php echo fecha_corta($tarea["fecha_limite"]); ?></span>
                            <span><?php echo fecha_corta($tarea["fecha_creacion"]); ?></span>
                        </div>
                        <span class="task-status <?php echo badge_estado_class($tarea["descripcion_est_tarea"]); ?>">
                            <?php echo htmlspecialchars($tarea["descripcion_est_tarea"]); ?>
                        </span>
                    </div>
                <?php endforeach; endif; ?>
            </div>

            <div class="task-list" data-tab-panel="personales" hidden>
                <?php if (!$tareas_departamento): ?>
                    <p class="task-empty">No tienes tareas personales asignadas.</p>
                <?php else: foreach ($tareas_departamento as $tarea): ?>
                    <div class="task-row">
                        <div class="task-main">
                            <span class="task-title"><?php echo htmlspecialchars($tarea["titulo"]); ?></span>
                            <span class="task-desc"><?php echo htmlspecialchars($tarea["descripcion_tarea"]); ?></span>
                        </div>
                        <div class="task-dates">
                            <span><strong>Límite:</strong> <?php echo fecha_corta($tarea["fecha_limite"]); ?></span>
                            <span><?php echo fecha_corta($tarea["fecha_creacion"]); ?></span>
                        </div>
                        <span class="task-status <?php echo badge_estado_class($tarea["descripcion_est_tarea"]); ?>">
                            <?php echo htmlspecialchars($tarea["descripcion_est_tarea"]); ?>
                        </span>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </section>
    </main>

    <div class="modal-overlay<?= $mensaje_tarea !== '' ? ' active' : '' ?>" id="modal-crear-tarea">
        <div class="modal-container">
            <button type="button" class="modal-close" data-modal="modal-crear-tarea">&times;</button>
            <h2>Crear tarea</h2>

            <?php if ($mensaje_tarea !== ''): ?>
            <p class="settings-message<?= $error_tarea ? ' settings-message--error' : ' settings-message--exito' ?>"><?= htmlspecialchars($mensaje_tarea) ?></p>
            <?php endif; ?>

            <form class="task-form" action="tasks.php?action=crear" method="POST">
                <div class="task-form-grid">
                    <div class="form-field">
                        <label class="form-label">Título</label>
                        <input class="form-input" name="titulo" type="text" required>
                    </div>
                    <div class="form-field">
                        <label class="form-label">Fecha límite</label>
                        <input class="form-input" name="fecha_limite" type="datetime-local" required min="<?php echo date('Y-m-d\TH:i'); ?>">
                    </div>
                </div>

                <div class="form-field">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-input" name="descipcion" rows="2"></textarea>
                </div>

                <div class="form-field">
                    <label class="form-label">Destinatario</label>
                    <div class="segmented">
                        <label class="segmented-option">
                            <input type="radio" name="destino_tipo" id="tipo_persona" value="persona" required>
                            <span>Persona</span>
                        </label>
                        <label class="segmented-option">
                            <input type="radio" name="destino_tipo" id="tipo_departamento" value="departamento" required>
                            <span>Departamento</span>
                        </label>
                    </div>
                </div>

                <div id="person_block" class="task-target-block" hidden>
                    <label class="form-label">Listado personas</label>
                    <select name="personal" id="personal">
                        <option value="" selected disabled>Selecciona una persona</option>
                        <?php if ($usuarios) {
                            foreach ($usuarios as $usuario) {
                            echo '<option value="'.htmlspecialchars($usuario['id_usuario']).'">'.htmlspecialchars($usuario['nombre']).'</option>';
                        }}?>
                    </select>
                    <input type="hidden" id="_personas_a_enviar" name="personas_a_enviar" value="">
                    <div id="personas_list_display"></div>
                </div>

                <div id="dept_block" class="task-target-block" hidden>
                    <label class="form-label">Listado departamento</label>
                    <select name="departamento" id="departamento">
                        <option value="" selected disabled>Selecciona un departamento</option>
                        <?php if ($departamentos) {
                            foreach ($departamentos as $departamento) {
                            echo '<option value="'.htmlspecialchars($departamento['id_departamento']).'">'.htmlspecialchars($departamento['nombre_departamento']).'</option>';
                        }}?>
                    </select>
                    <input type="hidden" id="_departamentos_a_enviar" name="departamentos_a_enviar" value="">
                    <div id="departamentos_list_display"></div>
                </div>

                <input type="submit" value="Crear tarea +" class="add">
            </form>
        </div>
    </div>

    <?php $layout_part = 'footer'; require 'includes/nav.php'; ?>
    <script src="assets/js/tasks.js"></script>
</body>
</html>