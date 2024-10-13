<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
class ErrorMailController extends AbstractController
{
    #[Route('/error', name: 'error_page')]
    public function lienExpire(): Response
    {
        return $this->render('emails/error.html.twig');
    }
}
