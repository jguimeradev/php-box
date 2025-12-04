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

## Características de la aplicación


* Rutas:
  * app/sign-up
    * post
  * app/log-in
    * get
  * app/log-out
    * get
* Database:
  * Tabla User
* Controlador:
  * El controlador comunicacará entre el entry point y el modelo.
  * Input validation
* Modelo:
  * El modelo va a mover los datos entre el controlador y la base de datos. 
  * Las propiedades del modelo coincidirán con las columnas de la tabla 'user'. 
  * Realizará las queries SQL contra la base de datos.
  * Business rules
* Vistas:
  * Index
  * Sign-Up
  * Login
  * User profile page