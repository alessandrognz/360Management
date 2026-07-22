---
tags: [tareas, bloque-4]
estado: pendiente
bloque: 4
---

# Bloque 4 — Validación y Mensajes al Usuario

Sustituir los `echo`/`alert()` provisionales de `index.php` por un sistema de mensajes visual, y reforzar la validación en servidor.

## Tareas

- **4.1 — Validación robusta en el registro (`index.php?action=reg`).**
  - Email con formato válido (`filter_var($email, FILTER_VALIDATE_EMAIL)`)
  - Contraseña con longitud mínima (8 caracteres)
  - Contraseñas coincidentes (ya existe)
  - Campos obligatorios no vacíos
  - `puesto` es un entero válido y existe en BD

- **4.2 — Validación robusta en el login (`index.php?action=ini`).** Email y contraseña no vacíos, formato de email válido.

- **4.3 — Sistema de mensajes de error/éxito integrado visualmente.** Sustituir los `echo`/`alert()` por HTML con estilo propio (`.mensaje-error` / `.mensaje-exito`), mostrado condicionalmente tras el POST. Archivo: `index.php`.

- **4.4 — Estilos para los mensajes.** Clases `.mensaje-error` y `.mensaje-exito` en `index.css` (y `session.css` si se necesitan en el panel).

## Patrón sugerido

```php
$error = null;
$exito = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // validaciones...
    $error = $algun_fallo ? "Descripción del error." : null;
}
```
```php
<?php if ($error): ?>
    <div class="mensaje-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
```

Cualquier variable de usuario impresa en HTML debe pasar por `htmlspecialchars()`.

## Referencias

- [[bloque-3-panel-session]] — prerequisito
- [[bloque-5-funcionalidades-futuras]] — siguiente bloque
- [[index]] — página afectada
