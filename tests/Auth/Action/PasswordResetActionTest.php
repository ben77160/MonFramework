<?php

namespace Tests\App\Auth\Action;

use App\Auth\Action\PasswordForgetAction;
use App\Auth\Action\PasswordResetAction;
use App\Auth\User;
use App\Auth\UserTable;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;
use Prophecy\Argument;
use Tests\ActionTestCase;

class PasswordResetActionTest extends ActionTestCase
{

    private $renderer;
    private $action;
    private $userTable;

    public function setUp()
    {
        $this->renderer = $this->prophesize(RendererInterface::class);
        $this->userTable = $this->prophesize(UserTable::class);
        $router = $this->prophesize(Router::class);
        $router->generateUri(Argument::cetera())->willReturnArgument();
        $this->renderer->render(Argument::cetera())->willReturnArgument();
        $this->action = new PasswordResetAction(
            $this->renderer->reveal(),
            $this->userTable->reveal(),
            $this->prophesize(FlashService::class)->reveal(),
            $router->reveal()
        );
    }

    private function makeUser()
    {
        $user = new User();
        $user->setId(3);
        $user->setPasswordReset("fake");
        $user->setPasswordResetAt(new \DateTime());
        return $user;
    }

    public function testWithBadToken()
    {
        $user = $this->makeUser();
        $request = $this->makeRequest('/da')
            ->withAttribute('id', $user->getId())
            ->withAttribute('token', $user->getPasswordReset() . 'aze');
        $this->userTable->find($user->getId())->willReturn($user);
        $response = call_user_func($this->action, $request);
        $this->assertRedirect($response, 'auth.password');
    }

    public function testWithExpiredToken()
    {
        $user = $this->makeUser();
        $user->setPasswordResetAt((new \DateTime())->sub(new \DateInterval('PT15M')));
        $request = $this->makeRequest('/da')
            ->withAttribute('id', $user->getId())
            ->withAttribute('token', $user->getPasswordReset());
        $this->userTable->find($user->getId())->willReturn($user);
        $response = call_user_func($this->action, $request);
        $this->assertRedirect($response, 'auth.password');
    }

    public function testWithValidToken()
    {
        $user = $this->makeUser();
        $user->setPasswordResetAt((new \DateTime())->sub(new \DateInterval('PT5M')));
        $request = $this->makeRequest('/da')
            ->withAttribute('id', $user->getId())
            ->withAttribute('token', $user->getPasswordReset());
        $this->userTable->find($user->getId())->willReturn($user);
        $response = call_user_func($this->action, $request);
        $this->assertEquals($response, '@auth/reset');
    }

    public function testPostWithBadPassword()
    {
        $user = $this->makeUser();
        $user->setPasswordResetAt((new \DateTime())->sub(new \DateInterval('PT5M')));
        $request = $this->makeRequest('/da', ['password' => 'azeaze', 'password_confirm' => 'azeazeaze'])
            ->withAttribute('id', $user->getId())
            ->withAttribute('token', $user->getPasswordReset());
        $this->userTable->find($user->getId())->willReturn($user);
        $this->renderer
            ->render(Argument::type('string'), Argument::withKey('errors'))
            ->shouldBeCalled()
            ->willReturnArgument();
        $response = call_user_func($this->action, $request);
        $this->assertEquals($response, '@auth/reset');
    }

    public function testPostWithGoodPassword()
    {
        $user = $this->makeUser();
        $user->setPasswordResetAt((new \DateTime())->sub(new \DateInterval('PT5M')));
        $request = $this->makeRequest('/da', ['password' => 'azeaze', 'password_confirm' => 'azeaze'])
            ->withAttribute('id', $user->getId())
            ->withAttribute('token', $user->getPasswordReset());
        $this->userTable->find($user->getId())->willReturn($user);
        $this->userTable->updatePassword($user->getId(), 'azeaze')->shouldBeCalled();
        $response = call_user_func($this->action, $request);
        $this->assertRedirect($response, 'auth.login');
    }
}
