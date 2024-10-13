<?php

namespace App\Controller\Article;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Section;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;


class Ajout_Article_Controller extends AbstractController
{

    private $entityManager;
    private $authorizationChecker;

    public function __construct(EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->entityManager = $entityManager;
        $this->authorizationChecker = $authorizationChecker;
    }

    #[Route('/add_article', name: 'add_article')]
    public function addArticle(Request $request): Response
    {
        if (!$this->authorizationChecker->isGranted('ROLE_SECTION_EDITOR')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit d\'accéder à cette page.');
        }
        //$sections = $this->entityManager->getRepository(Section::class)->findAll();
        // Récupérer l'utilisateur connecté
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $sections = $user->getMySection(); // Assurez-vous que la méthode getSection() existe et renvoie la section de l'utilisateur

        if ($request->isMethod('POST')) {
            // Création d'une nouvelle instance de l'entité Article
            $article = new Article();
            /** @var UploadedFile $uploadedFile */
            //$uploadedFile = $request->files->get('pdfFile');
            $uploadedFile = $request->files->get('pdfFile');
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';

            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $article->setPdfFileName($newFilename);
            // Récupération des données du formulaire

            $pmid = $request->request->get('pmid');
            $doi = $request->request->get('doi');
            $titre = $request->request->get('titre');
            $auteurPrincipale = $request->request->get('auteurPrincipale');
            $auteurs = $request->request->get('auteurs');
            $affiliation = $request->request->get('affiliation');
            $selectedSectionId = $request->request->get('section');
            $publicationDate = $request->request->get('publicationDate');
            $journalName = $request->request->get('journalName');


            $section = $this->entityManager->getRepository(Section::class)->find($selectedSectionId);
            if (!$section) {
                throw $this->createNotFoundException('Section not found');
            }

            // Attribution des valeurs à l'entité Article
            $article->setPmid($pmid);
            $article->setDoi($doi);
            $article->setTitre($titre);
            $article->setAuteurPrincipale($auteurPrincipale);
            $article->setAuteurs($auteurs);
            $article->setAffiliation($affiliation);
            $article->setSection($section);
            //$formattedDate = \DateTime::createFromFormat('Y-m-d', $publicationDate);
            $article->setPublicationDate($publicationDate);
            $article->setJournalName($journalName);


            // Obtention de l'EntityManager et sauvegarde de l'entité dans la base de données
            $entityManager = $this->entityManager;
            $entityManager->persist($article);
            $entityManager->flush();



            // Redirection vers une autre page après l'ajout
            $this->addFlash('success', 'Article successfully submitted.');

            return $this->redirectToRoute('app_articles');
        }

        return $this->render('Ajout_article_vue.html.twig', [
            'sections' => $sections,
        ]);


    }

    #[Route('/articles', name: 'list_articles')]
    #[IsGranted('ROLE_SECTION_EDITOR')]
    public function listArticles(): Response
    {
        if (!$this->authorizationChecker->isGranted('ROLE_SECTION_EDITOR')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit d\'accéder à cette page.');
        }
        $articles = $this->entityManager->getRepository(Article::class)->findAll();

        return $this->render('liste_articles.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/download/{filename}", name="download_pdf")
     */
    public function downloadPdf($filename)
    {
        $file = $this->getParameter('kernel.project_dir') . '/public/uploads/' . $filename;

        // Send file response
        return $this->file($file, $filename, ResponseHeaderBag::DISPOSITION_ATTACHMENT);
    }

    /**
     * @Route("/article/delete/{id}", name="delete_article")
     */
    #[IsGranted('ROLE_SECTION_EDITOR')]
    public function deleteArticle($id, EntityManagerInterface $entityManager)
    {
        if (!$this->authorizationChecker->isGranted('ROLE_SECTION_EDITOR')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit d\'accéder à cette page.');
        }
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $entityManager->remove($article);
        $entityManager->flush();

        // Rediriger vers une page de confirmation ou ailleurs après la suppression
        return $this->redirectToRoute('list_articles');
    }

    #[Route('/rechercher-pmid', name: 'rechercher_pmid')]
    public function rechercherPmid(Request $request): Response // Assurez-vous que le type d'argument est Symfony\Component\HttpFoundation\Request
    {
        // Récupérer les données POST envoyées depuis le front-end
        $requestData = json_decode($request->getContent(), true);

        // Assurez-vous que le champ 'pmid' est présent dans les données envoyées
        if (!isset($requestData['pmid'])) {
            return new JsonResponse(['error' => 'PMID missing'], Response::HTTP_BAD_REQUEST);
        }

        $pmid = $requestData['pmid'];

        // Construisez l'URL avec le PMID fourni
        $url = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=pubmed&id=$pmid&retmode=json";

        // Effectuez une requête HTTP pour récupérer le contenu
        $client = HttpClient::create();
        $response = $client->request('GET', $url);

        // Vérifiez si la requête a réussi
        if ($response->getStatusCode() === 200) {
            $jsonContent = $response->getContent();
            $data = json_decode($jsonContent, true);

            return new JsonResponse($data);
        } else {
            return new JsonResponse(['error' => 'Failed to fetch data'], $response->getStatusCode());
        }
    }
}