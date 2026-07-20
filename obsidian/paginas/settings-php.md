---
tags: [pagina, privada, placeholder]
archivo: settings.php
estado: placeholder
tipo: privada
---

# settings.php — Configuración y Perfil

## Qué hará

Sección para que el usuario edite sus datos de perfil y cambie su contraseña.

## Estado

🔲 Creada como placeholder (2026-07-19). Tiene nav, auth y estructura base pero sin formularios de edición. Ver [[bloque-5-funcionalidades-futuras]], tarea 5.3 para la funcionalidad completa.

## Funcionalidades previstas

- **Cambiar nombre**: formulario con el nombre actual precargado, envío por POST
- **Cambiar contraseña**: tres campos (contraseña actual, nueva contraseña, confirmar nueva). Verificar actual con `password_verify()`, hashear nueva con `password_hash(BCRYPT)`.
- **Cambiar puesto** (TBD): si se decide permitir al usuario cambiar su propio puesto
- **Cerrar sesión**: botón de logout (enlaza a `logout.php`)

## Requisitos de BD pendientes

- Nuevo procedimiento: `ACTUALIZAR_NOMBRE(id_usuario, nombre)` — devuelve `row_count()` como respuesta
- Nuevo procedimiento: `CAMBIAR_CONTRASENA(id_usuario, nueva_contrasena)` — la verificación de la contraseña actual se hace en PHP con `password_verify()`, no en el procedimiento
- Añadir en: `db.sql` y `procedure.sql`

## Requisitos de includes

- `require 'includes/auth_check.php'` al inicio (Bloque 1)
- `require 'includes/nav.php'` (Bloque 2)
- `require 'includes/footer_privado.php'` (Bloque 2)

## Notas de seguridad

> [!warning] Cambio de contraseña
> Al cambiar la contraseña:
> 1. Verificar la contraseña actual con `password_verify()` antes de actualizar.
> 2. Hashear la nueva con `password_hash($nueva, PASSWORD_BCRYPT)`.
> 3. Guardar el hash, nunca la contraseña en texto plano.
> 4. Opcionalmente: destruir la sesión actual y pedir al usuario que vuelva a iniciar sesión.

> [!warning] Prerequisitos
> No crear este archivo hasta tener cerrados los Bloques 1 y 2.

## Patrón de implementación sugerido

- Dos secciones/formularios en la misma página: "Editar perfil" y "Cambiar contraseña"
- Cada formulario tiene su propio `action` o se distinguen por un campo hidden `accion=perfil|contrasena`
- Mensajes de éxito/error integrados visualmente (Bloque 4)

## Referencias

- [[session-php]] — enlaza a settings desde el nav
- [[logout-php]] — botón de logout en esta página también
- [[bloque-5-funcionalidades-futuras]] — tarea 5.3
- [[bloque-1-sesion-seguridad]] — prerequisito (auth + datos de sesión para precarga)
- [[bloque-2-estructura-reutilizable]] — prerequisito (nav/footer)
- [[bloque-4-validacion-mensajes]] — patrones de validación y mensajes a aplicar aquí
