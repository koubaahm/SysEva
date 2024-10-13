<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LinkExpireController extends AbstractController
{
    #[Route('/lien-expire', name: 'lien_expire')]
    public function lienExpire(): Response
    {
        return $this->render('emails/lienExpire.html.twig');
    }
}
