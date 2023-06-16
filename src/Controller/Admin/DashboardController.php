<?php

namespace App\Controller\Admin;

use App\Entity\Comments;
use App\Entity\News;
use App\Entity\User;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

use Symfony\Component\Security\Core\Security;

class DashboardController extends AbstractDashboardController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');

        //return parent::index();

        if (!$hasAccess) 
           return $this->redirect('/');
       

        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(CommentsCrudController::class)->generateUrl();

        return $this->redirect($url);
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
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Laba 7 9');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Back to site', 'fa fa-home', 'app_index');
        yield MenuItem::linkToCrud('Комментарии', 'fas fa-comments', Comments::class);
        yield MenuItem::linkToCrud('Новости', 'fas fa-comments', News::class);
        yield MenuItem::linkToCrud('Пользователи', 'fas fa-comments', User::class);
    }
}
