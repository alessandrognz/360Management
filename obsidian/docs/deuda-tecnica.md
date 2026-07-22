---
tags: [docs, deuda-tecnica, riesgos]
---

# Deuda Técnica y Riesgos Conocidos

Actualizar esta lista cuando se resuelva algo — no dejar entradas obsoletas.

## Activos

**ALTA — `admin.php` sin comprobación de rol.** Solo exige sesión iniciada (`auth_check.php`), no que el usuario sea administrador. Cualquier cuenta autenticada puede listar y eliminar a otros usuarios. Solución: [[bloque-5-funcionalidades-futuras]] tarea 5.4 (columna `rol` + helper `es_admin()`). Ver también [[admin-php]].

**MEDIA — Datos de sesión inconsistentes entre login y registro.** Tras login, `$_SESSION` guarda `id_puesto`; tras registro, guarda `id_departamento`. Cualquier página que asuma uno de los dos fallará según cómo llegó el usuario. Ver [[arquitectura]].

**MEDIA — Procedimiento y tabla de tareas inexistentes.** `TBL_TAREA::SELECT_TAREAS_GENERALES()` llama a `CALL SELECT_TAREAS_GENERALES(?)`, que no existe en `db.sql`. Tampoco existe ninguna tabla de tareas — la línea `create index idx_tu_usuario on tareas_usuarios(id_usuario);` en `db.sql` referencia una tabla `tareas_usuarios` que no se ha creado. Ver [[bloque-5-funcionalidades-futuras]] tarea 5.1.

**MEDIA — Sin validación de servidor en `index.php`.** Ni formato de email, ni longitud de contraseña, ni email duplicado comprobado antes del insert (depende del `UNIQUE` de BD). Mensajes de error como `alert()` de JavaScript. Solución: [[bloque-4-validacion-mensajes]].

**MEDIA — Tarjetas de resumen en `session.php` inexistentes.** El panel no muestra tareas pendientes, mensajes ni el puesto/departamento del usuario. Solución: [[bloque-3-panel-session]].

**BAJA — Restos de depuración en `index.php`.** Varios `echo '<script>console.log(...)'` de prueba quedaron en el flujo de login/registro.

**BAJA — `<title>` incorrecto en `admin.php`.** Dice "Bandeja de entrada" (copiado de `inbox.php`).

## Resueltos

- Fallback de contraseña en texto plano — eliminado
- `session.php` sin protección de acceso — resuelto con `auth_check.php`
- Sin logout — resuelto con `includes/logout.php`
- Nav duplicado en cada página — resuelto, extraído a `includes/nav.php`
- `procedure.sql` vacío y confuso — resuelto, el archivo ya no existe, todo vive en `db.sql`
- Bloque 1 (sesión y seguridad) y Bloque 2 (estructura reutilizable) — completados
- Bloque 6 (panel de administración de usuarios) — completado, ver [[admin-php]]
- Cambio de contraseña y eliminar cuenta en `settings.php` — completados

## Referencias

- [[bloque-3-panel-session]] — [[bloque-4-validacion-mensajes]] — [[bloque-5-funcionalidades-futuras]]
- [[admin-php]] — [[arquitectura]] — [[base-de-datos]]
