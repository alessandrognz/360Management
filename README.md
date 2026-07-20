# 360Management

Aplicación web de gestión de usuarios/empresa construida con PHP procedural y MySQL. Proyecto de aprendizaje/portfolio orientado a seguridad, buenas prácticas y arquitectura escalable.

## Stack

| Capa | Tecnología |
|--------------------------------------------------------|
| Frontend | HTML + CSS puro (sistema de diseño moderno) |
| Backend | PHP procedural — extensión `mysqli` |
| Base de datos | MySQL con procedimientos almacenados |
| Entorno local | XAMPP en Windows (Apache + MySQL) |

## Estructura del proyecto

```
360Management/
├─ index.html              Bienvenida pública (logo + botones)
├─ ini.php                 Login — formulario + procesamiento POST
├─ registr.php             Registro — formulario + procesamiento POST
├─ session.php             Panel privado (home interno)
├─ tasks.php               Gestión de tareas (esqueleto)
├─ inbox.php               Bandeja de entrada (esqueleto)
├─ settings.php            Ajustes de usuario (esqueleto)
├─ includes/
│   ├─ db.php              Conexión + wrappers de procedimientos almacenados
│   ├─ auth_check.php      Comprobación de sesión activa (protege páginas privadas)
│   ├─ logout.php          Destrucción de sesión y redirección a login
│   └─ nav.php             Sidebar + footer compartido
├─ css/
│   ├─ index.css           Estilos públicos (login, registro, landing)
│   └─ session.css         Estilos del área autenticada
├─ Icons/                  Biblioteca de iconos SVG/PNG (160+ variantes)
├─ img/                    Imágenes de la landing
└─ db.sql                  Schema completo de la base de datos con datos iniciales
```

## Base de datos

Base de datos `users` (UTF8MB4), 5 tablas con borrado lógico (`eliminado BIT DEFAULT(0)`):

| Tabla | Descripción |
|-------|-------------|
| `departamento` | 7 departamentos de la empresa |
| `puesto` | 20 puestos con descripción (FK a `departamento`) |
| `usuarios` | id, puesto, nombre, email único, contraseña, fecha_registro, eliminado |
| `tareas` | Tareas con título (25 c.), descripción (250 c.), fechas y asignación |
| `tareas_usuarios` | Relación N:M tareas ↔ usuarios con fecha de asignación y completado |

### Procedimientos almacenados

| Procedimiento | Descripción |
|---------------|-------------|
| `INSERTAR_USUARIO(id_puesto, nombre, email, contrasena)` | Alta de usuario — devuelve `id_usuario` + `id_departamento` |
| `VERIFICAR_EMAIL(email)` | Comprueba si el email ya existe |
| `VERIFICAR_CONTRASENA(email)` | Devuelve el hash + datos del usuario para el login |

## Estado actual

- ✅ Registro funcional con `password_hash()` (BCRYPT)
- ✅ Login funcional con `password_verify()`
- ✅ Base de datos con datos iniciales (departamentos y puestos)
- ✅ Protección de sesión en todas las páginas privadas (`auth_check.php`)
- ✅ Logout con destrucción completa de sesión
- ✅ Sidebar y footer extraídos a `includes/nav.php`
- ✅ Sistema de diseño CSS consistente (paleta, tipografía, layout)
- ⚠️ `tasks.php`, `inbox.php` y `settings.php` en fase de esqueleto
- ⚠️ Credenciales de BD hardcodeadas en `db.php` (sin `.env`)
- ⚠️ Sin mensajes de validación en pantalla (solo `alert()`)

## Hoja de ruta

1. **Bloque 1** — ~~Sesión y seguridad~~ ✅ Completado
2. **Bloque 2** — ~~Estructura reutilizable (nav/footer como includes)~~ ✅ Completado
3. **Bloque 3** — Contenido del panel (dashboard con datos reales)
4. **Bloque 4** — Validación y mensajes de usuario en pantalla
5. **Bloque 5** — Tasks, Inbox, Settings (implementación completa)

## Convenciones del proyecto

- PHP procedural en **español** (`$Coneccion`, `INSERTAR_USUARIO`)
- SQL solo vía procedimientos almacenados + `mysqli->prepare()` + `bind_param()`
- Formulario y procesamiento en el **mismo archivo** (patrón `POST` al principio del archivo)
- Todas las páginas privadas incluyen `auth_check.php` como primera línea
- Sidebar y footer se incluyen via `nav.php` (no duplicar HTML)
- Ir en orden de bloques: no empezar Bloque 5 sin tener cerrados los anteriores
