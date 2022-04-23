<?php

namespace App\Controller\Admin;

use App\Entity\BuzzPost;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Blog');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Articles', 'fa fa-newspaper', Post::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-list', Category::class);
        yield MenuItem::linkToCrud('Tags', 'fa fa-tags', Tag::class);
        yield MenuItem::linkToCrud('Comment', 'fa fa-newspaper', Comment::class);
        // yield menu item buzzpost
        yield MenuItem::linkToCrud('Buzz post', 'fa fa-newspaper', BuzzPost::class);
        // yield menu item config
        yield MenuItem::linkToCrud('Config', 'fa fa-newspaper', 'App\Entity\Config');
        // yield menu item Page
        yield MenuItem::linkToCrud('Page', 'fa fa-newspaper', 'App\Entity\Page');

    }
}
