<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Section;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class AdminCustomActionsController extends AbstractDashboardController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/custom/actions', name: 'app_admin_custom_actions')]
    public function index(): Response
    {
        $this->removeUnusedSectionEditors();
        $userRepository = $this->entityManager->getRepository(User::class);

        $users = $userRepository->createQueryBuilder('u')
            ->where('u.roles NOT LIKE :roleAdmin')
            ->andWhere('u.roles NOT LIKE :roleSeniorEditor')
            ->andWhere('u.roles NOT LIKE :roleChiefEditor')
            ->setParameter('roleAdmin', '%ROLE_ADMIN%')
            ->setParameter('roleSeniorEditor', '%ROLE_SENIOR_EDITOR%')
            ->setParameter('roleChiefEditor', '%ROLE_CHIEF_EDITOR%')
            ->getQuery()
            ->getResult();

        $sectionsRepository = $this->entityManager->getRepository(Section::class);
        $sections = $sectionsRepository->findAll();

        return $this->render('admin_custom_actions/index.html.twig', [
            'controller_name' => 'AdminCustomActionsController',
            'sectionEds' => $users,
            'sections' => $sections,
        ]);
    }


    #[Route('/admin/custom/actions/setSectionEditor', name: 'app_admin_custom_actions_set_section_editor')]
    public function setSectionEditor(Request $request): Response
    {
        $sectionId = $request->request->get("section");
        $userIdsString = $request->request->get("users");

        $sectionsRepository = $this->entityManager->getRepository(Section::class);
        $section = $sectionsRepository->findOneBy(['id' => $sectionId]);

        $sectionEditors = $section->getSectionEditors();
        foreach ($sectionEditors as $sectionEditor) {
            $section->removeSectionEditor($sectionEditor);
            $this->entityManager->persist($section);
        }
        if (!empty($userIdsString)) {
            $userIds = explode(',', $userIdsString);
            $userRepository = $this->entityManager->getRepository(User::class);

            foreach ($userIds as $userId) {
                $user = $userRepository->findOneBy(['id' => $userId]);
                if ($user) {
                    $user->setMySection($section);
                    $this->entityManager->persist($user);

                    $roles = $user->getRoles();
                    if (!in_array('ROLE_SECTION_EDITOR', $roles, true)) {
                        $roles[] = 'ROLE_SECTION_EDITOR';
                        $user->setRoles($roles);
                        $this->entityManager->persist($user);
                    }
                }
            }
        }

        $this->entityManager->flush();
        $this->removeUnusedSectionEditors();
        return $this->redirectToRoute('app_admin_custom_actions');
    }




    #[Route('/admin/custom/actions/manageSeniorEditors', name: 'app_admin_custom_actions_manage_senior_editors')]
    public function displaySeniorEditors(): Response
    {
        $userRepository = $this->entityManager->getRepository(User::class);

        $users = $userRepository->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%ROLE_SENIOR_EDITOR%')
            ->getQuery()
            ->getResult();
        $sectionsRepository = $this->entityManager->getRepository(Section::class);
        $sections = $sectionsRepository->findAll();
        return $this->render('admin_custom_actions/manageSeniorEditors.html.twig', [
            'controller_name' => 'AdminCustomActionsController',
            'seniorEds' => $users,
            'sections' => $sections,
        ]);
    }

    #[Route('/admin/custom/actions/saveSeniorEditors', name: 'app_admin_custom_actions_save_senior_editors')]
    public function saveSeniorEditors(Request $request)
    {
        $data = json_decode($request->getContent(), true);
                
        foreach ($data as $userId => $sections) {
            if ($userId !== 'unassigned') {
                $userRepository = $this->entityManager->getRepository(User::class);
                $user = $userRepository->findOneBy(["id" => $userId]);
                if ($user) {
                    foreach ($sections as $section) {
                        $sectionId = $section['id'];
                        $sectionsRepository = $this->entityManager->getRepository(Section::class);
                        $sectionEntity = $sectionsRepository->findOneBy(["id" => $sectionId]);
                        if ($sectionEntity) {
                            $sectionEntity->setSeniorEditor($user);
                            $this->entityManager->persist($sectionEntity);
                            $this->entityManager->flush();
                        }
                    }
                }
            }
        }
        // Réponse à la requête
        return new Response('Senior editors assigned successfully!', Response::HTTP_OK);
    }

    public function removeUnusedSectionEditors()
    {
        $userRepository = $this->entityManager->getRepository(User::class);

        // Récupérer tous les utilisateurs qui ont le rôle "section editor"
        $sectionEditors = $userRepository->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%ROLE_SECTION_EDITOR%')
            ->getQuery()
            ->getResult();

        // Parcourir tous les utilisateurs avec le rôle "section editor"
        foreach ($sectionEditors as $user) {
            // Récupérer la section assignée à l'utilisateur
            $section = $user->getMySection();

            // Vérifier si la section est null ou vide
            if ($section === null || $section === '') {
                // Retirer le rôle "section editor" à l'utilisateur
                $roles = array_diff($user->getRoles(), ['ROLE_SECTION_EDITOR']);
                $user->setRoles($roles);
                $this->entityManager->persist($user);
            }
        }

        $this->entityManager->flush();
    }

}
