<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Notification;

class NotificationController extends AbstractController
{
    private NotificationRepository $notificationRepository;
    private EntityManagerInterface $entityManager; 

    public function __construct(NotificationRepository $notificationRepository, EntityManagerInterface $entityManager)
    {
        $this->notificationRepository = $notificationRepository;
        $this->entityManager = $entityManager; 
    }

    #[Route('/notifications', name: 'user_notifications')]
    public function notifications(): Response
    {
        $user = $this->getUser();
        $unreadNotifications = $this->notificationRepository->findBy(['user' => $user, 'seen' => false]);
        return $this->render('index.html.twig', [
            'unread_notifications' => $unreadNotifications ?? []
        ]);
    }

    #[Route('/notifications/{id}/read', name: 'mark_notification_read')]
    public function read(Notification $notification): Response
    {
        if ($this->getUser() !== $notification->getUser()) {
            throw $this->createAccessDeniedException('You are not allowed to modify this notification.');
        }

        $notification->setSeen(true);
        $this->entityManager->flush(); 

        return $this->redirectToRoute('app_view_article', [
            'id' => $notification->getArticleId(),
        ]);
    }
}
