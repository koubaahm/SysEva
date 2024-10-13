<?php

namespace App\Controller;

use App\Entity\Section;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;


class SectionController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/sections', name: 'sections_index')]
    public function index(): Response
    {   
    $entityManager = $this->entityManager;
    $sections = $entityManager->getRepository(Section::class)->findAll();

    return $this->render('section/index.html.twig', [
        'sections' => $sections,
    ]);
}


    #[Route('/sections/add', name: 'app_section_add')]
    public function addSectionForm(): Response
    {
    return $this->render('section/add.html.twig');
    }


    #[Route('/section/new', name: 'section_new')]
    public function new(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $section = new Section();

            $nom = $request->request->get('nom');
            $section->setNom($nom);

            $entityManager = $this->entityManager;
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirectToRoute('sections_index');
        }
    
        return $this->render('index.html.twig');

    }


    #[Route('/sections/{id}', name: 'section_delete')]
    public function delete(Section $section): Response
    {
        $entityManager = $this->entityManager;
        $entityManager->remove($section);
        $entityManager->flush();

        $this->addFlash('success', 'La section a été supprimée avec succès.');

        return $this->redirectToRoute('sections_index');
    }
}

