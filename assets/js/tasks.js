(function(){
    function escapeHtml(str){ return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

    // Modal "Crear tarea"
    var modalCrearTarea = document.getElementById('modal-crear-tarea');
    var btnCrearTarea = document.getElementById('btn-crear-tarea');

    function openModal(modal){ modal.classList.add('active'); }
    function closeModal(modal){ modal.classList.remove('active'); }

    if (btnCrearTarea && modalCrearTarea){
        btnCrearTarea.addEventListener('click', function(){ openModal(modalCrearTarea); });
    }

    document.querySelectorAll('.modal-close').forEach(function(btn){
        btn.addEventListener('click', function(){
            closeModal(document.getElementById(btn.dataset.modal));
        });
    });

    if (modalCrearTarea){
        modalCrearTarea.addEventListener('click', function(e){
            if (e.target === modalCrearTarea) closeModal(modalCrearTarea);
        });
    }

    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape' && modalCrearTarea) closeModal(modalCrearTarea);
    });

    // Pestañas Generales / Personales
    var taskTabs = document.querySelectorAll('.task-tab');
    var taskPanels = document.querySelectorAll('[data-tab-panel]');

    taskTabs.forEach(function(tab){
        tab.addEventListener('click', function(){
            taskTabs.forEach(function(t){ t.classList.remove('is-active'); t.setAttribute('aria-selected', 'false'); });
            tab.classList.add('is-active');
            tab.setAttribute('aria-selected', 'true');
            taskPanels.forEach(function(panel){
                panel.hidden = panel.dataset.tabPanel !== tab.dataset.tab;
            });
        });
    });

    // Generic renderer for list with remove buttons
    function renderList(ids, names, hiddenInput, display){
        hiddenInput.value = ids.join(',');
        if (!names.length){ display.innerHTML = ''; return; }
        var parts = names.map(function(name, i){
            return '<span class="selected-item" data-index="'+i+'">'+escapeHtml(name)+' <button type="button" class="remove-item" data-index="'+i+'">x</button></span>';
        });
        display.innerHTML = '<strong>Seleccionados:</strong> ' + parts.join(' ');
    }

    // Users
    var personSelect = document.getElementById('personal');
    var personHidden = document.getElementById('_personas_a_enviar');
    var personDisplay = document.getElementById('personas_list_display');
    var personIds = [], personNames = [];
    var personBlock = document.getElementById('person_block');
    var deptBlock = document.getElementById('dept_block');
    var tipoPerson = document.getElementById('tipo_persona');
    var tipoDept = document.getElementById('tipo_departamento');

    if (personSelect && personHidden){
        personSelect.addEventListener('change', function(){
            var val = personSelect.value;
            var txt = personSelect.options[personSelect.selectedIndex].text;
            if (!val) return;
            if (personIds.indexOf(val) === -1){ personIds.push(val); personNames.push(txt); renderList(personIds, personNames, personHidden, personDisplay); }
            personSelect.selectedIndex = 0;
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
            if (personBlock) personBlock.hidden = false;
            if (deptBlock) deptBlock.hidden = true;
            clearDept();
        } else if (mode === 'departamento'){
            if (deptBlock) deptBlock.hidden = false;
            if (personBlock) personBlock.hidden = true;
            clearPerson();
        } else {
            if (personBlock) personBlock.hidden = true;
            if (deptBlock) deptBlock.hidden = true;
        }
    }

    // attach radio events
    var radios = document.querySelectorAll('input[name="destino_tipo"]');
    radios.forEach(function(r){ r.addEventListener('change', function(){ if (this.checked) setMode(this.value); }); });

    // initialize hidden by default
    setMode(null);

    // Departments (same behavior)
    var deptSelect = document.getElementById('departamento');
    var deptHidden = document.getElementById('_departamentos_a_enviar');
    var deptDisplay = document.getElementById('departamentos_list_display');
    var deptIds = [], deptNames = [];

    if (deptSelect && deptHidden){
        deptSelect.addEventListener('change', function(){
            var val = deptSelect.value;
            var txt = deptSelect.options[deptSelect.selectedIndex].text;
            if (!val) return;
            if (deptIds.indexOf(val) === -1){ deptIds.push(val); deptNames.push(txt); renderList(deptIds, deptNames, deptHidden, deptDisplay); }
            deptSelect.selectedIndex = 0;
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
