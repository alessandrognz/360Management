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

/* ============================================================
   Selector de fecha y hora — factory reutilizable.
   Uso: var dtp = initDTP('id-del-contenedor', { onChange, validateOnSubmit });
   API pública: setValue(str), clear(), enable(), disable()
   ============================================================ */
function initDTP(rootId, opts) {
    opts = opts || {};
    var root = document.getElementById(rootId);
    if (!root) return null;

    var trigger = root.querySelector('.dtp-trigger');
    var valueEl = root.querySelector('.dtp-value');
    var hidden  = root.querySelector('input[type="hidden"]');
    var placeholder = valueEl.getAttribute('data-placeholder');
    var STEP = 5; // paso de minutos

    var MONTHS = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    var WEEKDAYS = ['L', 'M', 'X', 'J', 'V', 'S', 'D'];

    function pad(n) { return (n < 10 ? '0' : '') + n; }
    function ymd(d) { return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()); }
    function parseLocal(s) {
        var m = /^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2})/.exec(s || '');
        if (!m) { var now = new Date(); now.setSeconds(0, 0); return now; }
        return new Date(+m[1], +m[2] - 1, +m[3], +m[4], +m[5], 0, 0);
    }
    function optionExists(sel, val) {
        for (var i = 0; i < sel.options.length; i++) {
            if (parseInt(sel.options[i].value, 10) === val) return true;
        }
        return false;
    }

    var min = parseLocal(root.dataset.min);                               // límite inferior (ahora)
    var minDay = new Date(min.getFullYear(), min.getMonth(), min.getDate());
    var selected = null;                                                  // Date elegido, o null
    var view = { y: min.getFullYear(), m: min.getMonth() };               // mes visible

    // --- Construcción del popover ---
    var pop = document.createElement('div');
    pop.className = 'dtp-pop';
    pop.setAttribute('role', 'dialog');
    pop.setAttribute('aria-label', 'Elegir fecha y hora');
    pop.hidden = true;
    pop.innerHTML =
        '<div class="dtp-head">' +
            '<button type="button" class="dtp-nav" data-nav="-1" aria-label="Mes anterior">&#8249;</button>' +
            '<span class="dtp-title"></span>' +
            '<button type="button" class="dtp-nav" data-nav="1" aria-label="Mes siguiente">&#8250;</button>' +
        '</div>' +
        '<div class="dtp-weekdays">' + WEEKDAYS.map(function (w) { return '<span>' + w + '</span>'; }).join('') + '</div>' +
        '<div class="dtp-grid"></div>' +
        '<div class="dtp-time">' +
            '<span class="dtp-time-label">Hora</span>' +
            '<div class="dtp-time-selects">' +
                '<select class="dtp-select" data-time="h" aria-label="Hora"></select>' +
                '<span class="dtp-colon">:</span>' +
                '<select class="dtp-select" data-time="m" aria-label="Minutos"></select>' +
            '</div>' +
        '</div>' +
        '<div class="dtp-foot">' +
            '<button type="button" class="dtp-btn dtp-btn-ghost" data-act="clear">Limpiar</button>' +
            '<button type="button" class="dtp-btn dtp-btn-primary" data-act="done">Listo</button>' +
        '</div>';
    // Se monta en <body> para no quedar recortado por el overflow del modal
    document.body.appendChild(pop);

    var titleEl = pop.querySelector('.dtp-title');
    var gridEl = pop.querySelector('.dtp-grid');
    var prevBtn = pop.querySelector('[data-nav="-1"]');
    var selH = pop.querySelector('[data-time="h"]');
    var selM = pop.querySelector('[data-time="m"]');

    // Rellena las horas disponibles (respeta el mínimo si el día es hoy)
    function fillHours() {
        var onMinDay = selected && ymd(selected) === ymd(minDay);
        var minH = onMinDay ? min.getHours() : 0;
        selH.innerHTML = '';
        for (var h = minH; h <= 23; h++) selH.add(new Option(pad(h), h));
    }
    // Rellena los minutos disponibles según la hora elegida
    function fillMinutes() {
        var onMinDay = selected && ymd(selected) === ymd(minDay);
        var h = parseInt(selH.value, 10);
        var minM = (onMinDay && h === min.getHours()) ? Math.ceil(min.getMinutes() / STEP) * STEP : 0;
        selM.innerHTML = '';
        for (var m = minM; m <= 59; m += STEP) selM.add(new Option(pad(m), m));
    }
    // Hora por defecto al elegir un día (siguiente hueco válido si es hoy, 09:00 si no)
    function defaultTime(dayDate) {
        if (ymd(dayDate) === ymd(minDay)) {
            var d = new Date(min);
            var mm = Math.ceil(d.getMinutes() / STEP) * STEP;
            d.setSeconds(0, 0);
            if (mm > 55) { d.setHours(d.getHours() + 1); d.setMinutes(0, 0, 0); }
            else { d.setMinutes(mm, 0, 0); }
            return { h: d.getHours(), m: d.getMinutes() };
        }
        return { h: 9, m: 0 };
    }
    // Sincroniza los selects con la fecha seleccionada
    function syncSelects() {
        fillHours();
        var h = selected.getHours();
        if (!optionExists(selH, h)) { h = parseInt(selH.options[0].value, 10); selected.setHours(h); }
        selH.value = h;
        fillMinutes();
        var mm = Math.round(selected.getMinutes() / STEP) * STEP;
        if (mm > 55) mm = 55;
        if (!optionExists(selM, mm)) mm = parseInt(selM.options[0].value, 10);
        selected.setMinutes(mm, 0, 0);
        selM.value = mm;
    }

    function render() {
        titleEl.textContent = MONTHS[view.m] + ' ' + view.y;
        prevBtn.disabled = (view.y === min.getFullYear() && view.m === min.getMonth());

        gridEl.innerHTML = '';
        var first = new Date(view.y, view.m, 1);
        var offset = (first.getDay() + 6) % 7; // lunes primero
        var daysInMonth = new Date(view.y, view.m + 1, 0).getDate();

        for (var i = 0; i < offset; i++) {
            var e = document.createElement('span');
            e.className = 'dtp-day dtp-empty';
            gridEl.appendChild(e);
        }
        for (var d = 1; d <= daysInMonth; d++) {
            var cell = new Date(view.y, view.m, d);
            var b = document.createElement('button');
            b.type = 'button';
            b.className = 'dtp-day';
            b.textContent = d;
            b.dataset.day = d;
            if (cell < minDay) { b.disabled = true; b.classList.add('is-disabled'); }
            if (ymd(cell) === ymd(minDay)) b.classList.add('is-today');
            if (selected && ymd(cell) === ymd(selected)) b.classList.add('is-selected');
            gridEl.appendChild(b);
        }
        if (!pop.hidden) position();
    }

    function commit() {
        if (!selected) {
            hidden.value = '';
            valueEl.textContent = placeholder;
            valueEl.classList.add('is-placeholder');
            if (opts.onChange) opts.onChange('');
            return;
        }
        hidden.value = selected.getFullYear() + '-' + pad(selected.getMonth() + 1) + '-' +
            pad(selected.getDate()) + 'T' + pad(selected.getHours()) + ':' + pad(selected.getMinutes());
        valueEl.textContent = pad(selected.getDate()) + '/' + pad(selected.getMonth() + 1) + '/' +
            selected.getFullYear() + '  ·  ' + pad(selected.getHours()) + ':' + pad(selected.getMinutes());
        valueEl.classList.remove('is-placeholder');
        trigger.classList.remove('dtp-trigger--error');
        if (opts.onChange) opts.onChange(hidden.value);
    }

    // --- Interacción ---
    gridEl.addEventListener('click', function (e) {
        var b = e.target.closest('.dtp-day');
        if (!b || b.disabled || b.classList.contains('dtp-empty')) return;
        var d = parseInt(b.dataset.day, 10);
        if (!selected) {
            var t = defaultTime(new Date(view.y, view.m, d));
            selected = new Date(view.y, view.m, d, t.h, t.m, 0, 0);
        } else {
            selected.setFullYear(view.y, view.m, d);
        }
        if (selected < min) {
            var t2 = defaultTime(new Date(view.y, view.m, d));
            selected.setHours(t2.h, t2.m, 0, 0);
        }
        syncSelects();
        render();
        commit();
    });

    selH.addEventListener('change', function () {
        if (!selected) return;
        selected.setHours(parseInt(selH.value, 10));
        fillMinutes();
        var mm = parseInt(selM.value, 10);
        if (isNaN(mm) || !optionExists(selM, mm)) mm = parseInt(selM.options[0].value, 10);
        selected.setMinutes(mm, 0, 0);
        selM.value = mm;
        commit();
    });
    selM.addEventListener('change', function () {
        if (!selected) return;
        selected.setMinutes(parseInt(selM.value, 10), 0, 0);
        commit();
    });

    pop.addEventListener('click', function (e) {
        e.stopPropagation();
        var nav = e.target.closest('[data-nav]');
        if (nav && !nav.disabled) {
            var nm = view.m + parseInt(nav.dataset.nav, 10);
            view = { y: view.y + Math.floor(nm / 12), m: ((nm % 12) + 12) % 12 };
            render();
            return;
        }
        var act = e.target.closest('[data-act]');
        if (act) {
            if (act.dataset.act === 'clear') {
                selected = null;
                fillHours(); fillMinutes();
                commit(); render();
            } else if (act.dataset.act === 'done') {
                closePop();
            }
        }
    });

    // Coloca el popover respecto al campo (debajo, o encima si no hay hueco)
    function position() {
        var r = trigger.getBoundingClientRect();
        var vw = document.documentElement.clientWidth;
        var vh = document.documentElement.clientHeight;
        var w = pop.offsetWidth;
        var h = pop.offsetHeight;
        var left = Math.min(r.right - w, vw - w - 8);
        left = Math.max(8, left);
        var top = r.bottom + 6;
        if (top + h > vh - 8) {
            var above = r.top - 6 - h;
            top = above >= 8 ? above : Math.max(8, vh - h - 8);
        }
        pop.style.left = left + 'px';
        pop.style.top = top + 'px';
    }

    function open() {
        if (trigger.disabled) return;
        if (selected) { view = { y: selected.getFullYear(), m: selected.getMonth() }; }
        else { view = { y: min.getFullYear(), m: min.getMonth() }; fillHours(); fillMinutes(); }
        render();
        pop.style.visibility = 'hidden';
        pop.hidden = false;
        position();
        pop.style.visibility = '';
        trigger.setAttribute('aria-expanded', 'true');
    }
    function closePop() {
        pop.hidden = true;
        trigger.setAttribute('aria-expanded', 'false');
    }

    trigger.addEventListener('click', function (e) {
        e.stopPropagation();
        if (pop.hidden) open(); else closePop();
    });
    document.addEventListener('click', function (e) {
        if (!pop.hidden && !root.contains(e.target) && !pop.contains(e.target)) closePop();
    });
    window.addEventListener('resize', function () { if (!pop.hidden) position(); });
    window.addEventListener('scroll', function () { if (!pop.hidden) position(); }, true);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !pop.hidden) { e.stopPropagation(); closePop(); }
    }, true);

    // Validación al enviar: la fecha es obligatoria (solo si validateOnSubmit no es false)
    if (opts.validateOnSubmit !== false) {
        var form = trigger.closest('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                if (!hidden.value) {
                    e.preventDefault();
                    trigger.classList.add('dtp-trigger--error');
                    open();
                }
            });
        }
    }

    /* ——— API pública ——— */
    function dtpSetValue(str) {
        if (!str) { selected = null; commit(); return; }
        var norm = String(str).replace(' ', 'T').substring(0, 16);
        var m = /^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2})/.exec(norm);
        if (!m) { selected = null; commit(); return; }
        selected = new Date(+m[1], +m[2] - 1, +m[3], +m[4], +m[5], 0, 0);
        view = { y: selected.getFullYear(), m: selected.getMonth() };
        commit();
        if (opts.onChange) opts.onChange(hidden.value);
    }

    return {
        setValue: dtpSetValue,
        clear:    function () { selected = null; commit(); if (opts.onChange) opts.onChange(''); },
        enable:   function () { trigger.disabled = false; },
        disable:  function () { trigger.disabled = true; closePop(); }
    };
}

/* Instancia para el modal "Crear tarea" */
initDTP('dtp-fecha');
