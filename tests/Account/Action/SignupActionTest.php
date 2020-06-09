<?php
namespace Tests\App\Account\Action;

use App\Account\Action\SignupAction;
use App\Auth\DatabaseAuth;
use App\Auth\User;
use App\Auth\UserTable;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\ActionTestCase;

class SignupActionTest extends ActionTestCase
{

    private $action;

    /**
     * @var ObjectProphecy
     */
    private $renderer;

    /**
     * @var ObjectProphecy
     */
    private $userTable;

    /**
     * @var ObjectProphecy
     */
    private $flashService;

    public function setUp()
    {
        // UserTable
        $this->userTable = $this->prophesize(UserTable::class);
        $pdo = $this->prophesize(\PDO::class);
        $statement = $this->getMockBuilder(\PDOStatement::class)->getMock();
        $statement->expects($this->any())->method('fetchColumn')->willReturn(false);
        $pdo->prepare(Argument::any())->willReturn($statement);
        $pdo->lastInsertId()->willReturn(3);
        $this->userTable->getTable()->willReturn('fake');
        $this->userTable->getPdo()->willReturn($pdo->reveal());
        // Renderer
        $this->renderer = $this->prophesize(RendererInterface::class);
        $this->renderer->render(Argument::any(), Argument::any())->willReturn('');
        // Router
        $this->router = $this->prophesize(Router::class);
        $this->router->generateUri(Argument::any())->will(function ($args) {
            return $args[0];
        });
        // Flash
        $this->flashService = $this->prophesize(FlashService::class);
        // Auth
        $this->auth = $this->prophesize(DatabaseAuth::class);
        $this->action = new SignupAction(
            $this->renderer->reveal(),
            $this->userTable->reveal(),
            $this->router->reveal(),
            $this->auth->reveal(),
            $this->flashService->reveal()
        );
    }

    public function testGet()
    {
        call_user_func($this->action, $this->makeRequest());
        $this->renderer->render('@account/signup')->shouldHaveBeenCalled();
    }

    public function testPostInvalid()
    {
        call_user_func($this->action, $this->makeRequest('/demo', [
            'username' => 'John Doe',
            'email'    => 'azeaze',
            'password' => '0000',
            'password_confirm' => '000'
        ]));
        $this->renderer->render('@account/signup', Argument::that(function ($params) {
            $this->assertArrayHasKey('errors', $params);
            $this->assertEquals(['email', 'password'], array_keys($params['errors']));
            return true;
        }))->shouldHaveBeenCalled();
    }

    public function testPostWithNoPassword()
    {
        call_user_func($this->action, $this->makeRequest('/demo', [
            'username' => 'John Doe',
            'email'    => 'azeaze',
            'password' => '',
            'password_confirm' => ''
        ]));
        $this->renderer->render('@account/signup', Argument::that(function ($params) {
            $this->assertArrayHasKey('errors', $params);
            $this->assertEquals(['email', 'password'], array_keys($params['errors']));
            return true;
        }))->shouldHaveBeenCalled();
    }

    public function testPostValid()
    {
        $this->userTable->insert(Argument::that(function (array $userParams) {
            $this->assertArraySubset([
                'username' => 'John Doe',
                'email' => 'john@doe.fr'
            ], $userParams);
            $this->assertTrue(password_verify('0000', $userParams['password']));
            return true;
        }))->shouldBeCalled();
        $this->auth->setUser(Argument::that(function (User $user) {
            $this->assertEquals('John Doe', $user->username);
            $this->assertEquals('john@doe.fr', $user->email);
            $this->assertEquals(3, $user->id);
            return true;
        }))->shouldBeCalled();
        $this->renderer->render()->shouldNotBeCalled();
        $this->flashService->success(Argument::type('string'))->shouldBeCalled();
        $response = call_user_func($this->action, $this->makeRequest('/demo', [
            'username' => 'John Doe',
            'email'    => 'john@doe.fr',
            'password' => '0000',
            'password_confirm' => '0000'
        ]));
        $this->assertRedirect($response, 'account');
    }
}
