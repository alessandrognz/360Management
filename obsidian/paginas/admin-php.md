---
tags: [pagina, privada, completa]
archivo: admin.php
estado: completo
tipo: privada
---

# admin.php — Administración de Usuarios

## Qué hace

Panel de administración: lista todos los usuarios (`CRUD_USER::MOSTRAR_USUARIOS()`) y permite eliminarlos (borrado lógico, `ELIMINAR_USUARIO_LOGICO`). Un usuario no puede eliminarse a sí mismo — el botón se deshabilita para su propia fila y el servidor también lo bloquea.

## Estado

Completo (bloque 6). Sin sistema de roles todavía — ver riesgo abajo.

## Archivos relacionados

- `includes/db.php` — clase `CRUD_USER`
- `includes/auth_check.php` — protección de acceso
- `assets/css/session.css` — estilos de la lista de usuarios

## Riesgo de seguridad

`admin.php` solo comprueba que haya sesión iniciada (`auth_check.php`), no que el usuario sea administrador. Cualquier usuario autenticado puede entrar y eliminar cuentas de otros. Pendiente de [[bloque-5-funcionalidades-futuras]] tarea 5.4 (sistema de roles). Ver [[deuda-tecnica]].

También tiene un `<title>` copiado de `inbox.php` ("Bandeja de entrada") — error menor de copiar/pegar.

## Referencias

- [[session-php]] — enlaza desde la sidebar
- [[bloque-5-funcionalidades-futuras]] — tarea 5.4, roles y control de acceso
