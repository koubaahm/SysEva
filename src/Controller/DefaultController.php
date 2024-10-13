<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Notification;
use App\Entity\Article;
use App\Entity\Evaluation;


class DefaultController extends AbstractController 
{
    private NotificationRepository $notificationRepository;
    private EntityManagerInterface $entityManager; 

    public function __construct(NotificationRepository $notificationRepository, EntityManagerInterface $entityManager)
    {
        $this->notificationRepository = $notificationRepository;
        $this->entityManager = $entityManager; 
    }

    #[Route('/', name:'index')]
    public function index(): Response 
    {        
        $user = $this->getUser();
        $unreadNotifications = $this->notificationRepository->findBy(['user' => $user, 'seen' => false]);
        
        return $this->render('/index.html.twig', [
            'unread_notifications' => $unreadNotifications ?? []
        ]);
    }

    #[Route('/Mysection', name:'MySection')]
    public function mySection(): Response{
        $usersRoles = $this->getUser()->getRoles();
        $isSectionEditor = in_array('ROLE_SECTION_EDITOR', $usersRoles, true);
        if ($isSectionEditor) {
            return $this->render('/section/Mysection.html.twig');
        } else {
            return $this->redirectToRoute('nom_de_votre_route_vers_la_page_d_erreur');
        }
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

        return $this->redirectToRoute('user_notifications');
    }

    #[Route('/getStats', name: 'get_stats')]
    public function getStats(): Response
    {
        $articles = $this->entityManager->getRepository(Article::class)->findAll();
        $total_invitations = 0;
        foreach ($articles as $article) {
            $total_invitations += $article->getInvitedUsers()->count();
        }
        $total_evaluations = count($this->entityManager->getRepository(Evaluation::class)->findAll());

        // Create an associative array with the data
        $stats = [
            'total_invitations' => $total_invitations,
            'total_evaluations' => $total_evaluations,
        ];

        // Return the data as a JSON response
        return new JsonResponse($stats);
    }
}
