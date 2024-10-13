<?php
namespace App\EventListener;

use App\Service\NotificationService;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class NotificationListener
{
    private $notificationService;
    private $twig;

    public function __construct(NotificationService $notificationService, Environment $twig)
    {
        $this->notificationService = $notificationService;
        $this->twig = $twig;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $notifications = $this->notificationService->getUnreadNotifications();
        $this->twig->addGlobal('unread_notifications', $notifications);
    }
}
