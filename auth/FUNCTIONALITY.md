# Auth Module - Functionality Documentation

A pure PHP authentication system demonstrating core web development patterns: routing, MVC architecture, form validation, database operations, and unit testing.

## Quick Start

```bash
cd auth
composer dev
```

Visit `http://localhost:6969`

## Architecture Overview

This module implements a complete authentication system using pure PHP without frameworks. The architecture follows MVC principles with clear separation of concerns:

```
Request → Router → Controller → Model → Database
                  ↓
               View (render)
```

## Core Components

### 1. Router (`src/http/Router.php`)

**Purpose:** HTTP request routing and view rendering

**Key Methods:**
- `get(string $path, callable $handler)` - Register GET routes
- `post(string $path, callable $handler)` - Register POST routes
- `dispatch()` - Match request to route and execute handler
- `render(string $template, array $params)` - Render view with data

**How It Works:**
1. Extracts HTTP method from `$_SERVER['REQUEST_METHOD']`
2. Parses URI path from `$_SERVER['REQUEST_URI']`
3. Matches path against registered routes
4. Executes handler callback, passing Router instance
5. Throws 404 if no match found

**Example Usage:**
```php
$router->get('/', [new UserController(), 'index']);
$router->post('/signup', [new UserController(), 'save']);
$router->dispatch();
```

---

### 2. Database Connection (`src/data/DBConnection.php`)

**Purpose:** Singleton PDO connection for SQLite database

**Key Methods:**
- `getInstance()` - Get singleton instance
- `getConnection()` - Get PDO connection object

**Pattern:** 
- Singleton ensures single database connection across request lifecycle
- Lazy initialization - connection created only when first needed
- Uses SQLite for lightweight, file-based data storage

**Database Location:** `src/database/auth.sqlite3`

**Example Usage:**
```php
$db = DBConnection::getInstance();
$pdo = $db->getConnection();
$pdo->query("SELECT * FROM users");
```

---

### 3. User Model (`src/model/UserModel.php`)

**Purpose:** Data layer - encapsulates all user-related database operations and validation

**Constructor:**
```php
public function __construct(array $args, ?PDO $pdo = null)
```
- `$args` - User data (from form submission)
- `$pdo` - Optional PDO for testing (dependency injection)

**Static Methods (queries):**

| Method | Purpose | Returns |
|--------|---------|---------|
| `all()` | Fetch all users | Array of user objects |
| `findByEmail($email)` | Find user by email | Array of matching users |
| `connectDB()` | Get DB singleton | DBConnection instance |

**Instance Methods (getters):**
- `getEmail()` - Get user email from args
- `getPassword()` - Get user password from args
- `getFullName()` - Get user full name from args

**Validation:**
```php
public function validate(): bool
```
Validates user data. Checks:
- Email is required and valid format
- Password is required and min 6 characters
- Full name is required

Returns `true` if valid, `false` if errors exist.

**Create User:**
```php
public function create(): array
```
Creates new user in database if validation passes.

**Flow:**
1. Validates data using `validate()`
2. Checks if user already exists via `findByEmail()`
3. Hashes password using `password_hash()` with BCRYPT
4. Inserts into database using prepared statement
5. Returns empty array on success, array of errors on failure

**Error Handling:**
```php
public function getErrors(): array
```
Returns array of validation/creation errors (empty if no errors)

---

### 4. User Controller (`src/controller/UserController.php`)

**Purpose:** Request handler - orchestrates interaction between Router, Model, and View

**Methods:**

#### `index(Router $router): void`
Displays all users list
- Fetches all users via `UserModel::all()`
- Renders 'index' template with user data
- No parameters expected

**Flow:**
```
GET / → Controller::index() 
        → UserModel::all() 
        → render('index', ['data' => $users])
```

#### `save(Router $router): void`
Handles user registration (signup)
- Checks for POST data
- Creates UserModel with form data
- Calls `create()` to validate and insert
- On success: renders index with all users
- On failure: renders signup with errors

**Flow:**
```
POST /signup → Controller::save()
              → UserModel($_POST)
              → UserModel->create()
              
If valid:     → render('index', ['data' => users])
If invalid:   → render('signup', ['errors' => errors])
```

---

### 5. Views

#### `signup.php`
User registration form with fields:
- Full name (`name="full_name"`)
- Email (`name="email"`)
- Password (`name="password"`)
- Confirm Password (`name="confirm"`)
- Terms checkbox (`name="agree"`)

Form submits to `POST /signup`

Error display shows validation messages if present

#### `login.php`
User login form with fields:
- Email (`name="email"`)
- Password (`name="password"`)
- Remember checkbox (`name="remember"`)

Form submits to `POST /login`

#### `index.php`
Dashboard showing list of all users in table format

---

## Data Flow Example: User Signup

### 1. User visits `/signup`
```
GET /signup
    ↓
Router matches route to get handler
    ↓
render('signup', null)  // Show empty form
```

### 2. User submits form
```
POST /signup with form data
    ↓
Router matches to UserController->save()
    ↓
save() creates UserModel($_POST)
    ↓
UserModel->create() is called
    ↓
validate() checks:
  - Email required & valid format? ✓
  - Password required & min 6 chars? ✓
  - Full name required? ✓
    ↓
findByEmail() checks if email exists
    ↓
If valid & unique:
  → Hash password
  → INSERT INTO users
  → Return empty array []
    ↓
Controller sees empty errors
    ↓
render('index', ['data' => UserModel::all()])
    ↓
User sees success page with all users
```

### 3. If validation fails
```
validate() returns false
    ↓
errors contain: ['Email invalid', 'Password too short']
    ↓
create() returns errors array
    ↓
Controller sees non-empty errors
    ↓
render('signup', ['errors' => errors])
    ↓
View displays error messages
```

---

## Database Schema

### users table
```sql
CREATE TABLE users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  full_name TEXT NOT NULL,
  email TEXT NOT NULL UNIQUE,
  password_hash TEXT NOT NULL,
  role TEXT NOT NULL DEFAULT 'User',
  created_at TEXT NOT NULL
);
```

**Fields:**
- `id` - Auto-incrementing primary key
- `full_name` - User's display name
- `email` - Unique identifier (login)
- `password_hash` - Bcrypt hashed password
- `role` - User role (Admin, User)
- `created_at` - Registration timestamp (ISO format)

---

## Request/Response Cycle

### GET / (View Users)
```
┌─ Client
│
├─ GET /
│
└─► Router
    ├─ Matches: GET → [UserController, 'index']
    ├─ Calls: UserController->index($router)
    ├─ UserController fetches UserModel::all()
    ├─ Router->render('index', ['data' => users])
    └─ Browser receives HTML
```

### POST /signup (Create User)
```
┌─ Client (form submit)
│
├─ POST /signup with form data
│
└─► Router
    ├─ Matches: POST → [UserController, 'save']
    ├─ Calls: UserController->save($router)
    ├─ Create UserModel($_POST)
    ├─ Call UserModel->create()
    │
    ├─ Validate email/password/name
    ├─ Check for duplicates
    ├─ Hash password
    ├─ Insert into database
    │
    ├─ If success: render('index')
    ├─ If fail: render('signup', ['errors'])
    │
    └─ Browser receives HTML
```

---

## Key Design Patterns

### Dependency Injection
UserModel accepts optional PDO parameter for testing:
```php
// Production: uses real database
$user = new UserModel($data);

// Testing: uses mock database
$user = new UserModel($data, $mockPDO);
```

### Prepared Statements
All database queries use parameterized statements:
```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
```
Prevents SQL injection attacks.

### Singleton Pattern
Database connection is a singleton:
```php
$db = DBConnection::getInstance(); // Same instance always
```
Ensures single connection throughout request.

### Model Validation
Validation happens in model, controller just orchestrates:
```php
// Model owns validation logic
$user = new UserModel($_POST);
$errors = $user->create();  // Validates & inserts

// Controller handles flow
if (empty($errors)) {
    $router->render('index');
} else {
    $router->render('signup', ['errors']);
}
```

---

## Testing

27 comprehensive unit tests verify functionality:

**Run Tests:**
```bash
composer test
```

**Test Coverage:**
- UserModel validation (14 tests)
- UserController flow (13 tests)
- Database operations
- Error handling
- Edge cases

See `TESTING.md` and `TESTING_CHEATSHEET.md` for details.

---

## Form Submission

**Important:** Form inputs must have `name` attributes for POST data:

```html
<!-- ✓ Correct -->
<input name="email" type="email" />
<input name="password" type="password" />

<!-- ✗ Wrong (no name = no $_POST data) -->
<input id="email" type="email" />
```

Form values become available in `$_POST`:
```php
$_POST = [
    'email' => 'user@example.com',
    'password' => 'secret123',
    'full_name' => 'Jane Doe'
]
```

---

## Security Features

- ✅ **Password hashing** - Bcrypt with PASSWORD_BCRYPT algorithm
- ✅ **SQL injection prevention** - Prepared statements
- ✅ **Unique emails** - Database constraint prevents duplicates
- ✅ **Input validation** - Email format, password length
- ✅ **Type hints** - Ensures correct data types

---

## File Structure

```
auth/
├── public/
│   └── index.php              # Entry point, route registration
├── src/
│   ├── controller/
│   │   └── UserController.php # Request handlers
│   ├── data/
│   │   └── DBConnection.php   # Database singleton
│   ├── database/
│   │   └── auth.sqlite3       # SQLite database file
│   ├── http/
│   │   └── Router.php         # HTTP routing & view rendering
│   ├── model/
│   │   └── UserModel.php      # User data & validation
│   ├── scripts/
│   │   └── seed_users.sql     # Database seed data
│   └── views/
│       ├── index.php          # Users list page
│       ├── login.php          # Login form
│       ├── signup.php         # Registration form
│       └── includes/
│           ├── header.php     # Shared header
│           └── footer.php     # Shared footer
└── tests/
    ├── UserControllerTest.php # Controller tests
    └── UserModelTest.php      # Model tests
```

---

## Development Commands

```bash
# Start development server
composer dev

# Run all tests
composer test

# Run specific test
php vendor/bin/phpunit auth/tests/UserModelTest.php -v
```

---

## Routes

| Method | Path | Handler | Purpose |
|--------|------|---------|---------|
| GET | `/` | `index()` | Show all users |
| GET | `/signup` | render signup | Show signup form |
| POST | `/signup` | `save()` | Create new user |
| GET | `/login` | render login | Show login form |
| POST | `/login` | (incomplete) | Handle login |

---

## Next Steps

1. Implement login functionality (POST /login)
2. Add session management
3. Implement CSRF protection
4. Add password reset flow
5. Implement user roles/permissions
