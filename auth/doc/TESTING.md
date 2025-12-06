# Unit Testing Guide for php-box

## Quick Start

### Install PHPUnit
```bash
composer install
```

### Run Tests
```bash
composer test
# or directly:
cd auth && phpunit
```

### Run Specific Test
```bash
cd auth && phpunit tests/UserModelTest.php
```

### Run Specific Test Method
```bash
cd auth && phpunit --filter testValidatePassesWithValidData tests/UserModelTest.php
```

## Key Testing Concepts

### 1. **Unit Tests - Test One Thing**
Each test method should test **one specific behavior**. Look at the naming pattern:

```php
public function testValidateFailsWhenEmailIsEmpty(): void
```

This clearly describes:
- **What**: Validate method
- **Condition**: Email is empty
- **Result**: Should fail

### 2. **Arrange → Act → Assert Pattern**

```php
public function testValidatePassesWithValidData(): void
{
    // ARRANGE: Set up test data
    $userData = [
        'email' => 'valid@example.com',
        'password' => 'password123',
        'full_name' => 'John Doe'
    ];

    // ACT: Execute the method being tested
    $user = new UserModel($userData, $this->mockPDO);
    $result = $user->validate();

    // ASSERT: Verify the result
    $this->assertTrue($result);
    $this->assertEmpty($user->getErrors());
}
```

### 3. **Mocking Dependencies**

When a class depends on a database (PDO), we mock it to avoid database calls:

```php
protected function setUp(): void
{
    // Create a fake PDO object
    $this->mockPDO = $this->createMock(PDO::class);
    
    // Create a fake Statement object
    $this->mockStatement = $this->createMock(PDOStatement::class);
}

public function testCreateSucceedsWithValidData(): void
{
    // Tell the mock PDO what to return
    $this->mockPDO->method('prepare')
        ->willReturn($this->mockStatement);

    $this->mockStatement->method('execute')
        ->willReturn(true);

    // Now use mocked PDO instead of real database
    $user = new UserModel($userData, $this->mockPDO);
    $errors = $user->create();

    $this->assertEmpty($errors);
}
```

**Why mock?**
- ✅ Tests run fast (no I/O)
- ✅ Tests are isolated (no database needed)
- ✅ You can test error scenarios easily
- ✅ Tests don't affect real data

### 4. **Dependency Injection for Testing**

Notice how `UserModel` now accepts an optional `$pdo` parameter:

```php
public function __construct(public array $args = [], PDO $pdo = null)
{
    // Use injected PDO, or fall back to singleton
    $this->pdo = $pdo ?? self::connectDB()->getConnection();
}
```

This allows:
- **Production**: `new UserModel($data)` - uses real DB
- **Testing**: `new UserModel($data, $mockPDO)` - uses mock

### 5. **Common Assertions**

```php
// Test equality
$this->assertEquals('expected', $actual);

// Test boolean
$this->assertTrue($result);
$this->assertFalse($result);

// Test arrays
$this->assertEmpty($errors);
$this->assertNotEmpty($errors);
$this->assertCount(3, $errors);
$this->assertContains('error message', $errors);

// Test exceptions
$this->expectException(PDOException::class);
```

## Test Coverage Map

| Test | Purpose | Pattern |
|------|---------|---------|
| Constructor | Stores data correctly | Basic getter test |
| Validation - Email | Validates email format | Negative test |
| Validation - Password | Enforces min length | Boundary test |
| Create - Invalid | Returns errors on bad input | Error handling |
| Create - Duplicate | Rejects existing emails | Database mock |
| Create - Success | Inserts successfully | Full flow mock |

## How to Add More Tests

### Step 1: Identify what to test
```php
public function update($id, $data)
```

### Step 2: Write test names for each scenario
- `testUpdateFailsWithInvalidId`
- `testUpdateFailsWhenDataInvalid`
- `testUpdateSucceedsWithValidData`

### Step 3: Implement tests
```php
public function testUpdateSucceedsWithValidData(): void
{
    // Mock the prepare and execute
    $this->mockStatement->method('execute')->willReturn(true);
    $this->mockPDO->method('prepare')->willReturn($this->mockStatement);

    $user = new UserModel(['id' => 1], $this->mockPDO);
    $result = $user->update(['email' => 'new@example.com']);

    $this->assertTrue($result);
}
```

## Running Tests with Coverage

See which lines of code are tested:

```bash
cd auth && phpunit --coverage-html coverage/
```

Then open `coverage/index.html` in a browser.

## Next: Test-Driven Development (TDD)

Once comfortable with testing, try **TDD**:
1. Write the test FIRST (it fails)
2. Write minimal code to make it pass
3. Refactor to improve code

Example:
```php
// Test FIRST - this fails because method doesn't exist
public function testUpdateReturnsUpdatedUser(): void
{
    $user = new UserModel(['id' => 1], $this->mockPDO);
    $result = $user->update(['email' => 'new@example.com']);
    $this->assertNotNull($result);
}

// Then implement update() to make test pass
public function update($data)
{
    // ... implementation
    return $this;
}
```

## Learn More

- PHPUnit docs: https://phpunit.de/documentation.html
- Mocking guide: https://phpunit.de/manual/current/en/test-doubles.html
- TDD guide: https://en.wikipedia.org/wiki/Test-driven_development
