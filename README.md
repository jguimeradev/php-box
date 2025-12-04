A continuación tienes una **serie de mini-proyectos previos**, cada uno enfocado en un subconjunto de capacidades técnicas que usarás en Moodle-Lite. El propósito es **reducir riesgo**, **probar patrones**, **ganar velocidad** y **evitar cuellos de botella** antes de entrar al MVP completo.

Los ejercicios están ordenados **de menor a mayor complejidad**, y cada uno está diseñado para ser pequeño, aislado y completado en 1–3 horas.

---

# 1. Proyecto previo: **Mini-Sistema de Autenticación en PHP puro**

**Objetivo:** dominar registro, login, sesión y seguridad básica.
**Alcance mínimo:**

* Formulario “register” (email + password).
* Guardar usuario en SQLite (Tabla users).
* Login con sesión (`session_start`, `password_verify`, regeneración de sesión).
* Logout.
* Página protegida solo para logueados.

**Qué practicas:**

* Password hashing seguro.
* Sesiones y cookies.
* Validación server-side.
* Estructura básica de controladores y vistas.
* PDO + prepared statements.

**Beneficio:** te aseguras de que **autenticación + sesión** están bajo control antes del proyecto real.

---

# 2. Proyecto previo: **CRUD simple con SQLite (1 tabla)**

**Objetivo:** dominar operaciones CRUD y paginación fundamental.
**Alcance mínimo:**

* Tabla “notes” (id, title, body, created_at).
* Crear / listar / editar / eliminar nota.
* Validación mínima.
* Paginación con limit/offset.

**Qué practicas:**

* Repositorios con PDO.
* Transacciones simples.
* Escapar salida.
* Routing básico (GET/POST).
* Estructura de vistas con layout.

**Beneficio:** reduces el riesgo de que el CRUD de cursos/lecciones te consuma demasiada energía en el MVP.

---

# 3. Proyecto previo: **Router básico en PHP sin framework**

**Objetivo:** construir un enrutador funcional que usará directamente Moodle-Lite.
**Alcance mínimo:**

* Archivo `public/index.php` que interpreta `$_SERVER['REQUEST_URI']`.
* Tabla de rutas en `config/routes.php`: patrón → controlador::método.
* Soporte para rutas con parámetros usando regex: `/articles/(\d+)`.
* Distinción por método: GET vs POST (o `_method` hidden en forms).

**Qué practicas:**

* Despacho manual.
* Construcción ligera del patrón MVC.
* Separación controllers / views.

**Beneficio:** elimina el riesgo del enrutador del proyecto grande.

---

# 4. Proyecto previo: **CSRF + Flash messages**

**Objetivo:** dominar mecanismos de seguridad esenciales y experiencia de usuario.
**Alcance mínimo:**

* Generar token CSRF por sesión.
* Incluirlo en todos los forms POST.
* Validación del token antes de procesar.
* Sistema flash simple (guardar en sesión un mensaje y consumirlo al mostrarlo).

**Qué practicas:**

* random_bytes + hash_equals.
* Sesión avanzada.
* Helpers reutilizables.

**Beneficio:** cuando entres al MVP, CSRF + flash estará resuelto y podrás aplicarlo en controladores de curso/lección.

---

# 5. Proyecto previo: **Micro-relacional (1:N) con SQLite**

**Objetivo:** practicar relaciones y consultas preparadas con claves foráneas.
**Alcance mínimo:**

* Tabla `projects`.
* Tabla `tasks` con FK a `projects(id)`.
* CRUD de proyectos.
* CRUD de tareas ligadas al proyecto.
* Mostrar tareas de un proyecto.

**Qué practicas:**

* Relaciones 1:N como en cursos → lecciones.
* JOIN simples.
* PRAGMA foreign_keys = ON.
* Ordenación por posición.

**Beneficio:** tras esto, el CRUD de lecciones será directo.

---

# 6. Proyecto previo: **Sistema de inscripción simple (tabla pivot)**

**Objetivo:** practicar relaciones N:N con tabla pivot.
**Alcance mínimo:**

* Tabla `users`.
* Tabla `events`.
* Tabla pivot `registrations (user_id, event_id, created_at)`.
* Enroll / Unenroll.
* Mostrar solo eventos inscritos por un usuario.

**Qué practicas:**

* Operaciones sobre tabla pivot.
* Comprobaciones de unicidad (INSERT OR IGNORE / constraints UNIQUE).
* Control de acceso (si no inscrito → no ver detalles).
* Servicios y Repositorios más complejos.

**Beneficio:** el módulo de enrollments de Moodle-Lite será un clon conceptual.

---

# 7. Proyecto previo: **Front-end templating mínimo**

**Objetivo:** consolidar tu estructura de vistas con Bootstrap.
**Alcance mínimo:**

* Layout base: header, navbar, flash messages, footer.
* 3 vistas con formularios sencillos.
* Partial `form-errors.php`.
* Helpers para `old()` (mantener valores tras fallo de validación).

**Beneficio:** las vistas del MVP salen más rápido y limpias.

---

# 8. Proyecto previo: **Migrations y seeds básicos**

**Objetivo:** preparar tu pipeline para probar DB rápidamente.
**Alcance mínimo:**

* Carpeta migrations con 3 archivos SQL (crear tabla A, B, pivot).
* Script PHP que:

  * Lea todas las migrations en orden.
  * Verifique si ya corrieron (tabla migrations).
  * Las aplique.
* Seed simple para insertar datos de prueba.

**Beneficio:** el proyecto grande tendrá un entorno reproducible desde el día 1.

---

# 9. Proyecto previo: **Logging estructurado mínimo**

**Objetivo:** tener visibilidad básica.
**Alcance mínimo:**

* Clase Logger que use `error_log()` con formato JSON:
  `{ "level":"error", "msg":"Login failed", "email":"x@example.com" }`
* Registrar: logins fallidos, excepciones, y accesos.

**Beneficio:** te ayudará a depurar el proyecto grande.

---

# 10. Proyecto previo: **Pruebas unitarias básicas con PHPUnit**

**Objetivo:** establecer disciplina de pruebas antes del MVP.
**Alcance mínimo:**

* Test para AuthService con casos:

  * registro válido.
  * registro con email repetido.
  * login correcto/incorrecto.
* Test para CourseRepository (insert, update, find).

**Beneficio:** reduces el riesgo de romper funcionalidades al avanzar rápido.

---

# 11. Proyecto previo: **Mini-deploy local con PHP-FPM y Nginx**

**Objetivo:** evitar sorpresas al final del proyecto.
**Alcance mínimo:**

* Un Nginx config simple que apunta a `public/`.
* Verificar rewrite hacia index.php.
* Revisión de headers (Secure / HttpOnly).

**Beneficio:** cuando termines el MVP, el despliegue será trivial.

---

# ORDEN SUGERIDO (HOJA DE RUTA PRACTICABLE)

1. Autenticación básica.
2. CRUD simple (1 tabla).
3. Router básico.
4. CSRF + Flash.
5. 1:N (Projects + Tasks).
6. Pivot N:N (Users + Events).
7. Sistema de vistas con Bootstrap.
8. Migrations y seeds.
9. Logging.
10. PHPUnit.
11. Deploy local.

---

