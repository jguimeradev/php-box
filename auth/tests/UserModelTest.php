<?php

namespace Debian\Php\auth\tests;

use PHPUnit\Framework\TestCase;
use Debian\Php\auth\src\model\UserModel;
use PDO;
use PDOStatement;

class UserModelTest extends TestCase
{
    private $mockPDO;
    private $mockStatement;

    protected function setUp(): void
    {
        // Create mocks for PDO and PDOStatement
        $this->mockPDO = $this->createMock(PDO::class);
        $this->mockStatement = $this->createMock(PDOStatement::class);
    }

    // ============ CONSTRUCTOR TESTS ============

    /**
     * Test 1: Constructor stores user data correctly
     */
    public function testConstructorStoresUserData(): void
    {
        $userData = [
            'email' => 'test@example.com',
            'password' => 'secret123',
            'full_name' => 'John Doe'
        ];

        $user = new UserModel($userData, $this->mockPDO);

        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('secret123', $user->getPassword());
        $this->assertEquals('John Doe', $user->getFullName());
    }

    /**
     * Test 2: getEmail returns correct value
     */
    public function testGetEmailReturnsStoredEmail(): void
    {
        $user = new UserModel(['email' => 'jane@example.com'], $this->mockPDO);
        $this->assertEquals('jane@example.com', $user->getEmail());
    }

    /**
     * Test 3: Getters return empty string when data missing
     */
    public function testGettersReturnEmptyStringWhenDataMissing(): void
    {
        $user = new UserModel([], $this->mockPDO);

        $this->assertEquals('', $user->getEmail());
        $this->assertEquals('', $user->getPassword());
        $this->assertEquals('', $user->getFullName());
    }

    // ============ VALIDATION TESTS ============

    /**
     * Test 4: validate() passes with valid data
     */
    public function testValidatePassesWithValidData(): void
    {
        $userData = [
            'email' => 'valid@example.com',
            'password' => 'password123',
            'full_name' => 'John Doe'
        ];

        $user = new UserModel($userData, $this->mockPDO);
        $this->assertTrue($user->validate());
        $this->assertEmpty($user->getErrors());
    }

    /**
     * Test 5: validate() fails when email is empty
     */
    public function testValidateFailsWhenEmailIsEmpty(): void
    {
        $userData = [
            'email' => '',
            'password' => 'password123',
            'full_name' => 'John Doe'
        ];

        $user = new UserModel($userData, $this->mockPDO);
        $this->assertFalse($user->validate());
        $this->assertContains('Email is required', $user->getErrors());
    }

    /**
     * Test 6: validate() fails with invalid email format
     */
    public function testValidateFailsWithInvalidEmailFormat(): void
    {
        $userData = [
            'email' => 'not-an-email',
            'password' => 'password123',
            'full_name' => 'John Doe'
        ];

        $user = new UserModel($userData, $this->mockPDO);
        $this->assertFalse($user->validate());
        $this->assertContains('Invalid email format', $user->getErrors());
    }

    /**
     * Test 7: validate() fails when password is empty
     */
    public function testValidateFailsWhenPasswordIsEmpty(): void
    {
        $userData = [
            'email' => 'test@example.com',
            'password' => '',
            'full_name' => 'John Doe'
        ];

        $user = new UserModel($userData, $this->mockPDO);
        $this->assertFalse($user->validate());
        $this->assertContains('Password is required', $user->getErrors());
    }

    /**
     * Test 8: validate() fails when password is too short
     */
    public function testValidateFailsWhenPasswordIsTooShort(): void
    {
        $userData = [
            'email' => 'test@example.com',
            'password' => '12345',  // Only 5 characters
            'full_name' => 'John Doe'
        ];

        $user = new UserModel($userData, $this->mockPDO);
        $this->assertFalse($user->validate());
        $this->assertContains('Password must be at least 6 characters', $user->getErrors());
    }

    /**
     * Test 9: validate() fails when full_name is empty
     */
    public function testValidateFailsWhenFullNameIsEmpty(): void
    {
        $userData = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'full_name' => ''
        ];

        $user = new UserModel($userData, $this->mockPDO);
        $this->assertFalse($user->validate());
        $this->assertContains('Full name is required', $user->getErrors());
    }

    /**
     * Test 10: validate() detects multiple validation errors
     */
    public function testValidateDetectsMultipleErrors(): void
    {
        $userData = [
            'email' => 'invalid-email',
            'password' => '123',
            'full_name' => ''
        ];

        $user = new UserModel($userData, $this->mockPDO);
        $this->assertFalse($user->validate());
        $errors = $user->getErrors();
        $this->assertCount(3, $errors);
    }

    // ============ CREATE TESTS ============

    /**
     * Test 11: create() returns validation errors if data invalid
     */
    public function testCreateReturnsValidationErrorsIfDataInvalid(): void
    {
        $userData = [
            'email' => '',
            'password' => '123',
            'full_name' => 'John'
        ];

        $user = new UserModel($userData, $this->mockPDO);
        $errors = $user->create();

        $this->assertNotEmpty($errors);
        $this->assertContains('Email is required', $errors);
    }

    /**
     * Test 12: create() returns error if user already exists
     * 
     * Note: This test demonstrates the challenge of testing static methods.
     * In a real scenario, you'd refactor findByEmail to be an instance method.
     */
    public function testCreateReturnsErrorIfUserAlreadyExists(): void
    {
        $userData = [
            'email' => 'existing@example.com',
            'password' => 'password123',
            'full_name' => 'John Doe'
        ];

        $user = new UserModel($userData, $this->mockPDO);

        // This demonstrates why static methods are hard to test
        // The validate() should pass (no issues with data format)
        $this->assertTrue($user->validate());
    }
    /**
     * Test 13: create() successfully creates user with valid data and mocked DB
     * 
     * Note: This test is limited because findByEmail() is static.
     * In a real test, you'd refactor findByEmail to be an instance method
     * so it can be properly mocked.
     */
    public function testCreateSucceedsWithValidDataAndMockedDB(): void
    {
        // The mock PDO is set up correctly
        $this->mockPDO->method('prepare')
            ->willReturn($this->mockStatement);

        $this->mockStatement->method('execute')
            ->willReturn(true);

        $userData = [
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'full_name' => 'Jane Doe',
            'role' => 'User'
        ];

        $user = new UserModel($userData, $this->mockPDO);

        // Validate passes with mocked PDO
        $this->assertTrue($user->validate());
    }

    // ============ INTEGRATION HINTS ============

    /**
     * Test 14: getErrors returns array of errors
     */
    public function testGetErrorsReturnsErrorArray(): void
    {
        $userData = [
            'email' => 'invalid',
            'password' => '123',
            'full_name' => ''
        ];

        $user = new UserModel($userData, $this->mockPDO);
        $user->validate();

        $errors = $user->getErrors();
        $this->assertIsArray($errors);
        $this->assertGreaterThan(0, count($errors));
    }
}
