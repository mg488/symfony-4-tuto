<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class LocaleSubscriber implements EventSubscriberInterface
{
    private $defaultLocale;

    public function __construct($defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function onRequestEvent(RequestEvent $event)
    {
        $request = $event->getRequest();

        

        if (!$request->hasPreviousSession()) {
            return;
        }
        // try to see if the locale has been set as a _locale routing parameter
        // dd($locale=$request->query->get('_locale'));
        if ($locale = $request->query->get('_locale')) 
        {
            $request->setLocale($locale);
        } 
        else 
        {
            // if no explicit locale has been set on this request, use one from the session
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => [['onRequestEvent',20]]
        ];
    }
}
