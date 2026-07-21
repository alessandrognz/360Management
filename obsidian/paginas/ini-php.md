---
tags: [pagina, publica, parcial]
archivo: ini.php
estado: funcional-con-pendientes
tipo: publica
---

# ini.php — Login

## Qué hace

Formulario de inicio de sesión + procesamiento en el mismo archivo. Recoge `email` y `contrasena` por POST, llama a la función PHP `INICIAR_SESION()` de `includes/db.php`, verifica la contraseña con `password_verify()` y, si es correcta, inicia la sesión PHP y redirige al panel.

## Flujo

```
GET  → muestra el formulario HTML
POST → valida → llama INICIAR_SESION() → password_verify() → $_SESSION → redirect session.php
                                                            └→ error → muestra mensaje
```

## Estado

⚠️ Funcional pero con pendientes del Bloque 1 y 4.

| Tarea | Bloque | Estado |
|-------|--------|--------|
| Guardar `id_usuario`, `nombre`, `id_puesto` en `$_SESSION` | [[bloque-1-sesion-seguridad]] | Pendiente |
| Eliminar fallback contraseña texto plano | [[bloque-1-sesion-seguridad]] | Pendiente |
| Validación robusta (email, campos vacíos) | [[bloque-4-validacion-mensajes]] | Pendiente |
| Sustituir `echo`/`alert()` por mensajes visuales | [[bloque-4-validacion-mensajes]] | Pendiente |

## Archivos relacionados

| Archivo | Rol |
|---------|-----|
| `includes/db.php` | Función `INICIAR_SESION()` que llama al procedimiento almacenado |
| `assets/css/index.css` | Estilos del formulario |
| `session.php` | Destino tras login exitoso |

## Notas técnicas

- Patrón: comprobación `$_SERVER['REQUEST_METHOD'] === 'POST'` al inicio del archivo, formulario HTML debajo.
- La función `INICIAR_SESION()` en `db.php` actualmente guarda solo `email` en `$_SESSION`. Necesita ampliar a `id_usuario`, `nombre`, `id_puesto` (tarea 1.2).
- ⚠️ El fallback `|| $contrasena === $hash` en `db.php` es un riesgo de seguridad — ver tarea 1.5.

## Procedimiento almacenado asociado

`INICIAR_SESION(_email, _contrasena)` — definido en `db.sql`
- Actualmente devuelve: `email`, `contrasena`
- Debe devolver: `id_usuario`, `nombre`, `email`, `contrasena`, `id_puesto` (tarea 1.1)

## Referencias

- [[index]] — página anterior (botón "Iniciar Sesión")
- [[session-php]] — destino tras login exitoso
- [[logout-php]] — cierre de sesión
- [[bloque-1-sesion-seguridad]] — tareas que afectan a este archivo
- [[bloque-4-validacion-mensajes]] — tareas de validación y mensajes
