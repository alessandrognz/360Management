---
tags: [tareas, bloque-3]
estado: pendiente
bloque: 3
---

# Bloque 3 — Contenido del Panel (`session.php`)

> Bienvenida y accesos directos ya implementados (2026-07-19). Quedan las tarjetas de resumen y el procedimiento de puesto/departamento.

## Tareas

- [ ] **3.2 — Tarjetas de resumen**
  Crear 3–4 tarjetas con el componente de tarjeta del diseño actual:
  - Tareas pendientes (placeholder hasta que Tasks tenga CRUD)
  - Mensajes sin leer en Inbox (placeholder)
  - Avisos recientes (placeholder)
  - Último acceso
  - Archivo afectado: `session.php`

- [ ] **3.4 — Nuevo procedimiento `OBTENER_PUESTO_DEPARTAMENTO`**
  JOIN entre `puesto` y `departamento` para mostrar nombre del puesto y departamento en la bienvenida.
  - Archivos afectados: `db.sql` y `includes/db.php`

> [!tip] Sobre los datos de ejemplo
> Las tarjetas pueden mostrar `0` o datos hardcodeados hasta que Tasks/Inbox tengan CRUD real. La estructura visual es lo importante ahora.

## Referencias

- [[bloque-2-estructura-reutilizable]] — prerequisito
- [[bloque-4-validacion-mensajes]] — siguiente bloque
- [[session-php]] — página afectada
