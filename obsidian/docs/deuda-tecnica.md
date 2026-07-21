---
tags: [docs, deuda-tecnica, riesgos]
---

# Deuda Técnica y Riesgos Conocidos

Registro de problemas conocidos, compromisos técnicos y riesgos activos. Actualizar cuando se resuelvan.

## Riesgos activos

### 🟡 MEDIO — Nav sin link activo dinámico

`includes/nav.php` tiene la clase `active` fija en uno de los links. No se actualiza automáticamente al cambiar de página.

- **Solución**: tarea 2.2 del [[bloque-2-estructura-reutilizable]] — comparar `basename($_SERVER['PHP_SELF'])` con cada `href`
- **Archivos afectados**: `includes/nav.php`

---

### 🟡 MEDIO — Tarjetas de resumen en `session.php` vacías

El panel home no tiene tarjetas de resumen (tareas pendientes, mensajes sin leer, avisos). Sin datos reales hasta que Tasks/Inbox tengan CRUD.

- **Solución**: tarea 3.2 del [[bloque-3-panel-session]] — crear tarjetas con datos placeholder primero
- **Archivos afectados**: `session.php`

---

### 🟡 MEDIO — Sin validación visual de formularios

Los mensajes de error/éxito en `ini.php` y `registr.php` son `echo` o `alert()` sin estilo coherente.

- **Solución**: tareas 4.1–4.4 del [[bloque-4-validacion-mensajes]]
- **Archivos afectados**: `ini.php`, `registr.php`, `assets/css/index.css`

---

### 🟡 MEDIO — Sin validación cliente (frontend)

No hay validación en el lado del cliente en los formularios de login ni registro. Solo se valida en servidor.

- **Solución**: añadir validación JS básica antes del submit en `ini.php` y `registr.php`
- **Prioridad**: baja (la validación servidor es suficiente para seguridad; cliente solo mejora UX)

---

### 🟢 MENOR — `procedure.sql` vacío y confuso

El archivo `procedure.sql` existe pero está vacío. Los procedimientos reales están en `db.sql`. Puede llevar a confusión sobre dónde añadir nuevos procedimientos.

- **Solución**: documentar en [[base-de-datos]] qué archivo usar para nuevos procedimientos, o eliminar `procedure.sql`

---

### 🟢 MENOR — `session.php` sin tarjeta de departamento

La bienvenida muestra el nombre del usuario pero no el puesto/departamento (requiere JOIN en BD).

- **Solución**: tarea 3.4 del [[bloque-3-panel-session]] — crear procedimiento `OBTENER_PUESTO_DEPARTAMENTO`

---

## Resueltos (histórico)

| Riesgo | Resuelto el | Cómo |
|--------|-------------|------|
| 🔴 `session.php` sin protección de acceso | 2026-07-19 | `auth_check.php` implementado (tarea 1.3/1.4) |
| 🔴 Fallback contraseña texto plano en `db.php` | 2026-07-19 | Eliminado (tarea 1.5) |
| 🟡 Sin logout | 2026-07-19 | `logout.php` creado (tarea 1.6) |
| 🟡 `INICIAR_SESION` devuelve datos insuficientes | 2026-07-19 | Procedimiento ampliado a 5 campos (tarea 1.1/1.2) |
| 🟡 Nav duplicado en cada página privada | 2026-07-19 | Extraído a `includes/nav.php` (tarea 2.1) |

## Referencias

- [[bloque-2-estructura-reutilizable]] — tarea 2.2 (nav activo dinámico)
- [[bloque-3-panel-session]] — tarjetas resumen y dpto
- [[bloque-4-validacion-mensajes]] — mensajes visuales
