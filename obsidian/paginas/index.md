---
tags: [pagina, publica, completa]
archivo: index.php
estado: completo
tipo: publica
---

# index.php — Bienvenida, Login y Registro

## Qué hace

Landing pública. Muestra el logo y dos botones ("Iniciar Sesión" / "Registrarse") que abren modales sobre la misma página — ya no hay páginas separadas `ini.php` / `registr.php`.

Login y registro se procesan en el propio `index.php` según el parámetro `?action=`:

- `action=ini` — llama a `loginAndRegister::INICIAR_SESION()`
- `action=reg` — valida que las contraseñas coincidan, hashea con `password_hash(BCRYPT)` y llama a `loginAndRegister::INSERTAR_USUARIO()`

Los modales se abren/cierran con `assets/js/index.js` (clase `.active`, cierre con Escape o clic fuera).

## Estado

Completa. Sin validación de servidor todavía — ver [[bloque-4-validacion-mensajes]].

## Archivos relacionados

- `includes/db.php` — clase `loginAndRegister`
- `assets/css/index.css` — estilos del landing y los modales
- `assets/js/index.js` — lógica de apertura/cierre de modales

## Notas técnicas

- El select de puestos del registro está hardcodeado en el HTML (20 opciones), no se carga desde BD.
- Quedan `echo '<script>console.log(...)'` de depuración y un `alert()` de error en el flujo de registro — ver [[deuda-tecnica]].
- Sin validación de formato de email ni de longitud de contraseña en servidor (el `minlength="8"` es solo del cliente).

## Referencias

- [[session-php]] — destino tras login o registro exitoso
- [[bloque-4-validacion-mensajes]] — validación pendiente
