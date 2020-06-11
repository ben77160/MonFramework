<?php
namespace App\Auth\Mailer;

use Framework\Renderer\RendererInterface;

class PasswordResetMailer
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var RendererInterface
     */
    private $renderer;
    /**
     * @var string
     */
    private $from;

    public function __construct(\Swift_Mailer $mailer, RendererInterface $renderer, string $from)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
        $this->from = $from;
    }

    public function send(string $to, array $params)
    {
        $message = new \Swift_Message(
            'Réinitialisation de votre mot de passe',
            $this->renderer->render('@auth/email/password.text', $params)
        );
        $message->addPart($this->renderer->render('@auth/email/password.html', $params), 'text/html');
        $message->setTo($to);
        $message->setFrom($this->from);
        $this->mailer->send($message);
    }
}
