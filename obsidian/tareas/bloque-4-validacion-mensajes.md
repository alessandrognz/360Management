---
tags: [tareas, bloque-4]
estado: pendiente
bloque: 4
---

# Bloque 4 — Validación y Mensajes al Usuario

> Sustituir los `echo`/`alert()` provisionales por un sistema de mensajes visual coherente con el estilo Aero. Mejorar la validación en servidor.

## Tareas

- [ ] **4.1 — Validación robusta en `registr.php`**
  Añadir o reforzar las siguientes comprobaciones en servidor (antes de llamar a `INSERTAR_USUARIO`):
  - Email con formato válido (`filter_var($email, FILTER_VALIDATE_EMAIL)`)
  - Contraseña con longitud mínima (p. ej. 8 caracteres)
  - Contraseñas coincidentes (ya existe parcialmente)
  - Campos obligatorios no vacíos (`empty()`)
  - `id_puesto` es un entero válido y existe en BD
  - Archivo afectado: `registr.php`

- [ ] **4.2 — Validación robusta en `ini.php`**
  Comprobaciones en servidor antes de llamar a `INICIAR_SESION`:
  - Email no vacío y con formato válido
  - Contraseña no vacía
  - Archivo afectado: `ini.php`

- [ ] **4.3 — Sistema de mensajes de error/éxito integrado visualmente**
  Sustituir los `echo` sueltos y los `alert()` de JavaScript por mensajes HTML con estilos propios, coherentes con el sistema Aero. Propuesta:
  - Un div `.mensaje-error` o `.mensaje-exito` con borde y color acorde al tema
  - Se muestra condicionalmente si `$error` o `$exito` están definidos tras el procesamiento POST
  - Archivos afectados: `registr.php`, `ini.php`, `session.php`

- [ ] **4.4 — Crear estilos para mensajes en CSS**
  Añadir las clases `.mensaje-error` y `.mensaje-exito` a `index.css` (páginas públicas) y a `session.css` (panel privado), manteniendo la estética Aero (colores, bordes, tipografía).
  - Archivos afectados: `css/index.css`, `css/session.css`

## Notas

> [!info] Patrón sugerido para mensajes
> ```php
> // Al inicio del archivo, tras procesar el POST:
> $error = null;
> $exito = null;
>
> if ($_SERVER['REQUEST_METHOD'] === 'POST') {
>     // validaciones...
>     if ($algún_fallo) {
>         $error = "Descripción del error.";
>     } else {
>         $exito = "Operación completada.";
>     }
> }
> ```
> Luego en el HTML:
> ```php
> <?php if ($error): ?>
>     <div class="mensaje-error"><?= htmlspecialchars($error) ?></div>
> <?php endif; ?>
> ```

> [!warning] `htmlspecialchars` obligatorio
> Cualquier variable de usuario que se imprima en HTML debe pasar por `htmlspecialchars()` para evitar XSS.

## Referencias

- [[bloque-3-panel-session]] — prerequisito
- [[bloque-5-funcionalidades-futuras]] — siguiente bloque
- [[registr-php]] — [[ini-php]] — páginas afectadas principales
