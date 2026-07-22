---
tags: [pagina, privada, completa]
archivo: settings.php
estado: completo
tipo: privada
---

# settings.php — Ajustes de Cuenta

## Qué hace

Tres acciones sobre la cuenta del usuario, cada una en su propio formulario:

- Cambiar nombre — `CRUD_USER::CAMBIAR_NOMBRE_USUARIO()`
- Cambiar contraseña — `CRUD_USER::CAMBIAR_CONTRASENA()`: verifica la actual con `password_verify()` contra `VERIFICAR_CONTRASENA`, hashea la nueva con `password_hash(BCRYPT)`
- Eliminar cuenta — POST a `includes/delete_user.php`, borrado lógico + destrucción de sesión

Mensajes de éxito/error por sección (`$mensaje`, `$error`, `$seccion_mensaje`), sin recargar toda la página con un mensaje genérico.

## Estado

Completo.

## Archivos relacionados

- `includes/db.php` — clase `CRUD_USER`
- `includes/delete_user.php` — borrado de cuenta
- `includes/auth_check.php` — protección de acceso
- `assets/css/session.css` — estilos de las tarjetas de ajustes

## Referencias

- [[session-php]] — enlaza desde la sidebar
