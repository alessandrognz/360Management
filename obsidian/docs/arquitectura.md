---
tags: [docs, arquitectura]
---

# Arquitectura del Proyecto

## Convenciones generales

- Nombres en español: variables y funciones en castellano (`$Coneccion`, `INSERTAR_USUARIO`)
- La lógica de datos vive en clases dentro de `includes/db.php` (`loginAndRegister`, `CRUD_USER`, `TBL_TAREA`); cada método llama a un procedimiento almacenado vía `mysqli->prepare()` + `bind_param()`, sin SQL suelto en las páginas
- Formulario y procesamiento en el mismo archivo: `if ($_SERVER['REQUEST_METHOD'] === 'POST')` al principio
- Credenciales de BD en `includes/db_config.php`, fuera de git (`.gitignore`)

## Separación de páginas

| Tipo | Páginas | Descripción |
|------|---------|-------------|
| Pública | `index.php` | Login y registro por modal, sin sesión requerida |
| Privada | `session.php`, `admin.php`, `tasks.php`, `inbox.php`, `settings.php` | Requieren `includes/auth_check.php` |
| Acción | `includes/logout.php`, `includes/delete_user.php` | Solo lógica PHP, sin HTML |

## Includes

```
includes/
├─ db.php              Conexión mysqli + clases loginAndRegister, CRUD_USER, TBL_TAREA
├─ db_config.php       Credenciales locales (fuera de git)
├─ auth_check.php      Protección de sesión → redirect index.php
├─ nav.php             Sidebar + footer (controlado por $layout_part)
├─ logout.php          Destruye la sesión → redirect index.php
└─ delete_user.php     Borrado lógico de la cuenta propia → redirect index.php
```

## Assets

```
assets/
├─ css/     index.css (landing + modales) y session.css (panel privado)
├─ js/      index.js — apertura/cierre de los modales de login y registro
├─ icons/   ~220 iconos SVG/PNG (nav, acciones, logo, perfil)
├─ images/  background.png — usado en index.css
└─ img/     capturas y referencias de diseño, no usadas en código
```

## Sistema de diseño

Paleta primaria `#4a6741` (verde), fondo `#eef0ed`, tipografía de sistema. Layout flexbox. Login y registro en modales sobre `index.php` en vez de páginas separadas. Panel privado con sidebar (no nav superior).

## Flujo de datos

**Login** (`index.php?action=ini`)
```
loginAndRegister::INICIAR_SESION($email, $contrasena)
  └─ CALL VERIFICAR_EMAIL(email) + CALL VERIFICAR_CONTRASENA(email)
       └─ password_verify($contrasena, $hash)
            └─ true  → $_SESSION = {email, id_usuario, nombre, id_puesto} → redirect session.php
            └─ false → sin redirect, sin mensaje visual
```

**Registro** (`index.php?action=reg`)
```
loginAndRegister::INSERTAR_USUARIO($nombre, $email, $puesto, $contrasena)
  └─ CALL INSERTAR_USUARIO(...)
       └─ $_SESSION = {nombre, id_usuario, id_departamento, email} → redirect session.php
```

Nota: tras login la sesión guarda `id_puesto`; tras registro guarda `id_departamento`. No son el mismo dato — ver [[deuda-tecnica]].

## Referencias

- [[base-de-datos]] — esquema de BD y procedimientos
- [[deuda-tecnica]] — inconsistencias y riesgos activos
