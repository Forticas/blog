<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Page;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class DefaultController extends AbstractController
{
    public function __construct(
        /** @var EntityManagerInterface */
        private EntityManagerInterface $entityManager,
        private CacheInterface $cache
    )
    {
    }

    #[Route('/', name: 'app_default', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        // get posts by pagination
        $posts = $this->cache->get('home_posts_' . $page, function () use ($page) {
            return $this->entityManager->getRepository(Post::class)
                ->findBy(['isPublished'=> true], ['createdAt' => 'DESC'], 10, ($page - 1) * 10);
        });

        $count_posts = $this->cache->get('count_posts', function () {
            return $this->entityManager->getRepository(Post::class)
                ->count(['isPublished' => true]);
        });
        return $this->render('default/index.html.twig', [
            'posts' => $posts,
            'count_posts' => $count_posts,
        ]);
    }

    // create contact form
    #[Route('/contact', name: 'app_contact', priority: 9)]
    public function contact(): Response
    {
        return $this->render('default/contact.html.twig');
    }

    // get page by slug with priority 8
    #[Route('/p/{slug}', name: 'app_page', requirements: ['slug' => '^(?!admin).*$'])]
    public function page(string $slug): Response
    {
        $page = $this->cache->get("page_" . $slug, function () use ($slug) {
            return $this->entityManager->getRepository(Page::class)
                ->findOneBy(['slug' => $slug]);
        });
        if (!$page) {
            throw $this->createNotFoundException('Page not found');
        }
        return $this->render('default/page.html.twig', [
            'page' => $page,
        ]);
    }

    // get all categories and show them in _categories.html.twig
    public function showCategories(): Response
    {
        $categories = $this->cache->get('categories', function () {
            return $this->entityManager->getRepository(Category::class)
                ->findBy([], ['name' => 'ASC']);
        });
        return $this->render('partials/_categories.html.twig', [
            'categories' => $categories,
        ]);
    }
}
