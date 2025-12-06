# Quick Testing Reference

## Run Tests

```bash
# Run all tests
composer test

# Run specific test file
php vendor/bin/phpunit auth/tests/UserModelTest.php

# Run specific test method
php vendor/bin/phpunit --filter testValidatePassesWithValidData

# Verbose output
php vendor/bin/phpunit -v
```

## Test Structure Template

Every test follows **Arrange → Act → Assert**:

```php
public function testSomethingHappens(): void
{
    // ARRANGE: Set up the test data and mocks
    $mockPDO = $this->createMock(PDO::class);
    $userData = ['email' => 'test@example.com'];

    // ACT: Do the thing you're testing
    $user = new UserModel($userData, $mockPDO);
    $result = $user->validate();

    // ASSERT: Verify the result
    $this->assertTrue($result);
}
```

## Common Assertions Cheat Sheet

```php
// Equality & Type
$this->assertEquals($expected, $actual);
$this->assertNotEquals($expected, $actual);
$this->assertSame($expected, $actual);
$this->assertTrue($value);
$this->assertFalse($value);

// Collections
$this->assertEmpty($array);
$this->assertNotEmpty($array);
$this->assertCount(3, $array);
$this->assertContains('needle', $array);

// Objects
$this->assertInstanceOf(UserModel::class, $object);
$this->assertNull($value);
$this->assertNotNull($value);

// Exceptions
$this->expectException(PDOException::class);
$this->expectExceptionMessage('Connection failed');
```

## Mocking Pattern

```php
// 1. Create mock in setUp()
protected function setUp(): void
{
    $this->mockPDO = $this->createMock(PDO::class);
}

// 2. Configure mock behavior
$this->mockPDO->method('prepare')
    ->willReturn($this->mockStatement);

$this->mockStatement->method('execute')
    ->willReturn(true);

// 3. Pass mock to constructor
$user = new UserModel($data, $this->mockPDO);
```

## Test Naming Convention

- Test method name = `test` + `What` + `Condition` + `Result`
- Example: `testValidateFailsWhenEmailIsEmpty`
- Read naturally: "test validate fails when email is empty"

## Seeing What's Tested

```bash
# Generate coverage report (shows % of code tested)
php vendor/bin/phpunit --coverage-html coverage/

# Then open: coverage/index.html
```

## Key Lessons

1. **One test = One thing** - Don't test multiple behaviors in one test
2. **Mocks isolate** - Mock external dependencies (DB, API, etc.)
3. **Dependency injection** - Constructor accepts optional PDO for testing
4. **Test edge cases** - Empty strings, wrong formats, duplicates, etc.
5. **Clear naming** - Test names should explain what they test

## Real Tests vs Mock Tests

| Real DB Tests | Mocked Tests |
|---|---|
| Slower ⏱️ | Fast ⚡ |
| Need DB setup | No setup |
| Test actual queries | Test logic only |
| Integration tests | Unit tests |

**This project uses mocks** for unit tests to keep tests fast and isolated.
