# Controller Unit Tests - Summary

## ✅ All Tests Passing

- **UserModelTest**: 14 tests ✓
- **UserControllerTest**: 13 tests ✓
- **Total**: 27 tests, 40 assertions

Run with: `composer test`

## UserControllerTest Coverage

Tests verify the controller properly:

### Index Action
1. **testIndexFetchesAllUsers** - Verifies `index()` calls render
2. **testIndexRendersIndexTemplate** - Confirms correct template ('index')
3. **testIndexPassesDataToRender** - Ensures data array is passed
4. **testIndexCallsRenderExactlyOnce** - Verifies single render call
5. **testIndexReturnsVoid** - Confirms return type

### Save Action (Success Path)
6. **testSaveCreatesUserModelWithPostData** - Model created with POST
7. **testSaveRendersIndexOnSuccess** - Renders 'index' on valid data
8. **testSaveWithValidDataRendersIndex** - Valid data → index template

### Save Action (Error Path)
9. **testSaveRendersSignupWithErrorsOnValidationFailure** - Shows signup on errors
10. **testSavePassesErrorsToSignupTemplate** - Errors passed to view

### Edge Cases
11. **testSaveHandlesMissingPostData** - Gracefully handles no POST
12. **testSaveReturnsVoid** - Correct return type
13. **testControllerWorksWithRouterMock** - Mock integration works

## Testing Strategy Used

### Mocking the Router
```php
$this->mockRouter = $this->createMock(Router::class);

// Verify render was called
$this->mockRouter->expects($this->once())
    ->method('render')
    ->with('index');

$this->controller->index($this->mockRouter);
```

### Testing with Global $_POST
```php
$_POST = [
    'email' => 'test@example.com',
    'password' => 'password123',
    'full_name' => 'John Doe'
];

$this->controller->save($this->mockRouter);

unset($_POST);  // Clean up after test
```

### Verifying Method Calls
```php
// Verify render called exactly once
$this->mockRouter->expects($this->exactly(1))
    ->method('render');

// Verify render called at least once
$this->mockRouter->expects($this->atLeastOnce())
    ->method('render');

// Verify render never called
$this->mockRouter->expects($this->never())
    ->method('render');
```

## Bug Found and Fixed

While writing tests, I found a bug in the controller:

**Before (Buggy)**:
```php
public function save(Router $router)
{
    if (isset($_POST)) {
        $user = new UserModel($_POST);
        $errors = $user->create();
        if (empty($errors)) {
            $router->render('index', ['data' => UserModel::all()]);
        } else {
            $router->render('signup', ['errors' => $errors]);
        }
        
        // BUG: This always renders regardless of success/failure!
        $router->render('index', null);
    }
}
```

**After (Fixed)**:
```php
public function save(Router $router)
{
    if (isset($_POST)) {
        $user = new UserModel($_POST);
        $errors = $user->create();
        if (empty($errors)) {
            $router->render('index', ['data' => UserModel::all()]);
        } else {
            $router->render('signup', ['errors' => $errors]);
        }
    }
}
```

**This is exactly why testing is valuable!** Tests caught a real bug that would have caused issues in production.

## Key Assertions Used

```php
// Verify method called and with what arguments
$this->mockRouter->expects($this->once())
    ->method('render')
    ->with('index', $this->isType('array'));

// Verify callback condition
->with(
    $this->callback(function($params) {
        return is_array($params) && isset($params['data']);
    })
);

// Logical OR for multiple possible values
->with(
    $this->logicalOr('index', 'signup'),
    $this->isType('array')
);

// Verify call count
$this->exactly(1)
$this->atLeastOnce()
$this->never()
```

## Limitations & Future Improvements

### Current Limitation
The `create()` method calls static `findByEmail()`, which isn't easily mocked. This means:
- Tests can't fully isolate the create() flow
- Tests rely on actual database for duplicate checking

### How to Improve
Refactor `findByEmail()` to be an instance method:

```php
// Instead of
public static function findByEmail($email)
{
    $pdo = self::connectDB()->getConnection();
    // ...
}

// Do this
public function findByEmail($email)
{
    $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

// Then in create()
public function create(): array
{
    if (!$this->validate()) {
        return $this->errors;
    }

    // Now we can mock this call
    $existing = $this->findByEmail($this->args['email']);
    
    // ... rest of logic
}
```

This way, `findByEmail()` uses `$this->pdo` (which we already mock), making it fully testable.

## Running Tests with Coverage

See how much of your code is tested:

```bash
php vendor/bin/phpunit auth/tests/ --coverage-html coverage/
```

Then open `coverage/index.html` in a browser to see:
- % of code tested
- Which lines are covered
- Which lines are missing tests

## Next: Test Router

The Router class also deserves unit tests:

```php
public function testGetRegistersRoute(): void
{
    $router = new Router();
    $handler = fn($r) => "test";
    $router->get('/users', $handler);
    
    // Verify route is stored
}

public function testDispatchCallsCorrectHandler(): void
{
    // Test dispatch logic
}

public function testDispatch404OnNotFound(): void
{
    // Test 404 handling
}
```

---

**TL;DR**: Tests verify the controller correctly orchestrates the model and router. We caught a real bug! 🎉
