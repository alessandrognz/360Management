# 360Management — Contexto del Proyecto

> Archivo de contexto para Obsidian. Última actualización: 17/07/2026.
> Objetivo de este documento: que cualquiera (o cualquier IA, como Claude Code) que retome el proyecto entienda qué es, qué hay hecho, qué falta, cómo se trabaja y qué se busca conseguir, sin tener que releer todo el código.

---

## 1. Qué es el proyecto

**360Management** es una aplicación web de gestión de usuarios/empresa. Las personas se registran indicando su puesto de trabajo (asociado a un departamento) y pueden iniciar sesión para acceder a un panel privado. Es la base sobre la que se construirá el resto del sistema de gestión (tareas, inbox, configuración...).

No es un producto final ni un SaaS comercial por ahora: es un proyecto de aprendizaje/portfolio orientado a hacerlo bien desde la base (seguridad, estructura, buenas prácticas), no a sacarlo rápido y mal.

---

## 2. Qué buscamos conseguir

- Un sistema de login/registro **seguro y correcto** (contraseñas hasheadas, sesiones protegidas, validación de datos).
- Una arquitectura **fácil de escalar**: que añadir una página nueva (Tasks, Inbox, Settings) sea rápido porque el nav, el footer, la autenticación y el acceso a datos están centralizados y reutilizados, no copiados en cada archivo.
- Una app **funcional antes que bonita**, pero cuidando el estilo visual (estética "Aero" de Windows Vista/7) como seña de identidad del proyecto.
- Aprender/practicar PHP procedural con MySQL usando procedimientos almacenados y consultas preparadas, en vez de escribir SQL suelto en el PHP.

---

## 3. Stack tecnológico

- **Frontend**: HTML + CSS puro, sin frameworks.
- **Backend**: PHP procedural (extensión `mysqli`), en español (variables y funciones con nombres en castellano).
- **Base de datos**: MySQL, con procedimientos almacenados para toda la lógica de acceso a datos.
- **Entorno local**: XAMPP en Windows (Apache + MySQL).
- **Estética**: estilo "Aero" (inspirado en 7.css), con la fuente Segoe UI incluida en el proyecto (carpeta `fontVista`).

---

## 4. Estructura del proyecto

```
360Management/
├─ index.html          Pantalla de bienvenida pública: logo + botones
│                       "Iniciar Sesión" y "Registrarse".
├─ ini.php              Formulario de login + procesamiento (llama a
│                       INICIAR_SESION).
├─ registr.php          Formulario de registro + procesamiento (llama a
│                       INSERTAR_USUARIO).
├─ session.php          Panel privado (home interno). Por ahora solo
│                       tiene el nav, sin contenido ni protección.
├─ includes/db.php      Conexión mysqli + funciones PHP INSERTAR_USUARIO
│                       e INICIAR_SESION (llaman a los procedimientos
│                       almacenados con consultas preparadas).
├─ db.sql               Script de creación de la base de datos.
├─ procedure.sql        Reservado para más procedimientos (vacío/borrador).
├─ css/index.css         Estilos de páginas públicas: fondo, formulario
│                       "cristal aero", botones, componente .aero-window.
├─ css/session.css       Estilos del panel privado (nav superior estilo Aero).
└─ img/, fontVista/     Imágenes y fuentes.
```

**Nota importante de nomenclatura**: hay dos "páginas de inicio" distintas y no deben confundirse:
- `index.html` → bienvenida pública, ya resuelta.
- `session.php` → panel interno real, el que hay que seguir construyendo.

---

## 5. Base de datos (`db.sql`)

Base de datos `users`, con 3 tablas relacionadas:

- **`departamento`**: 7 departamentos (Dirección y Estrategia, Administración y Finanzas, RRHH, Marketing y Ventas, Tecnología y Desarrollo, Operaciones/Logística/Producto, Legal y Calidad).
- **`puesto`**: 20 puestos, cada uno vinculado a un departamento (FK `fk_departamento_puesto`).
- **`usuarios`**: `id_usuario`, `id_puesto` (FK), `nombre`, `email` (único), `fecha_registro`, `contrasena`, `eliminado`.

Todas las tablas usan **borrado lógico** (columna `eliminado BIT DEFAULT(0)`), no se borran filas físicamente.

**Procedimientos almacenados existentes:**
- `INSERTAR_USUARIO(id_puesto, nombre, email, contrasena)` → inserta usuario, devuelve `row_count()` como `response`.
- `INICIAR_SESION(_email, _contrasena)` → devuelve `email, contrasena` del usuario si `eliminado = 0`.
  - ⚠️ Pendiente: este SELECT solo devuelve `email` y `contrasena`. Para construir el panel (bienvenida personalizada, permisos por puesto) necesita devolver también `id_usuario`, `nombre` e `id_puesto`.

`procedure.sql` está vacío/reservado, no confundir con el bloque de procedimientos que realmente vive dentro de `db.sql`.

---

## 6. Cómo se trabaja (convenciones del proyecto)

- **PHP procedural en español**: nombres de variables y funciones en castellano (`$Coneccion`, `INSERTAR_USUARIO`, `INICIAR_SESION`).
- **Toda la lógica de datos centralizada en `includes/db.php`**: no se escribe SQL directo en las páginas, siempre se llama a un procedimiento almacenado vía `mysqli->prepare()` + `bind_param()`.
- **Formulario y procesamiento en el mismo archivo** (patrón usado en `registr.php` e `ini.php`): se comprueba `$_SERVER['REQUEST_METHOD'] === 'POST'` al principio del archivo, y debajo sigue el HTML del formulario.
- **Mensajes de prueba con `echo`/`alert()` de forma provisional**: es sabido que esto es temporal, se sustituirá por mensajes de error/éxito reales más adelante.
- **Reutilización de componentes visuales**: el componente `.aero-window` (definido en `index.css`) está pensado para reutilizarse como base de futuras tarjetas/widgets del panel, no crear estilos nuevos desde cero para cada bloque.
- **Antes de construir sobre algo, se protege primero lo básico**: p. ej., antes de diseñar contenido para `session.php`, primero se corrige que la sesión guarde los datos necesarios y que la página esté protegida contra acceso sin login.
- Se prioriza avanzar en orden de dependencia lógica (primero lo que otras partes necesitan) antes que por lo visualmente más vistoso.

---

## 7. Qué hay hecho ya

- ✅ `index.html` — pantalla de bienvenida pública, funcional y con estilo definido.
- ✅ Formulario de registro (`registr.php`) con procesamiento: recoge datos por POST, valida que las contraseñas coincidan, hashea con `password_hash()` (BCRYPT) y llama a `INSERTAR_USUARIO`.
- ✅ Formulario de login (`ini.php`) con procesamiento: recoge email/contraseña y llama a `INICIAR_SESION`.
- ✅ `includes/db.php`: conexión mysqli + funciones `INSERTAR_USUARIO` e `INICIAR_SESION` con consultas preparadas.
- ✅ Verificación de contraseña con `password_verify()` en `INICIAR_SESION` (con fallback a comparación en texto plano, ver sección de riesgos).
- ✅ Base de datos completa: tablas, relaciones, datos iniciales de departamentos y puestos, procedimiento `INSERTAR_USUARIO`.
- ✅ Estética Aero definida en CSS (fondo, formulario "cristal", botones, componente de ventana reutilizable).
- ✅ Nav superior del panel (`session.php` + `session.css`), con enlaces a Inicio, Tasks, Inbox, Settings (estos últimos tres aún sin destino real).

---

## 8. Qué falta por hacer (y en qué orden abordarlo)

### Bloque 1 — Base de sesión y seguridad (prioridad alta, todo lo demás depende de esto)
1. Ampliar el SELECT de `INICIAR_SESION` (procedimiento almacenado) para devolver también `id_usuario`, `nombre`, `id_puesto`.
2. Actualizar la función PHP `INICIAR_SESION()` en `db.php` para guardar en `$_SESSION` no solo `email`, sino también `id_usuario`, `nombre`, `id_puesto`.
3. Crear `includes/auth_check.php`: comprobación reutilizable que redirige a `ini.php` si no hay sesión iniciada. Debe incluirse al principio de `session.php` y de cualquier página privada futura.
4. Revisar el fallback de `password_verify($contrasena, $hash) || $contrasena === $hash` en `db.php` — es un riesgo de seguridad (permite login si la contraseña coincide en texto plano); debería eliminarse una vez se confirme que todas las contraseñas en BD están hasheadas.
5. Añadir logout: `logout.php` con `session_destroy()` + redirect a `index.html`, enlazado desde el nav.

### Bloque 2 — Estructura reutilizable
6. Extraer el `<nav>` de `session.php` a `includes/nav.php`, incluido con `require` en todas las páginas privadas (evitar duplicar el bloque al crear `tasks.php`, `inbox.php`, etc.).
7. Marcar el link activo del nav dinámicamente (comparando `basename($_SERVER['PHP_SELF'])` contra cada `href`), en vez de la clase `active` fija actual.
8. Crear `includes/footer_publico.php` (para `index.html`, `ini.php`, `registr.php`) e `includes/footer_privado.php` (para páginas internas), con estilos coherentes con el sistema Aero.

### Bloque 3 — Contenido del panel (`session.php`)
9. Bienvenida personalizada: "Hola, {nombre}" + puesto/departamento, usando los datos ya disponibles en `$_SESSION` (bloque 1).
10. Tarjetas de resumen usando `.aero-window`: tareas pendientes, mensajes sin leer en Inbox, avisos recientes (de momento pueden ser datos de ejemplo si Tasks/Inbox aún no existen).
11. Accesos directos a las secciones más usadas.

### Bloque 4 — Validación y mensajes al usuario
12. Validación de datos en servidor: email con formato válido, contraseñas coincidentes (ya existe parcialmente en `registr.php`), campos obligatorios no vacíos.
13. Sustituir los `echo`/`alert()` de prueba por mensajes de error/éxito reales, integrados visualmente (no JS `alert()`).

### Bloque 5 — Funcionalidades nuevas (a futuro, aún no diseñadas en detalle)
14. Sección Tasks.
15. Sección Inbox.
16. Sección Settings (edición de perfil, cambio de contraseña).
17. Posible sistema de permisos por puesto/departamento (ya existe la relación en BD, falta lógica de aplicación).

---

## 9. Riesgos / deuda técnica conocida

- El fallback de contraseña en texto plano en `INICIAR_SESION()` (`db.php`) debe eliminarse — es la única razón por la que un login con hash roto podría "colar".
- `session.php` no tiene ninguna protección de acceso todavía: cualquiera que conozca la URL entra sin login.
- No hay logout implementado.
- Los procedimientos almacenados de `db.sql` no coinciden aún al 100% con lo que necesita el panel (falta ampliar el SELECT de `INICIAR_SESION`).
- `procedure.sql` está vacío y puede llevar a confusión — considerar documentar en el propio repo que los procedimientos reales están en `db.sql`, o mover ahí los nuevos procedimientos que se vayan creando.

---

## 10. Cómo continuar trabajando en esto (para Claude Code u otra persona)

- Antes de tocar el panel (`session.php`), asegurarse de que el **Bloque 1** está resuelto — todo el resto depende de que la sesión tenga los datos correctos y esté protegida.
- Seguir el patrón ya existente en el proyecto (procedimientos almacenados + consultas preparadas, nombres en español, formulario+procesamiento en el mismo archivo) en vez de introducir un estilo distinto.
- Reutilizar `.aero-window` y las variables de color ya definidas en `index.css` para cualquier componente visual nuevo, para mantener coherencia estética.
- Ir por bloques en el orden indicado en la sección 8; no conviene empezar por Tasks/Inbox/Settings (Bloque 5) sin haber cerrado antes los Bloques 1 y 2, porque se duplicaría trabajo (nav, footer, auth) que luego habría que refactorizar.
