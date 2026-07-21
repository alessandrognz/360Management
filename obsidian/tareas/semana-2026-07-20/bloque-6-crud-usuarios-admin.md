---
tags: [tareas, bloque-6, semana-2026-07-20]
estado: pendiente
bloque: 6
semana: "2026-07-20"
---

# Bloque 6 — CRUD Gestión de Usuarios (Panel Admin)

> Panel de administración para listar, editar y desactivar usuarios. Requiere [[bloque-8-roles-permisos]] completado (rol en sesión + `requiere_admin()`).

## Tareas

- [x] **6.1 — Procedimientos de gestión de usuarios**
  Nuevos stored procedures en `db.sql`:
  - `LISTAR_USUARIOS()` — SELECT con JOIN a `puesto` y `departamento`, excluye `eliminado=1`
  - `ACTUALIZAR_USUARIO_ADMIN(id_usuario, nombre, email, id_puesto, rol)` — no toca `contrasena`
  - `ELIMINAR_USUARIO_LOGICO(id_usuario)` — UPDATE `eliminado=1`
  - Registrar en [[base-de-datos]]

- [x] **6.2 — Página `admin.php` — Listado de usuarios**
  Nueva página privada con tabla de todos los usuarios activos:
  - Inicio del archivo: `require_once 'includes/auth_check.php'; require_once 'includes/auth_rol.php'; requiere_admin();`
  - Columnas: Nombre, Email, Puesto, Departamento, Rol, Acciones (Editar | Desactivar)
  - Llamada a `LISTAR_USUARIOS()` desde `includes/db.php`
  - Archivo nuevo: `admin.php`

- [ ] **6.3 — Formulario de edición de usuario**
  Sección en `admin.php` que aparece al pulsar "Editar":
  - Campos: nombre (text), email (email), id_puesto (select), rol (select: admin/usuario)
  - POST a `admin.php` con `accion=editar` e `id_usuario`
  - Llama a `ACTUALIZAR_USUARIO_ADMIN` desde `includes/db.php`
  - Mensajes de éxito/error con el patrón `$error`/`$exito` del [[bloque-4-validacion-mensajes]]

- [ ] **6.4 — Acción de desactivar usuario**
  Botón "Desactivar" en cada fila del listado:
  - POST a `admin.php` con `accion=desactivar` e `id_usuario`
  - Llama a `ELIMINAR_USUARIO_LOGICO`
  - Protección: verificar que `$_POST['id_usuario'] !== $_SESSION['id_usuario']` (un admin no puede desactivarse a sí mismo)

## Notas

> [!warning] No modificar contraseñas desde admin
> `ACTUALIZAR_USUARIO_ADMIN` no incluye el campo `contrasena`. El cambio de contraseña es responsabilidad del propio usuario desde [[settings-php]].

> [!info] Select de puestos en el formulario de edición
> Reutilizar la misma query de puestos que usa `registr.php`. Si no existe como función en `includes/db.php`, extraerla (`LISTAR_PUESTOS()`).

> [!tip] Página admin como sección del nav
> Añadir `admin.php` al array de páginas en `includes/nav.php`, dentro del bloque condicional `es_admin()` definido en [[bloque-8-roles-permisos]].

## Referencias

- [[bloque-8-roles-permisos]] — prerequisito (rol en sesión + helpers)
- [[bloque-5-funcionalidades-futuras]] — contexto de funcionalidades
- [[bloque-4-validacion-mensajes]] — patrón de mensajes error/éxito
- [[base-de-datos]] — nuevos procedimientos
- [[registr-php]] — referencia del select de puestos existente
