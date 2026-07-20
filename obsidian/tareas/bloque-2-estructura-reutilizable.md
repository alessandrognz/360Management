---
tags: [tareas, bloque-2]
estado: casi-completado
bloque: 2
---

# Bloque 2 — Estructura Reutilizable

> Nav y footers extraídos a `includes/` el 2026-07-19. Solo queda marcar el link activo dinámicamente.

## Tareas

- [x] **2.2 — Marcar link activo del nav dinámicamente**
  Sustituir la clase `active` fija por lógica PHP que compara `basename($_SERVER['PHP_SELF'])` contra cada `href` del nav.
  - Archivo afectado: `includes/nav.php`
  - Ejemplo: si `$pagina_actual === 'tasks.php'`, el link a Tasks recibe `class="active"`

## Referencias

- [[bloque-1-sesion-seguridad]] — prerequisito (cerrado)
- [[bloque-3-panel-session]] — siguiente bloque
