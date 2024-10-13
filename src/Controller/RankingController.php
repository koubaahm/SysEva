<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RankingController extends AbstractController
{

    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }
    #[Route('/ranking', name: 'ranking_list')]
    public function index(ArticleRepository $articleRepository, SectionRepository $sectionRepository): Response
    {
        if ($this->authorizationChecker->isGranted('ROLE_CHIEF_EDITOR') || $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $articles = $articleRepository->findBy([], ['moyenne' => 'DESC']);
            $sections = $sectionRepository->findAll();
            return $this->render('ranking/index.html.twig', [
                'articles' => $articles,
                'sections' => $sections,
                    ]);
                }
        
        if ($this->authorizationChecker->isGranted('ROLE_SENIOR_EDITOR') ) {
            $id=$this->getUser()->getId();
            $sections = $sectionRepository->findBy(['seniorEditor' => $id]); 
           
           
            $articles = $articleRepository->findBy([ 'section' => $sections ], ['moyenne' => 'DESC'] );
           
            return $this->render('ranking/index.html.twig', [
                'articles' => $articles,
                'sections' => $sections,
                    ]);
        
        }
        if($this->authorizationChecker->isGranted('ROLE_SECTION_EDITOR')){
            $id= $this->getUser()->getId();
    
        $section= $this->getUser()->getMySection()->getId();
        $sections= $sectionRepository->findBy(['id' => $section]);
        
       $articles = $articleRepository->findAll(['section' => $section ], ['moyenne' => 'DESC'] );
  
       
       return $this->render('ranking/index.html.twig', [
        'articles' => $articles,
        'sections' => $sections,
            ]);
        }

        return $this->redirectToRoute('index');

    }
}
