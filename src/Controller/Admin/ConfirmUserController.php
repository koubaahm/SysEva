<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Service\JWTServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ConfirmUserController extends AbstractController
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/confirm/{token}', name: 'app_confirm_account')]
    public function confirmAccount(
        string $token,
        EntityManagerInterface $entityManager,
        JWTServiceInterface $jwtService,
        UrlGeneratorInterface $urlGenerator,
        Request $request
    ): Response {
        $user = $entityManager->getRepository(User::class)->findOneBy(['confirmationToken' => $token]);

        if (!$user || !$jwtService->isValid($token)) {
            $this->addFlash('error', 'The link is invalid.');
            return $this->redirectToRoute('error_page'); // Assurez-vous que cette route existe
        }

        if ($jwtService->isExpired($token)) {
            $header = ['typ' => 'JWT', 'alg' => 'HS256'];
            $payload = ['user_id' => $user->getId()];
            $newToken = $jwtService->generate($header, $payload, 'app.jwtsecret');

            $user->setConfirmationToken($newToken);
            $entityManager->flush();

            $confirmUrl = $urlGenerator->generate('app_confirm_account', ['token' => $newToken], UrlGeneratorInterface::ABSOLUTE_URL);

            $email = (new TemplatedEmail())
                ->from(new Address('syseva2024@gmail.com', 'SysEva'))
                ->to(new Address($user->getEmail()))
                ->subject('New confirmation link for your SysEva account')
                ->htmlTemplate('emails/register.html.twig')
                ->context([
                    'user' => $user,
                    'confirmUrl' => $confirmUrl
                ]);

            $this->mailer->send($email);

            $this->addFlash('warning', 'The previous confirmation link has expired. A new link has been sent to your email address.');
            return $this->redirectToRoute('lien_expire');
        }

        $user->setEtat(true);
        $user->setConfirmationToken(null);
        $entityManager->flush();

        $this->addFlash('success', 'Your account has been successfully activated. Please reset your password.');

        return $this->redirectToRoute('app_reset_password', ['token' => $user->getConfirmationToken()]);
    }

    #[Route('/lien_expire', name: 'lien_expire')]
    public function showError(): Response
    {
        return $this->render('emails/lienExpire.html.twig');
    }
}
