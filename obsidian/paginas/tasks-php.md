---
tags: [pagina, privada, en-desarrollo]
archivo: tasks.php
estado: en-desarrollo
tipo: privada
---

# tasks.php — Gestión de Tareas

## Qué hace hoy

Solo la cáscara: título y un botón "Añadir Tarea +" sin acción. Sin listado, sin CRUD.

En `includes/db.php` ya existe una clase `TBL_TAREA` con `SELECT_TAREAS_GENERALES(id)`, pero llama al procedimiento `SELECT_TAREAS_GENERALES`, que no existe en `db.sql` — falla si se ejecuta. No existe tampoco la tabla de tareas.

## Qué falta

- Tabla de tareas en BD (y el índice suelto `idx_tu_usuario` en `db.sql` ya referencia una tabla `tareas_usuarios` que tampoco existe — ver [[deuda-tecnica]])
- Procedimientos: crear, listar, cambiar estado
- Listado + botón de añadir funcional
- Ver: [[bloque-5-funcionalidades-futuras]] tarea 5.1

## Referencias

- [[session-php]] — enlaza desde la sidebar
- [[bloque-5-funcionalidades-futuras]] — tarea 5.1
- [[deuda-tecnica]] — procedimiento y tabla faltantes
