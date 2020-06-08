<?php
return [
    'contact.to' => \DI\get('mail.to'),
    \App\Contact\ContactAction::class => \DI\object()->constructorParameter('to', \DI\get('contact.to'))
];
