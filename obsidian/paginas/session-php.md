---
tags: [pagina, privada, funcional]
archivo: session.php
estado: funcional-basico
tipo: privada
---

# session.php — Panel Privado (Home Interno)

## Qué hace

Home del panel. Protegida por `auth_check.php`. Muestra el logo y "Bienvenido, {nombre}". Nada más por ahora — la navegación la da la sidebar (`includes/nav.php`), no la propia página.

## Estado

Funcional básico. Falta contenido real — ver [[bloque-3-panel-session]]:

- Tarjetas de resumen (tareas pendientes, mensajes sin leer) — no existen aún
- Mostrar puesto/departamento del usuario en la bienvenida — no existe aún, requiere un procedimiento con JOIN a `puesto`

## Archivos relacionados

- `includes/auth_check.php` — protección de acceso
- `includes/nav.php` — sidebar + footer (incluye el logout)
- `assets/css/session.css` — estilos del panel

## Referencias

- [[index]] — origen (redirect tras login/registro)
- [[admin-php]] — [[tasks-php]] — [[inbox-php]] — [[settings-php]] — resto de páginas de la sidebar
- [[bloque-3-panel-session]] — tareas de contenido pendientes
