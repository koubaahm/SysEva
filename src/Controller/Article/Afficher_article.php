<?php

namespace App\Controller\Article;

use App\Entity\Article;
use App\Entity\Evaluation;
use App\Entity\Section;
use App\Entity\Criteres;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CriteresRepository;
use App\Repository\EvaluationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class Afficher_article extends AbstractController
{
    private $entityManager;
    private $authorizationChecker;

    public function __construct(EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->entityManager = $entityManager;
        $this->authorizationChecker = $authorizationChecker;
    }

    #[Route(path: '/articles', name: 'app_articles')]
    public function afficherArticles(): Response
    {           
        $filtre = $this->entityManager->getRepository(Section::class)->findAll();                        
        if ($this->authorizationChecker->isGranted('ROLE_SENIOR_EDITOR')) {
            $user = $this->getUser();            
            
            $sections = $this->entityManager->getRepository(Section::class)->findBy(array('seniorEditor' => $user));                        
            $articles = [];
            foreach ($sections as $section) {                                
                $articles = array_merge($articles, $this->entityManager->getRepository(Article::class)->findBy(array('section' => $section)));                
            }            

            return $this->render('liste_articles.html.twig', [
                'articles' => $articles,
                'sections' => $filtre,
            ]);               
        }else if ($this->authorizationChecker->isGranted('ROLE_SECTION_EDITOR')){
            $user = $this->getUser();
            $Myarticles = $this->getUserArticles();
            $section = $this->entityManager->getRepository(Section::class)->findOneBySectionEditor($user);

            if (!$section) {
                throw $this->createNotFoundException('No section found for this editor.');
            }
            $articles = [];                                          
            $articles = $this->entityManager->getRepository(Article::class)->findBy(array('section' => $section)); 
            // Utilisez un tableau associatif pour éviter les doublons
            $mergedArticles = [];

            // Ajoutez les articles de $Myarticles
            foreach ($Myarticles as $article) {
                $mergedArticles[$article->getId()] = $article;
            }

            // Ajoutez les articles de $articles
            foreach ($articles as $article) {
                $mergedArticles[$article->getId()] = $article;
            }

            // Convertissez le tableau associatif en une liste d'articles
            $mergedArticles = array_values($mergedArticles);                                     
            return $this->render('liste_articles.html.twig', [
                'articles' => $mergedArticles,    
                'sections' => $filtre,            
            ]);
        }else if($this->authorizationChecker->isGranted('ROLE_CHIEF_EDITOR') || $this->authorizationChecker->isGranted('ROLE_ADMIN')){
            $articles = [];
            $articles = $this->entityManager->getRepository(Article::class)->findAll(); 
            return $this->render('liste_articles.html.twig', [
                'articles' => $articles,  
                'sections' => $filtre,              
            ]);                           
        }else{
            $articles = $this->getUserArticles();

            $article = $this->entityManager->getRepository(Article::class)->find(2);
            $user=$this->getUser();            
            $evaluation = $this->entityManager->getRepository(Evaluation::class)->findOneBy(['Article' => $article, 'user' => $user]);
        
            return $this->render('liste_articles.html.twig', [
                'articles' => $articles,
                'sections' => $filtre,
            ]);
        }
    }
        private function getUserArticles(): ?PersistentCollection
        {
            $user = $this->getUser();

            // Utilisez fetch join pour éviter le problème de Lazy Loading
            $query = $this->entityManager->createQuery(
                'SELECT u, a FROM App\Entity\User u
                JOIN u.myArticles a
                WHERE u.id = :id'
            )->setParameter('id', $user->getId());

            // Utilisez getOneOrNullResult pour éviter l'exception si aucun utilisateur n'est trouvé
            $user = $query->getOneOrNullResult();

            // Si l'utilisateur est trouvé mais n'a pas d'articles, renvoyez une collection vide
            if ($user !== null) {
                $myArticles = $user->getMyArticles();
                return $myArticles;
            }

            // Si aucun utilisateur n'est trouvé, renvoyez un PersistentCollection vide
            return new PersistentCollection(
                $this->entityManager,
                $this->entityManager->getClassMetadata(Article::class),
                new ArrayCollection()
            );
        }



    #[Route(path: '/article/{id}', name: 'app_view_article')]
    public function afficherArticle($id): Response
    {
        $article = $this->entityManager->getRepository(Article::class)->find($id);
        $user = $this->getUser();
       
        $evaluation = $this->entityManager->getRepository(Evaluation::class)->findOneBy(['Article' => $article , 'user'=> $user]);
        $criteres = $this->entityManager->getRepository(Criteres::class)->findAll();
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN') || $this->authorizationChecker->isGranted('ROLE_CHIEF_EDITOR')) {
            $evaluations = $this->entityManager->getRepository(Evaluation::class)->findBy(['Article' => $article ]);
            $criteres = $this->entityManager->getRepository(Criteres::class)->findAll();
         
        
            return $this->render('article.html.twig', [
                'evaluation'=>$evaluation,
                'article' => $article,
                'evaluations'=>$evaluations,
                'criteres'=>$criteres,
            ]);
        }        
        
        
        return $this->render('article.html.twig', [
            'article' => $article,
            'evaluation'=>$evaluation,
            'criteres'=>$criteres,
        ]);
    
    }
    #[Route(path:'/eval/{id}/{article}' , name :'app_eval')]
    public function afficherEvaluation(Request $request,CriteresRepository $CriteresRepository, EvaluationRepository $evaluationRepository,int $id, int $article): response 
    {
       
    $criteres = $CriteresRepository->findAll();
   
    $evaluation = $evaluationRepository -> find($id);
    $notes=$evaluation->getNotes();
    $this->getAllArticleAverages();
    return $this->render('evaluation/eval.html.twig' , 
    [ 'criteres' => $criteres,  'evaluation' =>$evaluation ,'id_eval' => $id, 'notes'=> $notes, 'id_article' => $article]);
    
    }
    
    
    #[Route(path:'/evalMdifSave/{id_eval}' , name :'app_evalModifSave', methods: ['POST'])]
    public function ModfierSaveEvaluation(EntityManagerInterface $entityManager, EvaluationRepository $evaluationRepository,int $id_eval, Request $request): response 
    {

        $evaluation = $this->entityManager->getRepository(Evaluation::class)->find($id_eval);
        $id=$evaluation->getArticle()->getId();
       
        $criteres = $this->entityManager->getRepository(Criteres::class)->findAll();
        foreach ($criteres as $critere) {
            
            $critereName = str_replace(' ', '_', $critere->getIntitule());
            $note = $request->request->get($critereName);
            if ($note === null) {
                return new Response("Missing value for criteria: $critereName", Response::HTTP_BAD_REQUEST);
            }
            $notes[] = $note; 
        }
       
       
        $criteresRepository = $entityManager->getRepository(Criteres::class);
        $criteres = $criteresRepository->findAll();
        $nb=0;
        
        foreach($criteres as $critere){
            $nb++;
             $coeff[]= $critere->getCoefficient();

        }
       
       
    

        $moy=0;
        $som=0;
        for ($i = 0 ;$i<$nb-1; $i++){
            $moy=$moy+$coeff[$i]*$notes[$i];
            $som=$som+$coeff[$i];
            
            }  
            switch($notes[$nb-1] ){
                case "YES" :
                    $moy=$moy+5; 
                    
                    break;
                case "Maybe Yes":
                    $moy=$moy+3;
                    
                    break;
                case "Maybe No" :
                    $moy=$moy-1;
                    
                    break;
                case "NO" :
                    $moy=$moy-5;  
                   
                    break;
                    
            }    
            $moy=$moy/$som;
           
           

        $evaluation->setNotes($notes);
        $commentaire = $request->request->get('commentaire');
        $evaluation->setCommentaire($commentaire);
        $evaluation->setMoyenne($moy);
        
        $entityManager->persist($evaluation);
        $entityManager->flush();
        $id = $evaluation->getArticle()->getId();
        $this->getAllArticleAverages();
        return $this->redirectToRoute('app_view_article', ['id' => $id]);  
    
    }
    
    #[Route(path: '/preview-pdf/{filename}', name: 'preview_pdf')]
    public function previewPdf(string $filename): Response
    {
        // Assuming your PDF files are stored in the 'public/pdfs' directory
        $pdfPath = $this->getParameter('kernel.project_dir') . '/public/uploads/' . $filename;

        // Check if the file exists
        if (!file_exists($pdfPath)) {
            throw $this->createNotFoundException('The file does not exist');
        }

        // Load the PDF content
        $pdfContent = file_get_contents($pdfPath);

        // Return a response with the PDF content
        return new Response(
            $pdfContent,
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"'
            ]
        );
    }
    public function getAllArticleAverages(): void
    {
     

        $articles = $this->entityManager->getRepository(Article::class)->findAll();

        if (empty($articles)) {
            
        }

        $output = "";

        foreach ($articles as $article) {
            $evaluations = $this->entityManager->getRepository(Evaluation::class)->findBy(['Article' => $article]);

            if (empty($evaluations)) {
                $article->setMoyenne(null);
                $average = "No evaluations";
            } else {
                $total = 0;
                $count = 0;

                foreach ($evaluations as $evaluation) {
                    $total += $evaluation->getMoyenne();
                    $count++;
                }

                $average = $total / $count;
                $article->setMoyenne($average);
            }

            $this->entityManager->flush();

            $output .= "Article ID: " . $article->getId() . " - Average: " . ($average === "No evaluations" ? $average : number_format($average, 2)) . "\n";
        }

       
    }
}