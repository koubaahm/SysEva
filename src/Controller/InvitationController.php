<?php

namespace App\Controller;

use App\Form\InvitationFormType;
use App\Form\Model\InvitationModel;
use App\Entity\Article;
use App\Entity\User;
use App\Entity\Keyword;
use App\Event\InvitationEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class InvitationController extends AbstractController
{
    private $entityManager;
    private $authorizationChecker;

    public function __construct(EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->entityManager = $entityManager;
        $this->authorizationChecker = $authorizationChecker;
    }

    #[Route('/invite', name: 'invite_users')]
    public function invite(Request $request, EventDispatcherInterface $eventDispatcher): Response
    {
        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit d\'accéder à cette page.');
        }
        $invitationModel = new InvitationModel();
        $form = $this->createForm(InvitationFormType::class, $invitationModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $users_ids = $request->request->all()["invitation_form"]["users"];
            $articles_ids = $request->request->all()["invitation_form"]["articles"];

            $users = $this->entityManager->getRepository(User::class)->findBy(['id' => $users_ids]);
            $articles = $this->entityManager->getRepository(Article::class)->findBy(['id' => $articles_ids]);

            foreach ($users as $user) {
                foreach ($articles as $article) {
                    $user->addMyArticle($article);
                    $article->addInvitedUser($user);
                    $this->entityManager->persist($user);
                    $this->entityManager->persist($article);
                }
            }

            $this->entityManager->flush();

            // Dispatch the custom event
            $event = new InvitationEvent($invitationModel);
            $eventDispatcher->dispatch($event, InvitationEvent::NAME);

            $this->addFlash('success', 'Invitations successfully sent!');
            return $this->redirectToRoute('invite_users');
        }

        return $this->render('invitation/invite.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/filter-articles", name: "filter_articles_by_section", methods: ["POST"])]
    public function filterArticlesBySection(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $keywordId = $request->request->get('keyword');

        if ($keywordId === 'all') {
            $articles = $em->getRepository(Article::class)->findAll();
        } else {
            $keyword = $em->getRepository(Keyword::class)->find($keywordId);
            $section = $keyword->getSection();
            $articles = $em->getRepository(Article::class)->findBy(['section' => $section]);
        }

        $articlesArray = [];
        foreach ($articles as $article) {
            $articlesArray[] = [
                'id' => $article->getId(),
                'titre' => $article->getTitre(),
            ];
        }

        return new JsonResponse($articlesArray);
    }

    #[Route("/filter-users", name: "filter_users_by_section", methods: ["POST"])]
    public function filterUsersBySection(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $keywordId = $request->request->get('keyword');

        if ($keywordId === 'all') {
            $users = $em->getRepository(User::class)->findAll();
        } else {
            $keyword = $em->getRepository(Keyword::class)->find($keywordId);
            $keywordName = $keyword->getName();
            $query = $em->createQuery(
                'SELECT u
                FROM App\Entity\User u
                WHERE u.competences LIKE :keywordName'
            )->setParameter('keywordName', '%' . $keywordName . '%');

            $users = $query->getResult();
        }

        $usersArray = [];
        foreach ($users as $user) {
            $usersArray[] = [
                'id' => $user->getId(),
                'nom' => $user->getNom(),
                'email' => $user->getEmail(),
            ];
        }

        return new JsonResponse($usersArray);
    }
}
