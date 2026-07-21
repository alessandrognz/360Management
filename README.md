# 360Management

Panel web de gestión de empresa — autenticación, administración de usuarios y gestión de tareas. PHP procedural + MySQL puro, sin frameworks.

> Proyecto de portfolio orientado a seguridad, buenas prácticas y arquitectura limpia.

---

## Stack

**Backend** PHP procedural · `mysqli` · procedimientos almacenados  
**Frontend** HTML · CSS vanilla · JS vanilla  
**Base de datos** MySQL (UTF8MB4)  
**Entorno** XAMPP — Apache + MySQL en Windows

---

## Funcionalidades

| Módulo | Estado |
|--------|--------|
| Registro e inicio de sesión (modales) | ✅ |
| Panel privado con bienvenida al usuario | ✅ |
| Perfil de usuario en sidebar | ✅ |
| Ajustes — cambiar nombre y eliminar cuenta | ✅ |
| Panel de administración — listar y eliminar usuarios | ✅ |
| Gestión de tareas (`tasks.php`) | 🔄 En desarrollo |
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
├─ session.php             Panel privado
├─ admin.php               Administración de usuarios
├─ settings.php            Ajustes de cuenta
├─ tasks.php               Gestión de tareas  (WIP)
├─ inbox.php               Bandeja de entrada (WIP)
├─ includes/
│   ├─ db.php              Conexión + wrappers de procedimientos almacenados
│   ├─ db_config.php       Credenciales locales  ← en .gitignore
│   ├─ auth_check.php      Guard de sesión (redirige a login si no autenticado)
│   ├─ delete_user.php     Eliminar cuenta propia
│   ├─ logout.php          Cierre de sesión
│   └─ nav.php             Sidebar compartido
└─ assets/
    ├─ css/                index.css · session.css
    ├─ js/                 index.js (apertura de modales)
    └─ icons/ images/
```

<details>
<summary>Procedimientos almacenados</summary>

| Procedimiento | Descripción |
|---------------|-------------|
| `INSERTAR_USUARIO(id_puesto, nombre, email, contrasena)` | Alta — devuelve `id_usuario` e `id_departamento` |
| `VERIFICAR_EMAIL(email)` | Comprueba si el email ya existe |
| `VERIFICAR_CONTRASENA(email)` | Devuelve hash + datos para el login |
| `MOSTRAR_USUARIO(id_usuario)` | Datos de un usuario por id |
| `MOSTRAR_USUARIOS()` | Todos los usuarios no eliminados |
| `ELIMINAR_USUARIO_LOGICO(id_usuario)` | Borrado lógico (`eliminado = 1`) |
| `CAMBIAR_NOMBRE_USUARIO(id_usuario, nombre)` | Actualiza el nombre |

</details>

---

## Hoja de ruta

- [x] Bloque 1 — Autenticación y sesión segura
- [x] Bloque 2 — Estructura reutilizable (nav/footer como includes)
- [x] Bloque 6 — Panel de administración de usuarios
- [ ] Bloque 3 — Dashboard con datos reales
- [ ] Bloque 4 — Validación y mensajes de error en pantalla
- [ ] Bloque 5 — Tasks e Inbox completos
- [ ] Bloque 7 — CRUD de tareas
- [ ] Bloque 8 — Sistema de roles

---

## Convenciones

- Nomenclatura en **español** — `$Coneccion`, `INSERTAR_USUARIO`, `id_puesto`
- Todo SQL a través de procedimientos almacenados + `prepare()` + `bind_param()`
- Formulario y procesamiento POST en el mismo archivo
- Páginas privadas incluyen `auth_check.php` como primera línea
- Credenciales de BD nunca en el repo — usar `includes/db_config.php`
