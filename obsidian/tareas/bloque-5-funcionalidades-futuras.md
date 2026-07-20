---
tags: [tareas, bloque-5]
estado: pendiente
bloque: 5
---

# Bloque 5 — Funcionalidades Nuevas

> Secciones principales de la aplicación. No diseñar ni implementar hasta tener cerrados los Bloques 1 y 2 (auth + nav/footer reutilizables). El detalle de diseño de cada sección se definirá cuando llegue el momento.

## Secciones pendientes

- [ ] **5.1 — Tasks (gestión de tareas)**
  Sección para crear, asignar, listar y cambiar el estado de tareas. Sin diseño detallado aún.
  - Archivo nuevo: `tasks.php`
  - Requiere: nuevas tablas en BD, nuevos procedimientos almacenados, nuevo CSS o extensión de `session.css`
  - Ver: [[tasks-php]]

- [ ] **5.2 — Inbox (mensajería interna)**
  Sistema de mensajes entre usuarios de la empresa.
  - Archivo nuevo: `inbox.php`
  - Requiere: nuevas tablas (`mensajes`, quizá `conversaciones`), nuevos procedimientos
  - Ver: [[inbox-php]]

- [ ] **5.3 — Settings (configuración de perfil)**
  Edición de datos personales y cambio de contraseña.
  - Archivo nuevo: `settings.php`
  - Funcionalidades mínimas:
    - Cambiar nombre
    - Cambiar contraseña (verificar actual, hashear nueva con BCRYPT)
    - Cambiar puesto (si se permite)
  - Requiere: procedimiento `ACTUALIZAR_USUARIO` y/o `CAMBIAR_CONTRASENA`
  - Ver: [[settings-php]]

- [ ] **5.4 — Sistema de permisos por puesto/departamento**
  La relación `puesto → departamento` ya existe en BD. Falta definir qué acciones o vistas están restringidas por departamento/puesto y aplicar las comprobaciones en PHP.
  - Posible tabla nueva: `permisos` o columna de nivel de acceso en `puesto`
  - Afecta a: `includes/auth_check.php` (ampliar para comprobar rol) y todas las páginas privadas

## Notas

> [!warning] No empezar este bloque sin los Bloques 1 y 2 cerrados
> Sin auth centralizado (`auth_check.php`) y sin nav como include, cada página nueva implica duplicar código que luego habrá que refactorizar.

> [!info] Orden sugerido dentro del bloque
> Settings → Tasks → Inbox → Permisos
> Settings es más simple y ayuda a validar que el patrón de edición de perfil funciona antes de atacar lógicas más complejas.

## Referencias

- [[bloque-4-validacion-mensajes]] — prerequisito
- [[tasks-php]] — [[inbox-php]] — [[settings-php]] — páginas a crear
- [[bloque-1-sesion-seguridad]] — el sistema de permisos se apoya en los datos de sesión
