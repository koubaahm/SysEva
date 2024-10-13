<?php

namespace App\EventSubscriber;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Service\JWTServiceInterface;
use App\Service\SendMailServiceInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class UserSubscriber implements EventSubscriberInterface
{
    private JWTServiceInterface $jwtService;
    private SendMailServiceInterface $mailService;
    private EntityManagerInterface $entityManager;
    private UrlGeneratorInterface $urlGenerator;
    private MailerInterface $mailer;

    public function __construct(JWTServiceInterface $jwtService, SendMailServiceInterface $mailService, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, MailerInterface $mailer)
    {
        $this->jwtService = $jwtService;
        $this->mailService = $mailService;
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityPersistedEvent::class => ['onAfterEntityPersisted'],
        ];
    }

    public function onAfterEntityPersisted(AfterEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        if (!$entity instanceof User) {
            return;
        }

        // Générer le token JWT pour le nouvel utilisateur
        $header = ['typ' => 'JWT', 'alg' => 'HS256'];
        $payload = ['user_id' => $entity->getId()];
        $token = $this->jwtService->generate($header, $payload, 'app.jwtsecret');

        // Définir le token de confirmation sur l'entité utilisateur
        $entity->setConfirmationToken($token);
        
        // Persister les modifications
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        // Générer l'URL de confirmation
        $confirmUrl = $this->urlGenerator->generate('app_confirm_account', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

        // Créer et envoyer l'email de confirmation
        $email = (new TemplatedEmail())
            ->from(new Address('syseva2024@gmail.com', 'SysEva'))
            ->to(new Address($entity->getEmail()))
            ->subject('Activate Your SysEva Account')
            ->htmlTemplate('emails/register.html.twig')
            ->context([
                'user' => $entity,
                'confirmUrl' => $confirmUrl
            ]);

        $this->mailer->send($email);
    }
}
