<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class LocaleSubscriber implements EventSubscriberInterface
{
    public function onRequestEvent(RequestEvent $event)
    {
        // ...
    }

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => 'onRequestEvent',
        ];
    }
}
