---
tags: [pagina, publica, hecha]
archivo: index.html
estado: completo
tipo: publica
---

# index.html — Bienvenida Pública

## Qué hace

Pantalla de entrada a la aplicación. Muestra el logo del proyecto y dos botones:
- **Iniciar Sesión** → redirige a `ini.php`
- **Registrarse** → redirige a `registr.php`

No tiene lógica PHP — es HTML estático puro.

## Estado

✅ Completada. Sin tareas pendientes en este archivo.

## Archivos relacionados

| Archivo | Rol |
|---------|-----|
| `assets/css/index.css` | Estilos: fondo, componente `.aero-window`, botones |
| `assets/icons/` | Logo y recursos visuales |
| `fontVista/` | Fuente Segoe UI |

## Notas técnicas

- Al ser `.html` estático, no puede usar `require` para incluir nav o footer. Si en algún momento se necesita lógica PHP, habrá que renombrarla a `index.php`.
- El componente `.aero-window` definido en `index.css` es reutilizable en el resto de páginas para mantener coherencia visual.

## Capturas / descripción visual

- Fondo: imagen o degradado de estilo Aero (azul/cristal)
- Componente central: `.aero-window` con efecto cristal
- Botones: estilo Aero (borde redondeado, gradiente, sombra)

## Referencias

- [[ini-php]] — destino del botón "Iniciar Sesión"
- [[registr-php]] — destino del botón "Registrarse"
