---
tags: [tareas, bloque-7, semana-2026-07-20]
estado: pendiente
bloque: 7
semana: "2026-07-20"
---

# Bloque 7 — CRUD de Tareas (`tasks.php`)

> Implementación real del placeholder `tasks.php` (creado 2026-07-19). Expande [[bloque-5-funcionalidades-futuras]] tarea 5.1. Usa `es_admin()` de [[bloque-8-roles-permisos]] para filtrar vistas.

## Tareas

- [x] **7.1 — Tabla `tarea` en BD**
  Crear la tabla en `db.sql`:
  - Archivo afectado: `db.sql`

- [ ] **7.2 — Procedimientos de tareas**
  En `db.sql`, cinco procedimientos:
  - `CREAR_TAREA(id_creador, titulo, descripcion, id_asignado, fecha_limite)`
  - `LISTAR_TAREAS_USUARIO(id_usuario)` — donde `id_creador=id OR id_asignado=id` y `eliminado=0`
  - `LISTAR_TAREAS_TODAS()` — para admin: todas sin filtro de usuario
  - `CAMBIAR_ESTADO_TAREA(id_tarea, nuevo_estado)` — solo actualiza `estado`
  - `ELIMINAR_TAREA(id_tarea)` — borrado lógico (`eliminado=1`)
  - Registrar en [[base-de-datos]]

- [ ] **7.3 — Listado de tareas en `tasks.php`**
  Tabla HTML con las tareas del usuario activo:
  - Usuario común: llama a `LISTAR_TAREAS_USUARIO($_SESSION['id_usuario'])`
  - Admin: llama a `LISTAR_TAREAS_TODAS()` (condicional con `es_admin()`)
  - Columnas: Título, Estado (badge de color), Asignada a, Fecha límite, Acciones
  - Archivo afectado: `tasks.php`

- [ ] **7.4 — Formulario de creación de tarea**
  Sección en la parte superior de `tasks.php`:
  - Campos: título (text, obligatorio), descripción (textarea), asignado a (select con usuarios activos), fecha límite (date, opcional)
  - POST a `tasks.php` con `accion=crear`
  - Validar: título no vacío antes de llamar a `CREAR_TAREA`

- [ ] **7.5 — Cambio de estado inline**
  Botón de acción rápida en cada fila del listado:
  - POST a `tasks.php` con `accion=estado`, `id_tarea`, `nuevo_estado`
  - Lógica de estado siguiente: `pendiente → en_progreso → completada → pendiente`
  - Restricción: solo el creador, el asignado o un admin pueden cambiar el estado

- [ ] **7.6 — Eliminación lógica de tarea**
  Botón "Eliminar" en el listado:
  - POST a `tasks.php` con `accion=eliminar` e `id_tarea`
  - Verificar antes de llamar: `id_creador === $_SESSION['id_usuario']` o `es_admin()`

- [ ] **7.7 — Conectar tarjeta de resumen en `session.php`**
  Añadir `CONTAR_TAREAS_PENDIENTES(id_usuario)` al listado de procedimientos y usarlo en la tarjeta "Tareas pendientes" del panel. Sustituye el `0` hardcodeado de [[bloque-3-panel-session]] tarea 3.2.
  - Archivos afectados: `db.sql`, `includes/db.php`, `session.php`

## Notas

> [!info] Select de usuarios para asignación
> Reutilizar `LISTAR_USUARIOS()` de [[bloque-6-crud-usuarios-admin]] (o una versión ligera `LISTAR_USUARIOS_ACTIVOS()` con solo `id_usuario` y `nombre` si el listado completo es costoso).

> [!tip] Badges de estado con CSS
> Usar clases `.estado-pendiente`, `.estado-en_progreso`, `.estado-completada` en `session.css` con colores del tema:
> - `pendiente` → gris / neutro
> - `en_progreso` → azul / amarillo
> - `completada` → verde primario `#4a6741`

> [!warning] Restricción de acciones por propiedad
> Verificar siempre en PHP (no solo en la UI) que quien actúa sobre una tarea es el creador, el asignado o un admin. La UI puede ocultar botones, pero la validación es obligatoria en el servidor.

## Referencias

- [[bloque-5-funcionalidades-futuras]] — tarea 5.1 que este bloque implementa
- [[bloque-8-roles-permisos]] — `es_admin()` para filtrar y autorizar acciones
- [[bloque-6-crud-usuarios-admin]] — `LISTAR_USUARIOS()` reutilizable para el select de asignación
- [[bloque-3-panel-session]] — tarjeta de tareas pendientes (tarea 3.2) que 7.7 completa
- [[bloque-4-validacion-mensajes]] — patrón de mensajes error/éxito
- [[tasks-php]] — página afectada
- [[base-de-datos]] — esquema y procedimientos
