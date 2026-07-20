---
tags: [tareas, bloque-8, semana-2026-07-20]
estado: pendiente
bloque: 8
semana: "2026-07-20"
---

# Bloque 8 — Sistema de Roles y Control de Acceso

> Prerequisito para [[bloque-6-crud-usuarios-admin]] y [[bloque-7-crud-tareas]]. Añade `rol` a la BD, lo propaga a la sesión y expone helpers de autorización reutilizables. Expande [[bloque-5-funcionalidades-futuras]] tarea 5.4.

## Tareas

- [ ] **8.1 — Añadir `rol` a `usuarios` y actualizar `INICIAR_SESION`**
  Dos cambios coordinados en `db.sql`:
  - `ALTER TABLE usuarios ADD COLUMN rol ENUM('admin','usuario') NOT NULL DEFAULT 'usuario';`
  - Ampliar el SELECT de `INICIAR_SESION(_email)` para que devuelva también `rol`
  - Seedear el primer admin: `UPDATE usuarios SET rol = 'admin' WHERE id_usuario = 1;`
  - Archivo afectado: `db.sql`

- [ ] **8.2 — Guardar `rol` en sesión tras login**
  En la función `INICIAR_SESION()` de `includes/db.php`:
  - Leer `$fila['rol']` del resultado y asignar `$_SESSION['rol'] = $fila['rol']`
  - El resto del flujo de login no cambia
  - Archivo afectado: `includes/db.php`

- [ ] **8.3 — Crear `includes/auth_rol.php`**
  Nuevo archivo con dos funciones de autorización:
  ```php
  function es_admin(): bool {
      return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
  }

  function requiere_admin(): void {
      if (!es_admin()) {
          header('Location: session.php');
          exit;
      }
  }
  ```
  - Archivo nuevo: `includes/auth_rol.php`

- [ ] **8.4 — Visibilidad condicional del enlace Admin en el nav**
  En `includes/nav.php`, mostrar el enlace "Admin" solo a administradores:
  ```php
  <?php if (es_admin()): ?>
      <a href="admin.php" class="<?= $pagina_actual === 'admin.php' ? 'active' : '' ?>">Admin</a>
  <?php endif; ?>
  ```
  - Requiere `require_once` de `auth_rol.php` al inicio de `nav.php` (o que ya esté en el scope de la página)
  - Archivo afectado: `includes/nav.php`

## Notas

> [!warning] Orden de implementación
> Este bloque va primero. [[bloque-6-crud-usuarios-admin]] necesita `requiere_admin()` y [[bloque-7-crud-tareas]] necesita `es_admin()` para filtrar tareas.

> [!info] Sin tabla de permisos separada
> El rol va directamente en `usuarios.rol`. Una tabla `permisos` sería necesaria si los permisos fueran granulares por recurso; con dos roles es suficiente para este proyecto.

> [!tip] Seedear el primer admin
> No hay flujo de "primer admin" en el registro. Ejecutar en MySQL una vez aplicada la migración:
> ```sql
> UPDATE usuarios SET rol = 'admin' WHERE id_usuario = 1;
> ```

## Referencias

- [[bloque-5-funcionalidades-futuras]] — tarea 5.4 que este bloque implementa
- [[bloque-6-crud-usuarios-admin]] — depende de este bloque
- [[bloque-7-crud-tareas]] — usa `es_admin()` para filtrar vistas
- [[base-de-datos]] — modificación de `usuarios` y `INICIAR_SESION`
- [[ini-php]] — flujo de login afectado (tarea 8.2)
