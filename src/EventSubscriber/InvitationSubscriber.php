<?php
// src/EventSubscriber/InvitationSubscriber.php

namespace App\EventSubscriber;

use App\Entity\Notification;
use App\Event\InvitationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface; 

class InvitationSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger; // Ajout du logger pour la débogage

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->logger = $logger; // Initialisation du logger
    }

    public static function getSubscribedEvents(): array
    {
        return [
            InvitationEvent::NAME => 'onInvitationSend',
        ];
    }

    public function onInvitationSend(InvitationEvent $event)
{
    $invitationModel = $event->getInvitationModel();
    foreach ($invitationModel->getUsers() as $user) {
        // Récupérer la liste des articles
        $articles = $invitationModel->getArticles();

        // Envoyer une notification pour chaque article
        foreach ($articles as $article) {
            $message = "You have been invited to review the article: " . $article->getTitre() . ".";

            // Créer et envoyer une notification
            $notification = new Notification();
            $notification->setUser($user);
            $notification->setMessage($message);
            $notification->setSeen(false);
            $notification->setArticleId($article->getId());
            $this->entityManager->persist($notification);
            $this->entityManager->flush();

            try {
                // Envoyer un email
                $email = (new TemplatedEmail())
                    ->from(new Address('syseva2024@gmail.com', 'SysEva Review Team'))
                    ->to(new Address($user->getEmail(), $user->getNom()))
                    ->subject('Invitation to Review New Article')
                    ->htmlTemplate('emails/review_invitation.html.twig')
                    ->context([
                        'user' => $user,
                        'article' => $article,
                    ]);

                $this->mailer->send($email);
                $this->logger->info("Email sent to {$user->getEmail()} for article {$article->getTitre()}");

                $this->entityManager->persist($notification);
                $this->entityManager->flush();
                $this->logger->info("Notification sent to {$user->getEmail()} for article {$article->getTitre()}");
            } catch (\Exception $e) {
                $this->logger->error("Failed to send email or notification: " . $e->getMessage());
            }
        }
    }
}

    
    
}
