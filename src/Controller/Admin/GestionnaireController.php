<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Evaluation;
use App\Entity\Criteres;
use App\Entity\Section;
use App\Entity\Keyword;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

#[IsGranted('ROLE_ADMIN')]
class GestionnaireController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.htmsl.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SysEva');
    }

    public function configureMenuItems(): iterable
    {        
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Évaluations', 'fas fa-poll', Evaluation::class);
        yield MenuItem::linkToCrud('Sections', 'fas fa-layer-group', Section::class);
        yield MenuItem::linkToCrud('Grilles', 'fas fa-th-list', Criteres::class);
        yield MenuItem::linkToCrud('Skills', 'fas fa-th-list', Keyword::class);
        yield MenuItem::linkToRoute('Gestion des Editeurs de Section', 'fas fa-user-edit', 'app_admin_custom_actions')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToRoute('Gestion des Editeurs Seniors', 'fas fa-user-tie', 'app_admin_custom_actions_manage_senior_editors')->setPermission('ROLE_ADMIN');

    }
}
