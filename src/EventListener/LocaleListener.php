<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class LocaleListener implements EventSubscriberInterface
{
    private string $defaultLocale;
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        $this->defaultLocale = $parameterBag->get('kernel.default_locale');
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        
        // Get enabled locales from translation configuration
        $enabledLocales = $this->parameterBag->get('kernel.enabled_locales');

        if ($request->hasSession()) {
            $sessionLocale = $request->getSession()->get('_locale', $this->defaultLocale);
            
            // Enforce enabled locales
            $locale = in_array($sessionLocale, $enabledLocales) ? $sessionLocale : $this->defaultLocale;
            
            $request->setLocale($locale);
            $request->getSession()->set('_locale', $locale);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}
