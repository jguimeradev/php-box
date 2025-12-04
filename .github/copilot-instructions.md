# AI Coding Instructions for php-box

## Project Overview
**php-box** is a learning workspace containing 11 progressive mini-projects teaching core PHP web development patterns, from authentication to deployment. Each sub-project is self-contained, isolated, and designed for 1–3 hour completion cycles.

## Architecture & Project Structure

The workspace contains these sequential learning projects:
- `auth/` - User authentication with sessions and SQLite
- `crud/` - CRUD operations on single tables
- `router/` - Custom PHP routing without frameworks
- `csrf/` - CSRF protection and flash messages
- `1n/` - One-to-many relationships (projects + tasks)
- `nn/` - Many-to-many with pivot tables (users + events)
- `vis_sys/` - View templates with Bootstrap layout
- `migrations/` - Database migration system
- `logging/` - Structured logging with JSON format
- `phpunit/` - Unit testing patterns
- `deploy/` - Production deployment setup

## Key Patterns & Conventions

### PSR-4 Autoloading
All PHP classes follow PSR-4 namespacing under `Debian\Php\`. Each sub-project maps to its own namespace:
```php
namespace Debian\Php\auth\src\controller;  // For auth/ project
namespace Debian\Php\router\src\http;      // For router/ project
```
Root directory configuration in `composer.json`:
```json
"autoload": {
  "psr-4": {
    "Debian\\Php\\": "./"
  }
}
```

### Request Routing Pattern
Router class (`auth/src/http/Router.php`) handles HTTP dispatch:
- Simple route registration: `$router->get('/path', callable)`
- Supports `GET` and `POST` methods only
- Routes exact path matching (no regex patterns yet)
- Handler receives Router instance: `fn($router) => ...`
- 404 responses for unmatched routes

### Database Access
**Singleton pattern with PDO**:
- `DBConnection::getInstance()` ensures single DB connection
- SQLite used for all projects (lightweight, file-based at `src/database/*.sqlite3`)
- Prepared statements required for security
- Models fetch data via static methods: `UserModel::all()` returns array of objects

### MVC Structure per Project
- **Controllers**: `src/controller/` - receive Router instance, call models, render views
- **Models**: `src/model/` - static CRUD methods, manage DB queries
- **Views**: `src/views/` - PHP templates with `.php` extension
- **Data/HTTP**: `src/data/` and `src/http/` - DB connections and routing

### View Rendering
Router includes `render()` method that:
1. Resolves template path: `src/views/{template}.php`
2. Extracts parameters into local scope: `extract($params)`
3. Uses output buffering to capture HTML
4. Throws `RuntimeException` if template not found

Views include shared partials from `src/views/includes/` (header.php, footer.php).

### Database Initialization
- SQL seeds stored in `src/scripts/seed_users.sql`
- Use `PRAGMA foreign_keys = ON` for SQLite constraint enforcement
- Use `INSERT OR IGNORE` to handle duplicate records safely
- Timestamps use ISO format: `'2025-03-01 12:00:00'`

## Development Workflow

### Starting a Sub-Project
1. Navigate to project directory: `cd auth/`
2. Run dev server: `composer dev` (PHP built-in server at `localhost:6969`)
3. Edit `public/index.php` to register routes
4. Implement controllers in `src/controller/`
5. Query models in `src/model/`

### Adding a New Route
In `public/index.php`:
```php
$router->get('/users/:id', [new UserController(), 'show']);
$router->post('/users', [new UserController(), 'store']);
```

In controller:
```php
public function show(Router $router) {
    $id = /* extract from path */;
    $user = UserModel::find($id);
    $router->render('users/show', ['user' => $user]);
}
```

### Adding Database Operations
In model (static methods preferred):
```php
public static function findByEmail($email) {
    $pdo = self::connectDB()->getConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_OBJ);
}
```

## Common Challenges & Solutions

**Challenge**: Mixed static/instance methods in models  
**Pattern**: Use static methods for queries (`UserModel::all()`), constructor for object state when needed.

**Challenge**: Reusable flash messages and form errors  
**Pattern**: Store in `$_SESSION['flash']` during request, consume in view header partial, clear after display.

**Challenge**: Preventing duplicate route registration  
**Pattern**: Each project's `public/index.php` is isolated; no global route conflicts.

**Challenge**: SQLite foreign key constraints not enforcing  
**Pattern**: Enable explicitly in seed SQL: `PRAGMA foreign_keys = ON` before creating tables.

## Important Conventions

- **No frameworks**: Pure PHP—understand HTTP lifecycle, PDO, and routing mechanics
- **Namespace all classes**: Never use global namespace
- **Prepared statements always**: Prevent SQL injection from start
- **Output buffering in views**: Allows capturing template output as string
- **Singleton DB connection**: Reuse `DBConnection::getInstance()` across request
- **Error handling minimal during learning**: Focus on happy path; expand error handling in later projects

## File Locations to Know

- `public/index.php` - Entry point for each project, route registration
- `src/http/Router.php` - HTTP dispatch and view rendering
- `src/controller/*Controller.php` - Request handlers
- `src/model/*.php` - Static query methods
- `src/views/*.php` - Template files
- `src/data/DBConnection.php` - PDO singleton
- `src/database/*.sqlite3` - SQLite database files
- `src/scripts/seed*.sql` - Database initialization

## Testing Approach
Unit tests with PHPUnit go in `tests/` within each project:
- Test static model methods: `UserModelTest::testAll()`
- Mock `DBConnection` for isolation
- Test controller via `call_user_func($handler, $router)`

## Next Steps for New Feature
1. **Understand scope**: Is it a new route, model method, or view?
2. **Check existing patterns**: Review similar routes/models in this project
3. **Add incrementally**: Register route → implement controller → call model → render view
4. **Test with dev server**: Verify request/response cycle
5. **Use prepared statements**: Never concatenate user input into queries
