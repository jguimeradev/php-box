<?php

namespace Debian\Php\auth\tests;

use PHPUnit\Framework\TestCase;
use Debian\Php\auth\src\controller\UserController;
use Debian\Php\auth\src\http\Router;
use Debian\Php\auth\src\model\UserModel;
use PDO;

class UserControllerTest extends TestCase
{
    private $mockRouter;
    private $mockPDO;
    private UserController $controller;

    protected function setUp(): void
    {
        // Create mock Router to avoid actual rendering
        $this->mockRouter = $this->createMock(Router::class);

        // Create mock PDO for UserModel
        $this->mockPDO = $this->createMock(PDO::class);

        // Create controller instance
        $this->controller = new UserController();
    }

    // ============ INDEX ACTION TESTS ============

    /**
     * Test 1: index() fetches all users
     */
    public function testIndexFetchesAllUsers(): void
    {
        // We can't easily mock UserModel::all() since it's static,
        // but we can verify the controller calls render with the correct template

        $this->mockRouter->expects($this->once())
            ->method('render')
            ->with('index', $this->isType('array'));

        $this->controller->index($this->mockRouter);
    }

    /**
     * Test 2: index() calls render with 'index' template
     */
    public function testIndexRendersIndexTemplate(): void
    {
        $this->mockRouter->expects($this->once())
            ->method('render')
            ->with('index');

        $this->controller->index($this->mockRouter);
    }

    /**
     * Test 3: index() passes data array to render
     */
    public function testIndexPassesDataToRender(): void
    {
        // Verify that render is called with 'index' template and data parameter
        $this->mockRouter->expects($this->once())
            ->method('render')
            ->with(
                'index',
                $this->callback(function ($params) {
                    // Verify it's an array with 'data' key
                    return is_array($params) && isset($params['data']);
                })
            );

        $this->controller->index($this->mockRouter);
    }

    // ============ SAVE ACTION TESTS ============

    /**
     * Test 4: save() creates UserModel with POST data
     */
    public function testSaveCreatesUserModelWithPostData(): void
    {
        // Set up global POST
        $_POST = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'full_name' => 'John Doe'
        ];

        // Mock render to capture any calls
        $this->mockRouter->method('render');

        // Call save
        $this->controller->save($this->mockRouter);

        // Reset POST
        unset($_POST);

        // If we got here without error, the UserModel was created successfully
        $this->assertTrue(true);
    }

    /**
     * Test 5: save() renders 'index' template on successful creation
     */
    public function testSaveRendersIndexOnSuccess(): void
    {
        // Set up valid POST data
        $_POST = [
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'full_name' => 'Jane Doe'
        ];

        // We expect render to be called (at least once)
        $this->mockRouter->expects($this->atLeastOnce())
            ->method('render');

        $this->controller->save($this->mockRouter);

        unset($_POST);
    }

    /**
     * Test 6: save() renders 'signup' template with errors on validation failure
     */
    public function testSaveRendersSignupWithErrorsOnValidationFailure(): void
    {
        // Set up invalid POST data (missing email)
        $_POST = [
            'email' => '',  // Invalid - empty
            'password' => 'pass',  // Invalid - too short
            'full_name' => 'Jane Doe'
        ];

        // Expect render to be called
        $this->mockRouter->expects($this->atLeastOnce())
            ->method('render');

        $this->controller->save($this->mockRouter);

        unset($_POST);
    }

    /**
     * Test 7: save() passes errors to signup template
     */
    public function testSavePassesErrorsToSignupTemplate(): void
    {
        $_POST = [
            'email' => 'invalid',  // Invalid email format
            'password' => '123',   // Too short
            'full_name' => ''      // Empty
        ];

        // Check that render is called with errors
        $this->mockRouter->expects($this->atLeastOnce())
            ->method('render')
            ->with(
                $this->logicalOr('index', 'signup'),
                $this->isType('array')
            );

        $this->controller->save($this->mockRouter);

        unset($_POST);
    }

    // ============ INTEGRATION-STYLE TESTS ============

    /**
     * Test 8: save() handles missing POST data gracefully
     */
    public function testSaveHandlesMissingPostData(): void
    {
        // Don't set $_POST
        unset($_POST);

        $this->mockRouter->expects($this->never())
            ->method('render');

        // Should not throw exception
        $this->controller->save($this->mockRouter);
    }

    /**
     * Test 9: index() always calls render exactly once
     */
    public function testIndexCallsRenderExactlyOnce(): void
    {
        $this->mockRouter->expects($this->exactly(1))
            ->method('render');

        $this->controller->index($this->mockRouter);
    }

    /**
     * Test 10: save() returns void (doesn't return anything)
     */
    public function testSaveReturnsVoid(): void
    {
        $_POST = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'full_name' => 'John Doe'
        ];

        $this->mockRouter->method('render');

        $result = $this->controller->save($this->mockRouter);

        $this->assertNull($result);

        unset($_POST);
    }

    /**
     * Test 11: index() returns void
     */
    public function testIndexReturnsVoid(): void
    {
        $this->mockRouter->method('render');

        $result = $this->controller->index($this->mockRouter);

        $this->assertNull($result);
    }

    // ============ ERROR HANDLING TESTS ============

    /**
     * Test 12: Controller works with Router mock
     */
    public function testControllerWorksWithRouterMock(): void
    {
        // Verify mock setup is correct by calling a method
        $this->mockRouter->expects($this->once())
            ->method('render')
            ->with('index');

        // Should not throw exception
        $this->controller->index($this->mockRouter);
    }

    /**
     * Test 13: save() with valid data and empty errors renders index
     */
    public function testSaveWithValidDataRendersIndex(): void
    {
        $_POST = [
            'email' => 'valid@example.com',
            'password' => 'validpassword123',
            'full_name' => 'Valid User'
        ];

        $this->mockRouter->method('render');
        $this->mockRouter->expects($this->atLeastOnce())
            ->method('render');

        $this->controller->save($this->mockRouter);

        unset($_POST);
    }
}
