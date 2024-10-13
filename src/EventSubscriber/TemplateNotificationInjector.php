<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use App\Service\NotificationService;
use Psr\Log\LoggerInterface;

class TemplateNotificationInjector implements EventSubscriberInterface
{
    private NotificationService $notificationService;
    private LoggerInterface $logger;

    public function __construct(NotificationService $notificationService,LoggerInterface $logger)
    {
        $this->notificationService = $notificationService;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }
    
        $notifications = $this->notificationService->getUnreadNotifications();
        $event->getRequest()->attributes->set('unread_notifications', $notifications);
        // Ajoutez un log ici pour confirmer
        $this->logger->debug('Notifications set for the request.');
    }
    
    
}
