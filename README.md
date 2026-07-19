# 360Management

Aplicación web de gestión de usuarios/empresa construida con PHP procedural y MySQL. Proyecto de aprendizaje/portfolio orientado a seguridad, buenas prácticas y arquitectura escalable.

## Stack

| Capa | Tecnología |
|------|------------|
| Frontend | HTML + CSS puro (estética Aero/Vista) |
| Backend | PHP procedural — extensión `mysqli` |
| Base de datos | MySQL con procedimientos almacenados |
| Entorno local | XAMPP en Windows (Apache + MySQL) |

## Estructura del proyecto PHP

```
360Management/
├─ index.html          Bienvenida pública (logo + botones)
├─ ini.php             Login — formulario + procesamiento
├─ registr.php         Registro — formulario + procesamiento
├─ session.php         Panel privado (home interno)
├─ includes/
│   ├─ db.php          Conexión + funciones PHP (llaman a procedimientos almacenados)
│   └─ auth_check.php  (pendiente) Comprobación de sesión activa
├─ css/
│   ├─ index.css       Estilos públicos (fondo, formulario, botones)
│   └─ session.css     Estilos del panel
├─ img/                Imágenes
├─ db.sql              Script de creación de la base de datos
└─ procedure.sql       Reservado para procedimientos adicionales
```

## Base de datos

Base de datos `users`, 3 tablas relacionadas con borrado lógico (`eliminado BIT DEFAULT(0)`):

- **`departamento`** — 7 departamentos
- **`puesto`** — 20 puestos (FK a `departamento`)
- **`usuarios`** — id, puesto, nombre, email, fecha_registro, contraseña, eliminado

### Procedimientos almacenados

- `INSERTAR_USUARIO(id_puesto, nombre, email, contrasena)` — alta de usuario
- `INICIAR_SESION(_email, _contrasena)` — autenticación ⚠️ pendiente ampliar campos devueltos

## Estado actual

- ✅ Registro funcional con `password_hash()` (BCRYPT)
- ✅ Login funcional con `password_verify()`
- ✅ Base de datos con datos iniciales
- ⚠️ `session.php` sin protección de acceso
- ⚠️ Sin logout implementado
- ⚠️ Sin nav/footer extraídos a includes

## Hoja de ruta

Ver carpeta [`tareas/`](tareas/) para el detalle por bloques.

1. **Bloque 1** — Sesión y seguridad (prioridad alta)
2. **Bloque 2** — Estructura reutilizable (nav/footer como includes)
3. **Bloque 3** — Contenido del panel
4. **Bloque 4** — Validación y mensajes de usuario
5. **Bloque 5** — Tasks, Inbox, Settings

## Convenciones del proyecto

- PHP procedural en **español** (`$Coneccion`, `INSERTAR_USUARIO`)
- SQL solo vía procedimientos almacenados + `mysqli->prepare()` + `bind_param()`
- Formulario y procesamiento en el **mismo archivo** (patrón `POST` al principio)
- Reutilizar `.aero-window` para cualquier componente visual nuevo
- Ir en orden de bloques: no empezar Bloque 5 sin tener cerrados los Bloques 1 y 2
