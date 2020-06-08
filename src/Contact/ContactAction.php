<?php
namespace App\Contact;

use Framework\Renderer\RendererInterface;
use Framework\Response\RedirectResponse;
use Framework\Session\FlashService;
use Framework\Validator;
use Psr\Http\Message\ServerRequestInterface;

class ContactAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;
    /**
     * @var string
     */
    private $to;

    /**
     * @var FlashService
     */
    private $flashService;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(
        string $to,
        RendererInterface $renderer,
        FlashService $flashService,
        \Swift_Mailer $mailer
    ) {
    
        $this->renderer = $renderer;
        $this->to = $to;
        $this->flashService = $flashService;
        $this->mailer = $mailer;
    }

    /**
     * @param ServerRequestInterface $request
     * @return RedirectResponse|string
     */
    public function __invoke(ServerRequestInterface $request)
    {
        if ($request->getMethod() === 'GET') {
            return $this->renderer->render('@contact/contact');
        }
        $params = $request->getParsedBody();
        $validator = (new Validator($params))
            ->required('name', 'email', 'content')
            ->length('name', 5)
            ->email('email')
            ->length('content', 15);
        if ($validator->isValid()) {
            $this->flashService->success('Merci pour votre email');
            $message = new \Swift_Message('Formulaire de contact');
            $message->setBody($this->renderer->render('@contact/email/contact.text', $params));
            $message->addPart($this->renderer->render('@contact/email/contact.html', $params), 'text/html');
            $message->setTo($this->to);
            $message->setFrom($params['email']);
            $this->mailer->send($message);
            return new RedirectResponse((string)$request->getUri());
        } else {
            $this->flashService->error('Merci de corriger vos erreurs');
            $errors = $validator->getErrors();
            return $this->renderer->render('@contact/contact', compact('errors'));
        }
    }
}
