---
tags: [pagina, publica, parcial]
archivo: registr.php
estado: funcional-con-pendientes
tipo: publica
---

# registr.php — Registro

## Qué hace

Formulario de registro de usuario + procesamiento en el mismo archivo. Recoge `id_puesto`, `nombre`, `email`, `contrasena` y `confirmar_contrasena` por POST. Valida que las contraseñas coincidan, hashea la contraseña con `password_hash()` (BCRYPT) y llama a `INSERTAR_USUARIO()` en `includes/db.php`.

## Flujo

```
GET  → muestra el formulario HTML (con select de puestos cargado desde BD)
POST → valida contraseñas → password_hash(BCRYPT) → INSERTAR_USUARIO() → redirect / mensaje
```

## Estado

⚠️ Funcional pero con pendientes del Bloque 4.

| Tarea | Bloque | Estado |
|-------|--------|--------|
| Validación email con `filter_var` | [[bloque-4-validacion-mensajes]] | Pendiente |
| Validación longitud mínima contraseña | [[bloque-4-validacion-mensajes]] | Pendiente |
| Validación campos obligatorios vacíos | [[bloque-4-validacion-mensajes]] | Pendiente |
| Validación `id_puesto` existe en BD | [[bloque-4-validacion-mensajes]] | Pendiente |
| Sustituir `echo`/`alert()` por mensajes visuales | [[bloque-4-validacion-mensajes]] | Pendiente |

## Archivos relacionados

| Archivo | Rol |
|---------|-----|
| `includes/db.php` | Función `INSERTAR_USUARIO()` que llama al procedimiento almacenado |
| `css/index.css` | Estilos del formulario |
| `ini.php` | Destino sugerido tras registro exitoso |

## Notas técnicas

- Patrón: comprobación `$_SERVER['REQUEST_METHOD'] === 'POST'` al inicio, formulario HTML debajo.
- El `<select>` de puestos debe cargar las opciones desde la tabla `puesto` de la BD.
- `password_hash($contrasena, PASSWORD_BCRYPT)` — correcto, no cambiar.
- Tras registro exitoso: considerar redirigir a `ini.php` con un mensaje de éxito, o iniciar sesión directamente.

## Procedimiento almacenado asociado

`INSERTAR_USUARIO(id_puesto, nombre, email, contrasena)` — definido en `db.sql`
- Devuelve `row_count()` como `response` (1 si insertado, 0 si error/duplicado)

## Referencias

- [[index]] — página anterior (botón "Registrarse")
- [[ini-php]] — destino tras registro exitoso
- [[bloque-4-validacion-mensajes]] — tareas de validación y mensajes
