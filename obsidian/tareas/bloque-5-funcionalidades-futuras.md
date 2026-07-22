---
tags: [tareas, bloque-5]
estado: pendiente
bloque: 5
---

# Bloque 5 — Funcionalidades Nuevas

Settings (5.3) y Administración de Usuarios (bloque 6) ya están completos. Quedan Tasks, Inbox y el sistema de roles.

## Pendientes

- **5.1 — Tasks (gestión de tareas).** `tasks.php` es solo la cáscara (botón "Añadir Tarea +" sin acción). Ya existe un arranque de clase `TBL_TAREA` en `db.php`, pero llama a un procedimiento (`SELECT_TAREAS_GENERALES`) que no existe en `db.sql` — ver [[deuda-tecnica]]. Falta: tabla de tareas, procedimientos (crear, listar, cambiar estado), listado y formulario de alta. Ver [[tasks-php]].

- **5.2 — Inbox (mensajería interna).** `inbox.php` no tiene contenido. Falta: tabla(s) `mensajes`, procedimientos (enviar, listar, marcar leído, contar no leídos). Ver [[inbox-php]].

- **5.4 — Sistema de roles.** Urgente: `admin.php` no comprueba rol, cualquier usuario autenticado puede entrar y eliminar cuentas — ver [[deuda-tecnica]]. Columna `rol ENUM('admin','usuario')` en `usuarios`, helper `includes/auth_rol.php` con `es_admin()` / `requiere_admin()`. Afecta a: `db.sql`, `includes/db.php`, `includes/nav.php`, `admin.php`.

## Orden sugerido

Roles (5.4) primero — cierra el hueco de seguridad en `admin.php`. Luego Tasks (5.1), luego Inbox (5.2).

## Referencias

- [[bloque-4-validacion-mensajes]] — prerequisito
- [[tasks-php]] — [[inbox-php]] — [[admin-php]] — páginas afectadas
- [[deuda-tecnica]] — riesgo de seguridad en admin.php
