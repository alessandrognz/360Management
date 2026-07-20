---
tags: [docs, base-de-datos]
---

# Base de Datos

Base de datos: `users`
Motor: MySQL con procedimientos almacenados
Patrón: borrado lógico (`eliminado BIT DEFAULT(0)`)

## Esquema de tablas

### `departamento`

| Campo | Tipo | Notas |
|-------|------|-------|
| `id_departamento` | INT PK AUTO_INCREMENT | |
| `nombre` | VARCHAR | |
| `eliminado` | BIT DEFAULT(0) | Borrado lógico |

**Datos iniciales (7 departamentos):**
1. Dirección y Estrategia
2. Administración y Finanzas
3. RRHH
4. Marketing y Ventas
5. Tecnología y Desarrollo
6. Operaciones/Logística/Producto
7. Legal y Calidad

---

### `puesto`

| Campo | Tipo | Notas |
|-------|------|-------|
| `id_puesto` | INT PK AUTO_INCREMENT | |
| `id_departamento` | INT FK | → `departamento.id_departamento` |
| `nombre` | VARCHAR | |
| `eliminado` | BIT DEFAULT(0) | Borrado lógico |

**FK**: `fk_departamento_puesto` (`id_departamento` → `departamento.id_departamento`)

20 puestos iniciales distribuidos entre los 7 departamentos.

---

### `usuarios`

| Campo | Tipo | Notas |
|-------|------|-------|
| `id_usuario` | INT PK AUTO_INCREMENT | |
| `id_puesto` | INT FK | → `puesto.id_puesto` |
| `nombre` | VARCHAR | |
| `email` | VARCHAR UNIQUE | |
| `fecha_registro` | DATETIME | |
| `contrasena` | VARCHAR | Hash BCRYPT |
| `eliminado` | BIT DEFAULT(0) | Borrado lógico |

---

## Procedimientos almacenados

Todos definidos en `db.sql`. `procedure.sql` está vacío (reservado para futuros procedimientos).

### `INSERTAR_USUARIO(id_puesto, nombre, email, contrasena)`

Inserta un usuario nuevo en la tabla `usuarios`.
- **Devuelve**: `row_count()` como `response` (1 si insertado, 0 si error/email duplicado)
- **Usado en**: `includes/db.php` → función `INSERTAR_USUARIO()`

---

### `INICIAR_SESION(_email)`

Devuelve los datos del usuario si existe y `eliminado = 0`.
- **Devuelve**: `id_usuario`, `nombre`, `email`, `contrasena`, `id_puesto`
- **Usado en**: `includes/db.php` → función `INICIAR_SESION()`
- La verificación de contraseña se hace en PHP con `password_verify()`, no en el procedimiento
- ✅ Ampliado el 2026-07-19 (tarea 1.1 completada)

---

## Procedimientos pendientes

| Procedimiento | Para qué | Tarea |
|--------------|----------|-------|
| `OBTENER_PUESTO_DEPARTAMENTO(id_puesto)` | Panel: mostrar puesto y dpto del usuario | [[bloque-3-panel-session]] tarea 3.4 |
| `ACTUALIZAR_NOMBRE(id_usuario, nombre)` | Settings: cambiar nombre | [[bloque-5-funcionalidades-futuras]] |
| `CAMBIAR_CONTRASENA(id_usuario, nueva_contrasena)` | Settings: cambiar contraseña | [[bloque-5-funcionalidades-futuras]] |
| `CREAR_TAREA(...)` | Tasks | [[bloque-5-funcionalidades-futuras]] |
| `LISTAR_TAREAS(id_usuario)` | Tasks | [[bloque-5-funcionalidades-futuras]] |
| `ENVIAR_MENSAJE(...)` | Inbox | [[bloque-5-funcionalidades-futuras]] |
| `LISTAR_INBOX(id_usuario)` | Inbox | [[bloque-5-funcionalidades-futuras]] |
| `CONTAR_NO_LEIDOS(id_usuario)` | Inbox + tarjeta panel | [[bloque-5-funcionalidades-futuras]] |

## Archivos SQL

| Archivo | Contenido |
|---------|-----------|
| `db.sql` | Creación de tablas + datos iniciales + procedimientos actuales |
| `procedure.sql` | Vacío — reservado para futuros procedimientos (o añadir directamente a `db.sql`) |

> [!info] Sobre `procedure.sql`
> Actualmente vacío y puede llevar a confusión. Los procedimientos reales están en `db.sql`. Decidir si los nuevos van en `db.sql` o en `procedure.sql` y documentarlo aquí.

## Referencias

- [[arquitectura]] — flujo de datos completo
- [[bloque-1-sesion-seguridad]] — tarea 1.1: ampliar SELECT de `INICIAR_SESION`
