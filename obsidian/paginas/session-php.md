---
tags: [pagina, privada, funcional]
archivo: session.php
estado: funcional-basico
tipo: privada
---

# session.php — Panel Privado (Home Interno)

## Qué hace

Home del panel interno. Página principal que ve el usuario tras iniciar sesión. Muestra bienvenida personalizada con el nombre del usuario y accesos directos a las secciones principales.

## Estado

✅ Funcional básico (2026-07-19). Auth protegida, bienvenida personalizada y nav operativo.

### Tareas completadas

**Bloque 1 — Seguridad**
| Tarea | Estado |
|-------|--------|
| `require 'includes/auth_check.php'` al inicio | ✅ Hecho |
| `$_SESSION['nombre']`, `$_SESSION['id_puesto']` disponibles | ✅ Hecho |

**Bloque 3 — Contenido**
| Tarea | Estado |
|-------|--------|
| Bienvenida personalizada "Hola, {nombre}" | ✅ Hecho |
| Accesos directos a secciones (Tasks, Inbox, Settings) | ✅ Hecho |

### Pendientes

**Bloque 3 — Contenido**
| Tarea | Estado |
|-------|--------|
| Tarjetas de resumen (tareas pendientes, mensajes sin leer, etc.) | ⬜ Pendiente (tarea 3.2) |
| `OBTENER_PUESTO_DEPARTAMENTO` para mostrar dpto | ⬜ Pendiente (tarea 3.4) |

**Bloque 4 — Mensajes visuales**
| Tarea | Estado |
|-------|--------|
| Sistema de mensajes de error/éxito integrado | ⬜ Pendiente |

## Flujo actual

```
Acceso sin sesión → auth_check.php → redirect ini.php
Login exitoso en ini.php → redirect aquí → muestra panel personalizado
```

## Archivos relacionados

| Archivo | Rol |
|---------|-----|
| `includes/auth_check.php` | ✅ Protección de acceso activa |
| `includes/nav.php` | Nav extraído (Bloque 2) |
| `css/session.css` | Estilos del panel (nav, layout) |

## Notas técnicas

- Las tarjetas de resumen pueden usar datos placeholder hasta que Tasks/Inbox tengan CRUD real.
- `htmlspecialchars()` aplicado en la salida de datos de sesión.

## Referencias

- [[ini-php]] — origen (redirect tras login)
- [[logout-php]] — enlazado desde el nav
- [[tasks-php]] — [[inbox-php]] — [[settings-php]] — páginas del nav
- [[bloque-1-sesion-seguridad]] — prerequisito crítico
- [[bloque-2-estructura-reutilizable]] — prerequisito
- [[bloque-3-panel-session]] — tareas de contenido
