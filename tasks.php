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
            //crear tarea
            if($destino_tipo == 0){
                $crud->INSERTAR_TAREA($id_emisor,$titulo,$descipcion,$fecha_limite,$destino_tipo,$personas_a_enviar);
            }
            if($destino_tipo == 1){
                $crud->INSERTAR_TAREA($id_emisor,$titulo,$descipcion,$fecha_limite,$destino_tipo,$departamentos_a_enviar);
            }
            


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
        <div>
            <h1 class="page-title">Tareas</h1>
            <button class="add">Añadir Tarea +</button>
        </div>
        <div>
            <h1>Tareas Generales</h1>
            <table>
                <th>Departamento</th>
                <th>Titulo</th>
                <th>Descripcion</th>
                <th>Fecha creacion</th>
                <th>Fecha limite</th>
                <th>Estado</th>
                <?php
                foreach ($tareas_generales as $tarea) {
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tarea["nombre_departamento"]);?></td>
                        <td><?php echo htmlspecialchars($tarea["titulo"]);?></td>
                        <td><?php echo htmlspecialchars($tarea["descripcion_tarea"]);?></td>
                        <td><?php echo htmlspecialchars($tarea["fecha_creacion"]);?></td>
                        <td><?php echo htmlspecialchars($tarea["fecha_limite"]);?></td>
                        <td><?php echo htmlspecialchars($tarea["descripcion_est_tarea"]);?></td>
                    </tr>
                    <?php
                }
                ?>
            </table>

        </div>
        <div>
            <h1>Tareas Personales</h1>
            <table>
                <th>Titulo</th>
                <th>Descripcion</th>
                <th>Fecha creacion</th>
                <th>Fecha limite</th>
                <th>Estado</th>
                <?php
                    foreach ($tareas_departamento as $tarea) {
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($tarea["titulo"]);?></td>
                            <td><?php echo htmlspecialchars($tarea["descripcion_tarea"]);?></td>
                            <td><?php echo htmlspecialchars($tarea["fecha_creacion"]);?></td>
                            <td><?php echo htmlspecialchars($tarea["fecha_limite"]);?></td>
                            <td><?php echo htmlspecialchars($tarea["descripcion_est_tarea"]);?></td>
                        </tr>
                    <?php
                }
                ?>
            </table>
        </div>
        <div>
            <h1>Crear tarea</h1>
            <div>
                <form action="tasks.php?action=crear" method="POST">
                    <label>Titulo</label>
                    <input name="titulo" type="text">
                    <br>
                    <label>Descripcion</label>
                    <input name="descipcion" type="text">
                    <br>
                    <label>fecha limite</label>
                    <input name="fecha_limite" type="datetime-local">
                    <br>
                    <label>Para:</label>
                    <input type="radio" name="destino_tipo" id="tipo_persona" value="persona"> Persona
                    <input type="radio" name="destino_tipo" id="tipo_departamento" value="departamento"> Departamento
                    <br>

                    <div id="person_block" style="display:none; margin-top:6px;">
                        <label>Listado Personas</label>
                        <select name="personal" id="personal">
                            <?php if ($usuarios) {
                                foreach ($usuarios as $usuario) {
                                echo '<option value="'.htmlspecialchars($usuario['id_usuario']).'">'.htmlspecialchars($usuario['nombre']).'</option>';
                            }}?>
                        </select>
                        <button type="button" id="add_person_btn">Añadir</button>
                        <input type="hidden" id="_personas_a_enviar" name="personas_a_enviar" value="">
                        <div id="personas_list_display" style="margin-top:8px;"></div>
                    </div>

                    <div id="dept_block" style="display:none; margin-top:6px;">
                        <label>Listado Departamento</label>
                        <select name="departamento" id="departamento">
                            <?php if ($departamentos) {
                                foreach ($departamentos as $departamento) {
                                echo '<option value="'.htmlspecialchars($departamento['id_departamento']).'">'.htmlspecialchars($departamento['nombre_departamento']).'</option>';
                            }}?>
                        </select>
                        <button type="button" id="add_dept_btn">Añadir</button>
                        <input type="hidden" id="_departamentos_a_enviar" name="departamentos_a_enviar" value="">
                        <div id="departamentos_list_display" style="margin-top:8px;"></div>
                    </div>
                    <br>
                    <input type="submit" value="Crear tarea">
                </form>
            </div>
        </div>

    </main>
    <?php $layout_part = 'footer'; require 'includes/nav.php'; ?>
    <script>
        (function(){
            function escapeHtml(str){ return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

            // Generic renderer for list with remove buttons
            function renderList(ids, names, hidden, display){
                hidden.value = ids.join(',');
                if (!names.length){ display.innerHTML = ''; return; }
                var parts = names.map(function(name, i){
                    return '<span class="selected-item" data-index="'+i+'" style="display:inline-block;padding:4px 8px;margin:3px;border:1px solid #ccc;border-radius:4px;">'+escapeHtml(name)+' <button type="button" class="remove-item" data-index="'+i+'" style="margin-left:6px;">x</button></span>';
                });
                display.innerHTML = '<strong>Seleccionados:</strong> ' + parts.join(' ');
            }

            // Users
            var addPersonBtn = document.getElementById('add_person_btn');
            var personSelect = document.getElementById('personal');
            var personHidden = document.getElementById('_personas_a_enviar');
            var personDisplay = document.getElementById('personas_list_display');
            var personIds = [], personNames = [];
            var personBlock = document.getElementById('person_block');
            var deptBlock = document.getElementById('dept_block');
            var tipoPerson = document.getElementById('tipo_persona');
            var tipoDept = document.getElementById('tipo_departamento');

            if (addPersonBtn && personSelect && personHidden){
                addPersonBtn.addEventListener('click', function(){
                    var val = personSelect.value;
                    var txt = personSelect.options[personSelect.selectedIndex].text;
                    if (!val) return;
                    if (personIds.indexOf(val) === -1){ personIds.push(val); personNames.push(txt); renderList(personIds, personNames, personHidden, personDisplay); }
                });
            }

            if (personDisplay){
                personDisplay.addEventListener('click', function(e){
                    var t = e.target;
                    if (t && t.classList.contains('remove-item')){
                        var idx = parseInt(t.getAttribute('data-index'), 10);
                        if (!isNaN(idx)){
                            personIds.splice(idx,1);
                            personNames.splice(idx,1);
                            renderList(personIds, personNames, personHidden, personDisplay);
                        }
                    }
                });
            }

            // radio toggle logic to ensure only one type is active
            function clearDept(){ if (deptIds.length){ deptIds.length = 0; deptNames.length = 0; renderList(deptIds, deptNames, deptHidden, deptDisplay); } }
            function clearPerson(){ if (personIds.length){ personIds.length = 0; personNames.length = 0; renderList(personIds, personNames, personHidden, personDisplay); } }

            function setMode(mode){
                if (mode === 'persona'){
                    if (personBlock) personBlock.style.display = '';
                    if (deptBlock) deptBlock.style.display = 'none';
                    clearDept();
                } else if (mode === 'departamento'){
                    if (deptBlock) deptBlock.style.display = '';
                    if (personBlock) personBlock.style.display = 'none';
                    clearPerson();
                } else {
                    if (personBlock) personBlock.style.display = 'none';
                    if (deptBlock) deptBlock.style.display = 'none';
                }
            }

            // attach radio events
            var radios = document.querySelectorAll('input[name="destino_tipo"]');
            radios.forEach(function(r){ r.addEventListener('change', function(){ if (this.checked) setMode(this.value); }); });

            // initialize hidden by default
            setMode(null);

            // Departments (same behavior)
            var addDeptBtn = document.getElementById('add_dept_btn');
            var deptSelect = document.getElementById('departamento');
            var deptHidden = document.getElementById('_departamentos_a_enviar');
            var deptDisplay = document.getElementById('departamentos_list_display');
            var deptIds = [], deptNames = [];

            if (addDeptBtn && deptSelect && deptHidden){
                addDeptBtn.addEventListener('click', function(){
                    var val = deptSelect.value;
                    var txt = deptSelect.options[deptSelect.selectedIndex].text;
                    if (!val) return;
                    if (deptIds.indexOf(val) === -1){ deptIds.push(val); deptNames.push(txt); renderList(deptIds, deptNames, deptHidden, deptDisplay); }
                });
            }

            if (deptDisplay){
                deptDisplay.addEventListener('click', function(e){
                    var t = e.target;
                    if (t && t.classList.contains('remove-item')){
                        var idx = parseInt(t.getAttribute('data-index'), 10);
                        if (!isNaN(idx)){
                            deptIds.splice(idx,1);
                            deptNames.splice(idx,1);
                            renderList(deptIds, deptNames, deptHidden, deptDisplay);
                        }
                    }
                });
            }
        })();
    </script>
</body>
</html>