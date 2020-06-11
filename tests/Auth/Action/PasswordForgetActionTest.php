<?php

namespace Tests\App\Auth\Action;

use App\Auth\Action\PasswordForgetAction;
use App\Auth\Mailer\PasswordResetMailer;
use App\Auth\User;
use App\Auth\UserTable;
use Framework\Database\NoRecordException;
use Framework\Renderer\RendererInterface;
use Framework\Session\FlashService;
use Prophecy\Argument;
use Tests\ActionTestCase;

class PasswordForgetActionTest extends ActionTestCase
{

    private $renderer;
    private $action;
    private $userTable;
    private $mailer;

    public function setUp()
    {
        $this->renderer = $this->prophesize(RendererInterface::class);
        $this->userTable = $this->prophesize(UserTable::class);
        $this->mailer = $this->prophesize(PasswordResetMailer::class);
        $this->action = new PasswordForgetAction(
            $this->renderer->reveal(),
            $this->userTable->reveal(),
            $this->mailer->reveal(),
            $this->prophesize(FlashService::class)->reveal()
        );
    }

    public function testEmailInvalid()
    {
        $request = $this->makeRequest('/demo', ['email' => 'azezaez']);
        $this->renderer
            ->render(Argument::type('string'), Argument::withEntry('errors', Argument::withKey('email')))
            ->shouldBeCalled()
            ->willReturnArgument();
        $response = call_user_func($this->action, $request);
        $this->assertEquals('@auth/password', $response);
    }

    public function testEmailDontExists()
    {
        $request = $this->makeRequest('/demo', ['email' => 'john@doe.fr']);
        $this->userTable->findBy('email', 'john@doe.fr')->willThrow(new NoRecordException());
        $this->renderer
            ->render(Argument::type('string'), Argument::withEntry('errors', Argument::withKey('email')))
            ->shouldBeCalled()
            ->willReturnArgument();
        $response = call_user_func($this->action, $request);
        $this->assertEquals('@auth/password', $response);
    }

    public function testWithGoodEmail()
    {
        $user = new User();
        $user->id = 3;
        $user->email = 'john@doe.fr';
        $token = "fake";
        $request = $this->makeRequest('/demo', ['email' => $user->email]);
        $this->userTable->findBy('email', 'john@doe.fr')->willReturn($user);
        $this->userTable->resetPassword(3)->willReturn($token);
        $this->mailer->send($user->email, [
            'id' => $user->id,
            'token' => $token
        ])->shouldBeCalled();
        $this->renderer->render()->shouldNotBeCalled();
        $response = call_user_func($this->action, $request);
        $this->assertRedirect($response, '/demo');
    }
}
