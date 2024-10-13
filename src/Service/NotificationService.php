<?php
namespace App\Service;

use App\Repository\NotificationRepository;
use Symfony\Bundle\SecurityBundle\Security;

class NotificationService
{
    private $notificationRepository;
    private $security;

    public function __construct(NotificationRepository $notificationRepository, Security $security)
    {
        $this->notificationRepository = $notificationRepository;
        $this->security = $security;
    }

    public function getUnreadNotifications()
    {
        $user = $this->security->getUser();
        if ($user) {
            $unreadNotifications = $this->notificationRepository->findBy(['user' => $user, 'seen' => false]);
        }
        return [];
    }
}
