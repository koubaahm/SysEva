<?php

namespace App\Service;

use App\Form\Model\InvitationModel;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class InvitationService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendInvitations(InvitationModel $invitationModel): void
    {
        foreach ($invitationModel->getUsers() as $user) {
            $email = (new TemplatedEmail())
                ->from(new Address('syseva2024@gmail.com', 'SysEva Review Team'))
                ->to(new Address($user->getEmail(), $user->getNom()))
                ->subject('Invitation to Review Articles')
                ->htmlTemplate('emails/invitation_email.html.twig')
                ->context([
                    'user' => $user,
                    'articles' => $invitationModel->getArticles(),
                ]);

            $this->mailer->send($email);
        }
    }
}
