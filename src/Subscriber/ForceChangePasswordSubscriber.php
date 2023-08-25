<?php

namespace App\Subscriber;

use App\Entity\Main\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ForceChangePasswordSubscriber implements EventSubscriberInterface
{
    public const RESET_ROUTE = 'app_reset';

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private Security $security
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        /** @var User|null $user */
        $user = $this->security->getUser();

        if ($user && $user->getForceChangePassword()) {
            $currentRoute = $event->getRequest()->attributes->get('_route');

            if ($currentRoute !== self::RESET_ROUTE) {
                $url = $this->urlGenerator->generate('app_reset');
                $event->setController(function () use ($url) {
                    return new RedirectResponse($url);
                });
            }
        }
    }
}