# 360Management

Panel web de gestión de empresa — autenticación, administración de usuarios y gestión de tareas. PHP procedural + MySQL puro, sin frameworks.

> Proyecto de portfolio orientado a seguridad, buenas prácticas y arquitectura limpia.

---

## Stack

**Backend** PHP procedural · `mysqli` · procedimientos almacenados  
**Frontend** HTML · CSS vanilla · JS vanilla · diseño responsive  
**Base de datos** MySQL (UTF8MB4)  
**Entorno** XAMPP — Apache + MySQL en Windows

---

## Funcionalidades

| Módulo | Estado |
|--------|--------|
| Registro e inicio de sesión (modales) | ✅ |
| Panel privado con bienvenida y widget "Tus tareas" | ✅ |
| Perfil de usuario en sidebar | ✅ |
| Ajustes — cambiar nombre, cambiar contraseña y eliminar cuenta | ✅ |
| Panel de administración — listar y eliminar usuarios | ✅ |
| Diseño responsive móvil — sidebar como *drawer* con menú hamburguesa (≤768px) | ✅ |
| Gestión de tareas — crear y listar (`tasks.php`) | 🔄 En desarrollo |
| Bandeja de entrada (`inbox.php`) | 🔄 En desarrollo |
| Sistema de roles admin / usuario | ⏳ Pendiente |

---

## Puesta en marcha

**Requisitos:** [XAMPP](https://www.apachefriends.org/) con Apache y MySQL activos.

**1. Clona el repositorio en `htdocs`**

```bash
git clone <repo-url> C:/xampp/htdocs/360Management
```

**2. Importa la base de datos**

Abre **phpMyAdmin** → pestaña *Importar* → selecciona `db.sql`.

> ⚠️ **Nota:** `db.sql` contiene el esquema de usuarios y sus procedimientos. Las tablas
> y procedimientos del módulo de **tareas** (`tareas`, `tareas_usuarios`,
> `tareas_departamento`, `INSERTAR_TAREA`, `SELECT_TAREAS_*`, etc.) todavía no están
> volcados en `db.sql` y solo existen en la base de datos de desarrollo.

**3. Crea el archivo de credenciales**

```php
// includes/db_config.php  (no se sube al repo)
<?php
return [
    'ip'         => '127.0.0.1',
    'puerto'     => 3306,
    'usuario'    => 'root',
    'contrasenia'=> '',
    'db_nombre'  => 'users',
];
```

**4. Accede en el navegador**

```
http://localhost/360Management/
```

---

## Estructura

```
360Management/
├─ index.php               Landing (login + registro en modales)
├─ session.php             Panel privado + widget "Tus tareas"
├─ admin.php               Administración de usuarios
├─ settings.php            Ajustes de cuenta (nombre, contraseña, eliminar)
├─ tasks.php               Gestión de tareas — crear y listar  (WIP)
├─ inbox.php               Bandeja de entrada (WIP)
├─ includes/
│   ├─ db.php              Conexión + carga de las clases de procedimientos
│   ├─ db_config.php       Credenciales locales  ← en .gitignore
│   ├─ auth_check.php      Guard de sesión (redirige a login si no autenticado)
│   ├─ delete_user.php     Eliminar cuenta propia
│   ├─ logout.php          Cierre de sesión
│   ├─ nav.php             Sidebar + footer compartidos (drawer en móvil)
│   └─ procedures/         Clases wrapper de los procedimientos almacenados
│       ├─ login.php            loginAndRegister — alta e inicio de sesión
│       ├─ tbl_usuarios.php     CRUD_USER — usuarios y cuenta
│       └─ tbl_tarea.php        CRUD_TAREAS — tareas y departamentos
└─ assets/
    ├─ css/                index.css · session.css (con bloques responsive ≤768px)
    ├─ js/                 index.js (modales) · tasks.js (formulario de tareas)
    └─ icons/ images/
```

<details>
<summary>Procedimientos almacenados</summary>

**Usuarios y sesión**

| Procedimiento | Descripción |
|---------------|-------------|
| `INSERTAR_USUARIO(_id_puesto, _nombre, _email, _contrasena)` | Alta — devuelve `id_usuario` e `id_departamento` |
| `VERIFICAR_EMAIL(_email)` | Comprueba si el email ya existe |
| `VERIFICAR_CONTRASENA(_email)` | Devuelve hash + datos para el login |
| `MOSTRAR_USUARIO(_id_usuario)` | Datos de un usuario por id |
| `MOSTRAR_USUARIOS()` | Todos los usuarios no eliminados |
| `ELIMINAR_USUARIO_LOGICO(_id_usuario)` | Borrado lógico (`eliminado = 1`) |
| `CAMBIAR_NOMBRE_USUARIO(_id_usuario, _nombre)` | Actualiza el nombre |
| `CAMBIAR_CONTRASENA(_id_usuario, _contrasena)` | Actualiza la contraseña (hash) |
| `CAMBIAR_EMAIL(_id_usuario, _email)` | Actualiza el email |

**Tareas**

| Procedimiento | Descripción |
|---------------|-------------|
| `INSERTAR_TAREA(_id_usuario_creador, _titulo, _descripcion_tarea, _fecha_limite, _es_general)` | Crea la tarea — devuelve el id insertado |
| `DESTINAR_A_USAURIO(_id_tarea, _id_usuario)` | Asigna la tarea a una persona |
| `DESTINAR_A_DEPARTAMENTO(_id_tarea, _id_departamento)` | Asigna la tarea a un departamento |
| `SELECT_TAREAS_PERSONALES(_id_usuario)` | Tareas vigentes asignadas a un usuario |
| `SELECT_TAREAS_GENERALES(_id_puesto)` | Tareas vigentes del departamento del puesto |
| `LISTAR_DEPARTAMENTOS()` | Lista de departamentos |

</details>

---

## Hoja de ruta

- [x] Bloque 1 — Autenticación y sesión segura
- [x] Bloque 2 — Estructura reutilizable (nav/footer como includes)
- [x] Bloque 6 — Panel de administración de usuarios
- [x] Diseño responsive móvil (drawer + menú hamburguesa)
- [ ] Bloque 3 — Dashboard con datos reales
- [ ] Bloque 4 — Validación y mensajes de error en pantalla
- [ ] Bloque 5 — Tasks e Inbox completos
- [ ] Bloque 7 — CRUD de tareas — *crear y listar hechos; editar, completar y eliminar pendientes*
- [ ] Bloque 8 — Sistema de roles

---

## Convenciones

- Nomenclatura en **español** — `$Coneccion`, `INSERTAR_USUARIO`, `id_puesto`
- Todo SQL a través de procedimientos almacenados + `prepare()` + `bind_param()`
- Clases wrapper por tabla en `includes/procedures/` (`loginAndRegister`, `CRUD_USER`, `CRUD_TAREAS`)
- Formulario y procesamiento POST en el mismo archivo
- Páginas privadas incluyen `auth_check.php` como primera línea
- Cambios responsive encapsulados en `@media (max-width: 768px)` — la versión de escritorio queda intacta
- Credenciales de BD nunca en el repo — usar `includes/db_config.php`
