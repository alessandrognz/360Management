---
tags: [pagina, privada, placeholder]
archivo: inbox.php
estado: placeholder
tipo: privada
---

# inbox.php — Mensajería Interna

## Qué hará

Sistema de mensajes entre usuarios de la empresa. Sin funcionalidad implementada aún.

## Estado

🔲 Creada como placeholder (2026-07-19). Tiene nav, auth y estructura base pero sin funcionalidad real. Ver [[bloque-5-funcionalidades-futuras]], tarea 5.2 para la mensajería completa.

## Funcionalidades previstas (sin definir aún en detalle)

- Ver mensajes recibidos (bandeja de entrada)
- Leer un mensaje
- Redactar y enviar un mensaje a otro usuario
- Indicador de mensajes no leídos (se usará también en la tarjeta del panel en [[session-php]])
- Posiblemente: conversaciones (hilo de mensajes entre dos usuarios)

## Requisitos de BD pendientes

- Nueva(s) tabla(s): al menos `mensajes` (o `conversaciones` + `mensajes`)
- Campos probables: `id_mensaje`, `id_emisor` (FK usuarios), `id_receptor` (FK usuarios), `asunto`, `cuerpo`, `fecha`, `leido BIT`
- Nuevos procedimientos: `ENVIAR_MENSAJE`, `LISTAR_INBOX`, `MARCAR_LEIDO`, `CONTAR_NO_LEIDOS`
- Añadir en: `db.sql` y `procedure.sql`

## Requisitos de includes

- `require 'includes/auth_check.php'` al inicio (Bloque 1)
- `require 'includes/nav.php'` (Bloque 2)
- `require 'includes/footer_privado.php'` (Bloque 2)

## Notas

> [!warning] Prerequisitos
> No crear este archivo hasta tener cerrados los Bloques 1 y 2.

> [!info] Contador de no leídos
> El procedimiento `CONTAR_NO_LEIDOS` se usará también en la tarjeta del panel ([[session-php]], tarea 3.2) para mostrar mensajes sin leer. Tenerlo en cuenta al diseñar el procedimiento.

## Referencias

- [[session-php]] — enlaza a inbox desde el nav + tarjeta de resumen
- [[bloque-5-funcionalidades-futuras]] — tarea 5.2
- [[bloque-1-sesion-seguridad]] — prerequisito (auth)
- [[bloque-2-estructura-reutilizable]] — prerequisito (nav/footer)
