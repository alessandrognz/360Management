---
tags: [docs, base-de-datos]
---

# Base de Datos

Base de datos `users`, MySQL con procedimientos almacenados. Todas las tablas usan borrado lógico (`eliminado BIT DEFAULT(0)`), nunca se borran filas físicamente. Todo definido en `db.sql` (`procedure.sql` ya no existe).

## Tablas

### `departamento`

| Campo | Tipo | Notas |
|-------|------|-------|
| `id_departamento` | INT PK AUTO_INCREMENT | |
| `nombre_departamento` | VARCHAR(150) | |
| `eliminado` | BIT DEFAULT(0) | |

7 departamentos iniciales (Dirección y Estrategia, Administración y Finanzas, Recursos Humanos, Marketing y Ventas, Tecnología y Desarrollo, Operaciones/Logística/Producto, Legal y Calidad).

### `puesto`

| Campo | Tipo | Notas |
|-------|------|-------|
| `id_puesto` | INT PK AUTO_INCREMENT | |
| `id_departamento` | INT FK | → `departamento.id_departamento` |
| `nombre_puesto` | VARCHAR(150) | |
| `descripcion_puesto` | VARCHAR(200) | |
| `eliminado` | BIT DEFAULT(0) | |

20 puestos iniciales distribuidos entre los 7 departamentos.

### `usuarios`

| Campo | Tipo | Notas |
|-------|------|-------|
| `id_usuario` | INT PK AUTO_INCREMENT | |
| `id_puesto` | INT FK | → `puesto.id_puesto` |
| `nombre` | VARCHAR(50) | |
| `email` | VARCHAR(100) UNIQUE | |
| `fecha_registro` | DATETIME | default `current_timestamp` |
| `contrasena` | VARCHAR(250) | hash BCRYPT |
| `eliminado` | BIT DEFAULT(0) | |

Sin columna `rol` todavía — pendiente [[bloque-5-funcionalidades-futuras]] tarea 5.4.

## Procedimientos almacenados

| Procedimiento | Para qué | Usado en |
|---|---|---|
| `INSERTAR_USUARIO(id_puesto, nombre, email, contrasena)` | Alta de usuario, devuelve `id_usuario`, `nombre`, `id_departamento` | `loginAndRegister::INSERTAR_USUARIO()` |
| `VERIFICAR_EMAIL(email)` | Comprueba si el email existe | `loginAndRegister::INICIAR_SESION()` |
| `VERIFICAR_CONTRASENA(email)` | Devuelve `contrasena`, `nombre`, `id_usuario`, `id_puesto` para verificar login | `loginAndRegister::INICIAR_SESION()`, `CRUD_USER::CAMBIAR_CONTRASENA()` |
| `MOSTRAR_USUARIO(id_usuario)` | Datos de un usuario + `nombre_puesto` (JOIN) | `CRUD_USER::MOSTRAR_USUARIO()` |
| `MOSTRAR_USUARIOS()` | Todos los usuarios no eliminados + `nombre_puesto` (JOIN) | `CRUD_USER::MOSTRAR_USUARIOS()` |
| `CAMBIAR_NOMBRE_USUARIO(id_usuario, nombre)` | Actualiza el nombre | `CRUD_USER::CAMBIAR_NOMBRE_USUARIO()` |
| `CAMBIAR_CONTRASENA(id_usuario, contrasena)` | Actualiza el hash de contraseña | `CRUD_USER::CAMBIAR_CONTRASENA()` (vía `REMPLAZAR_CONTRASENA`) |
| `ELIMINAR_USUARIO(nombre)` | Borrado lógico por nombre | `CRUD_USER::ELIMINAR_USUARIO()` |
| `ELIMINAR_USUARIO_LOGICO(id_usuario)` | Borrado lógico por id | `CRUD_USER::ELIMINAR_USUARIO_LOGICO()` — usado en `admin.php` y `includes/delete_user.php` |

## Pendiente / roto

- `SELECT_TAREAS_GENERALES(id)` — llamado desde `TBL_TAREA` en `db.php`, pero el procedimiento no existe en `db.sql`. Falla si se ejecuta.
- Tabla de tareas — no existe ninguna todavía. `db.sql` tiene una línea suelta `create index idx_tu_usuario on tareas_usuarios(id_usuario);` que referencia una tabla `tareas_usuarios` que tampoco existe.
- Tabla(s) de mensajería para [[inbox-php]] — no existen.
- Columna `rol` en `usuarios` — no existe, ver [[bloque-5-funcionalidades-futuras]] tarea 5.4.

## Referencias

- [[arquitectura]] — flujo de datos completo
- [[deuda-tecnica]] — detalle de los riesgos anteriores
