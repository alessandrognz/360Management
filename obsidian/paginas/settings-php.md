---
tags: [pagina, privada, parcial]
archivo: settings.php
estado: parcial
tipo: privada
---

# settings.php — Configuración y Perfil

## Qué hace

Sección para que el usuario edite sus datos de perfil. Cambio de nombre implementado; cambio de contraseña pendiente.

## Estado

🟡 Parcial (2026-07-21). Tarjeta de perfil (avatar, nombre, email, puesto) + formulario "Editar perfil" funcional que llama a `CAMBIAR_NOMBRE_USUARIO()`. El formulario roto anterior (`action="includes/db.php"`, enlace a `db.php` inexistente) fue sustituido. Cambio de contraseña sigue pendiente — ver [[bloque-5-funcionalidades-futuras]], tarea 5.3.

## Funcionalidades previstas

- **Cambiar nombre**: ✅ formulario con el nombre actual precargado, envío por POST (`accion=cambiar_nombre`)
- **Cambiar contraseña**: pendiente. Tres campos (contraseña actual, nueva contraseña, confirmar nueva). Verificar actual con `password_verify()`, hashear nueva con `password_hash(BCRYPT)`.
- **Cambiar puesto** (TBD): si se decide permitir al usuario cambiar su propio puesto
- **Cerrar sesión**: ya cubierto por el enlace de logout en `includes/nav.php`, no duplicado aquí

## Requisitos de BD pendientes

- `CAMBIAR_NOMBRE_USUARIO(id_usuario, nombre)` y `MOSTRAR_USUARIO(id_usuario)` ✅ ya existían en `db.sql`/`db.php`, reutilizados sin cambios
- Nuevo procedimiento: `CAMBIAR_CONTRASENA(id_usuario, nueva_contrasena)` — la verificación de la contraseña actual se hace en PHP con `password_verify()`, no en el procedimiento

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
