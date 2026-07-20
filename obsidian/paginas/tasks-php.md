---
tags: [pagina, privada, placeholder]
archivo: tasks.php
estado: placeholder
tipo: privada
---

# tasks.php — Gestión de Tareas

## Qué hará

Sección de gestión de tareas del equipo/empresa. Sin CRUD implementado aún.

## Estado

🔲 Creada como placeholder (2026-07-19). Tiene nav, auth y estructura base pero sin funcionalidad real. Ver [[bloque-5-funcionalidades-futuras]], tarea 5.1 para el CRUD completo.

## Funcionalidades previstas (sin definir aún en detalle)

- Listar tareas (propias y/o del departamento)
- Crear nueva tarea (título, descripción, asignado a, prioridad, fecha límite)
- Cambiar estado de una tarea (pendiente, en progreso, completada)
- Filtrar tareas por estado, departamento, asignado

## Requisitos de BD pendientes

- Nueva(s) tabla(s): al menos `tareas`, posiblemente `tarea_asignados`
- Nuevos procedimientos almacenados: `CREAR_TAREA`, `LISTAR_TAREAS`, `ACTUALIZAR_ESTADO_TAREA`, etc.
- Añadir en: `db.sql` y `procedure.sql`

## Requisitos de includes

- `require 'includes/auth_check.php'` al inicio (Bloque 1)
- `require 'includes/nav.php'` para el nav (Bloque 2)
- `require 'includes/footer_privado.php'` (Bloque 2)

## Notas

> [!warning] Prerequisitos
> No crear este archivo hasta tener cerrados los Bloques 1 y 2. De lo contrario habrá que refactorizar el nav y el auth cuando se cierren.

> [!info] Link en nav
> `session.php` ya tiene un link a `tasks.php` en el nav (actualmente sin destino real). Una vez creada la página, el link funciona automáticamente.

## Referencias

- [[session-php]] — enlaza a tasks desde el nav
- [[bloque-5-funcionalidades-futuras]] — tarea 5.1
- [[bloque-1-sesion-seguridad]] — prerequisito (auth)
- [[bloque-2-estructura-reutilizable]] — prerequisito (nav/footer)
