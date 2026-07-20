---
tags: [pagina, privada, completa]
archivo: logout.php
estado: completa
tipo: privada
fecha-completada: 2026-07-19
---

# logout.php — Cierre de Sesión

## Qué hace

Página que destruye la sesión activa del usuario y redirige a la página de bienvenida pública. No tiene HTML propio — es solo lógica PHP que termina con una redirección.

## Estado

✅ Implementada. Tarea 1.6 del [[bloque-1-sesion-seguridad]] cerrada el 2026-07-19.

## Implementación prevista

```php
<?php
session_start();
session_destroy();
header('Location: index.html');
exit;
```

## Enlace desde el nav

Una vez creado `logout.php`, añadir el enlace en el nav. Si el nav ya está extraído a `includes/nav.php` (Bloque 2), modificarlo ahí. Si no, modificarlo directamente en `session.php`.

## Notas de seguridad

- Usar `session_destroy()` (destruye todos los datos de sesión) y no solo `unset($_SESSION)`.
- El `exit` tras `header('Location: ...)` es obligatorio para que PHP no siga ejecutando código tras el redirect.
- Considerar añadir también `setcookie(session_name(), '', time()-42000, '/')` para eliminar la cookie de sesión en el cliente, no solo los datos en servidor.

## Archivos relacionados

| Archivo | Rol |
|---------|-----|
| `includes/nav.php` o `session.php` | Donde se enlaza este archivo |
| `index.html` | Destino tras el logout |

## Referencias

- [[session-php]] — página que enlaza el logout
- [[ini-php]] — alternativa de destino tras logout (si se prefiere ir a login en vez de bienvenida)
- [[bloque-1-sesion-seguridad]] — tarea 1.6
