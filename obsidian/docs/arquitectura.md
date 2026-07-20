---
tags: [docs, arquitectura]
---

# Arquitectura del Proyecto

## Convenciones generales

- **PHP procedural en español**: variables y funciones en castellano (`$Coneccion`, `INSERTAR_USUARIO`)
- **Toda la lógica de datos en `includes/db.php`**: no hay SQL suelto en las páginas
- **Procedimientos almacenados + consultas preparadas**: `mysqli->prepare()` + `bind_param()`
- **Formulario y procesamiento en el mismo archivo**: `if ($_SERVER['REQUEST_METHOD'] === 'POST')` al principio

## Separación de páginas

| Tipo | Páginas | Descripción |
|------|---------|-------------|
| Pública | `index.html`, `ini.php`, `registr.php` | Sin sesión requerida |
| Privada | `session.php`, `tasks.php`, `inbox.php`, `settings.php` | Requieren `auth_check.php` |
| Acción | `logout.php` | Solo lógica PHP, sin HTML |

## Includes

```
includes/
├─ db.php              ✅ Conexión + funciones PHP
├─ auth_check.php      ✅ Protección sesión → redirect ini.php
├─ nav.php             ✅ Nav superior extraído (Bloque 2, 2026-07-19)
├─ footer_publico.php  ✅ Footer páginas públicas (Bloque 2, 2026-07-19)
└─ footer_privado.php  ✅ Footer páginas privadas (Bloque 2, 2026-07-19)
```

## CSS

```
css/
├─ index.css    Estilos públicos: formularios de login/registro, layout flexbox
└─ session.css  Estilos privados: nav, layout del panel
```

No crear nuevos estilos base desde cero — extender los existentes manteniendo la paleta y el sistema de diseño.

## Sistema de diseño (actualizado 2026-07-19)

Rediseño completo realizado en la semana del 14–19 jul. La estética Aero/Vista fue sustituida por un diseño moderno y limpio.

**Paleta de color:**
- Primario: `#4a6741` (verde)
- Fondo: `#eef0ed`
- Tipografía: sistema (sin fuente personalizada)

**Layout:** Flexbox en todas las páginas.

**Iconos:** Biblioteca SVG/PNG añadida (~328 iconos: Edit, Search, Settings, Info, etc.). Los iconos se referencian como `<img src="assets/icons/...">` o inline SVG según contexto.

**Componentes UI:**
- Formularios rediseñados con mejor UX y validación visual
- Nav con estados hover y tipografía limpia
- Mensajes de error/éxito (pendiente — Bloque 4)

> [!info] Estética anterior
> El proyecto comenzó con estética Aero/Vista (Windows 7, librería 7.css, fondo cristal, Segoe UI). Fue descartada el 2026-07-19 en favor del diseño verde actual. Cualquier referencia a `.aero-window` o `fontVista/` en notas antiguas ya no aplica.

## Flujo de datos (login)

```
ini.php (POST)
  └─ INICIAR_SESION($email) en db.php
       └─ CALL INICIAR_SESION(_email) [procedimiento SQL]
            └─ devuelve: id_usuario, nombre, email, contrasena, id_puesto
  └─ password_verify($contrasena_input, $hash_bd)
       └─ true  → $_SESSION = {email, id_usuario, nombre, id_puesto} → redirect session.php
       └─ false → mensaje de error
```

## Referencias

- [[base-de-datos]] — esquema de BD
- [[bloque-1-sesion-seguridad]] — tareas que completan la arquitectura de auth
- [[bloque-2-estructura-reutilizable]] — tareas que completan los includes
