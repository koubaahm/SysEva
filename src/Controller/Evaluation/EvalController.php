<?php

namespace App\Controller\Evaluation;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Criteres;
use App\Entity\Evaluation;
use App\Entity\Grille;
use App\Entity\User;
use App\Entity\Article;
use App\Repository\CriteresRepository;
use App\Repository\EvaluationRepository;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class EvalController extends AbstractController
{
    private $entityManager;
    private $authorizationChecker;

    public function __construct(EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->entityManager = $entityManager;
        $this->authorizationChecker = $authorizationChecker;
    }



    #[Route(path: '/grille/{id}', name: 'app_grille')]
   public function evaluer (CriteresRepository $CriteresRepository , int $id): response{
    if (!$this->authorizationChecker->isGranted('ROLE_USER')) {
        throw $this->createAccessDeniedException('Vous n\'avez pas le droit d\'accéder à cette page. vous n\'etes connecté');
    }
    
    $criteres = $CriteresRepository->findAll();

            return $this->render('evaluation/evaluation.html.twig' , 
            [ 'criteres' => $criteres,
        'id' => $id]);
            
    }
    #[Route('/create_grille', name: 'create_grille')]
    public function createGrille(EntityManagerInterface $entityManager): Response
    {
        // Créer une nouvelle grille
        $grille = new Grille();
        $grille->setAnnee(2024);
    
        // Récupérer tous les critères
        $criteresRepository = $entityManager->getRepository(Criteres::class);
        $criteres = $criteresRepository->findAll();
    
        // Ajouter tous les critères à la grille
        foreach ($criteres as $critere) {
            $grille->addCritere($critere);
        }
 
        // Enregistrer la grille dans la base de données
        $entityManager->persist($grille);
        $entityManager->flush();
    
        return new Response('Grille créée avec succès !');
    }  
    //afficher une évaluation 
    #[Route('/evaluation', name: 'app_evaluation')]
    public function affiche_evaluation(EvaluationRepository $EvaluationRepository, CriteresRepository $CriteresRepository):Response{
        if (!$this->authorizationChecker->isGranted('ROLE_USER')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit d\'accéder à cette page. vous n\'etes connecté');
        }
    
        $note=[];
        $evaluation = $EvaluationRepository->find(13);
        $note=$evaluation->getNotes();
       
        $criteres = $CriteresRepository->findAll();
      
          
            return $this->render('evaluation/afficher_evaluation.html.twig', [
                'evaluation' => $evaluation,'criteres' => $criteres,'notes' => $note
            ]);
    }
//sauvegarde de l'evaluation
    #[Route('/evaluer/{id}', name: 'app_evaluer', methods: ['POST'])]
    public function saveEvaluation(Request $request , EntityManagerInterface $entityManager, int $id): Response
    
    {
        if (!$this->authorizationChecker->isGranted('ROLE_USER')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit d\'accéder à cette page. vous n\'etes connecté');
        }
    
        // Récupérer les données du formulaire
        $formData = $request->request->all();
      // Créer une nouvelle instance de l'entité Evaluation
        $evaluation = new Evaluation();
        
        // Extraire les notes du formulaire et les ajouter au tableau 'notes'
        $notes = [];
       
        $criteresRepository = $entityManager->getRepository(Criteres::class);
        $criteres = $criteresRepository->findAll();
        $nb=0;
        
        foreach($criteres as $critere){
            $nb++;
             $coeff[]= $critere->getCoefficient();

        }
       
       
        // Ajouter tous les critères à la grille
        for ($i = 0; $i <$nb; $i++) {
            $notes[] = array_values($formData)[$i];
            
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
           
           
           
            
        $com=$request->request->get('commentaire');
        // Définir les notes dans l'entité Evaluation
        $evaluation->setNotes($notes);
     
        $evaluation->setMoyenne($moy);
 
        
       // Récupérer user et article 

       $UserRepository = $entityManager->getRepository(User::class);
       $user = $this->getUser();
       $articleRepository = $entityManager->getRepository(Article::class); 
       $article = $articleRepository->find($id);
       $grilleRepository = $entityManager->getRepository(Grille::class); 
       $grille = $grilleRepository->find(1);
       $evaluation->setUser($user);
        $evaluation->setCommentaire($com);
       $evaluation->setArticle($article);
       $evaluation->setGrille($grille);
       $evaluation->setSubmite(0);
        // Enregistrer l'entité dans la base de données
        $entityManager->persist($evaluation);
        $entityManager->flush();
        $this->getAllArticleAverages();
        // Redirection vers une autre page après soumission du formulaire
        return $this->redirectToRoute('app_view_article', ['id' => $id]);    
    }//submit l'evaluation
    
    #[Route('/submit/{id}', name: 'app_sub', methods: ['POST'])]
    public function submitEvaluation(Request $request , EntityManagerInterface $entityManager, int $id): Response
    
    {
        if (!$this->authorizationChecker->isGranted('ROLE_USER')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit d\'accéder à cette page. vous n\'etes connecté');
        }
        $user = $this->getUser();

    // Vérifier si l'article a déjà été évalué par l'utilisateur connecté
    $evaluationRepository = $entityManager->getRepository(Evaluation::class);
    $existingEvaluation = $evaluationRepository->findOneBy(['user' => $user, 'Article' => $id]);

    if ($existingEvaluation) {
        return new Response('Cet article a déjà été évalué par vous.');
    }
        // Récupérer les données du formulaire
        $formData = $request->request->all();
      // Créer une nouvelle instance de l'entité Evaluation
        $evaluation = new Evaluation();
        
        // Extraire les notes du formulaire et les ajouter au tableau 'notes'
        $notes = [];
       
        $criteresRepository = $entityManager->getRepository(Criteres::class);
        $criteres = $criteresRepository->findAll();
        $nb=0;
        foreach($criteres as $critere){
            $nb++;
             $coeff[]= $critere->getCoefficient();

        }
       
       
        // Ajouter tous les critères à la grille
        for ($i = 0; $i <$nb; $i++) {
            $notes[] = array_values($formData)[$i];
            
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
            
    
        // Ajouter tous les critères à la grille
        
        $com=$request->request->get('commentaire');
        // Définir les notes dans l'entité Evaluation
        $evaluation->setNotes($notes);
        $evaluation->setMoyenne($moy);
       
        


       // Récupérer user et article 

       $UserRepository = $entityManager->getRepository(User::class);
       $user = $this->getUser();
       $articleRepository = $entityManager->getRepository(Article::class); 
       $article = $articleRepository->find($id);
       $grilleRepository = $entityManager->getRepository(Grille::class); 
       $grille = $grilleRepository->find(1);
       $evaluation->setUser($user);
        $evaluation->setCommentaire($com);
       $evaluation->setArticle($article);
       $evaluation->setGrille($grille);
       $evaluation->setSubmite(1);
       
        // Enregistrer l'entité dans la base de données
        $entityManager->persist($evaluation);
        $entityManager->flush();
        $this->getAllArticleAverages();
        // Redirection vers une autre page après soumission du formulaire
        return $this->redirectToRoute('app_view_article', ['id' => $id]);    
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

