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

class SitemapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'app_sitemap', defaults: [ '_format' => 'xml' ], priority: 10)]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $host = $request->getSchemeAndHttpHost();
        $urls = [];
        //define static urls
        $urls[] = ['loc' => $this->generateUrl('app_default'), 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => 1];
        $urls[] = ['loc' => $this->generateUrl('app_contact'), 'changefreq' => 'monthly', 'priority' => 0.1];
        $staticPages = $entityManager->getRepository(Page::class)->findAll();
        foreach ($staticPages as $page) {
            $urls[] = ['loc' => $this->generateUrl('app_page', ['slug' => $page->getSlug()]), 'lastmod' => $page->getUpdatedAt()->format('Y-m-d'), 'changefreq' => 'monthly', 'priority' => 0.3];
        }
        //define dynamic urls
        $categories = $entityManager->getRepository(Category::class)->findAll();
        foreach ($categories as $category) {
            $urls[] = ['loc' => $this->generateUrl('list_post_by_category', ['slug' => $category->getSlug()]), 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => 0.9];
            // get published posts inside this category
            $posts = $entityManager->getRepository(Post::class)->findByCategory($category);
            foreach ($posts as $post) {
                $urls[] = [
                    'loc' => $this->generateUrl('show_post', [
                        'category_slug' => $category->getSlug(),
                        'slug' => $post->getSlug()
                    ]),
                    'lastmod' => $post->getCreatedAt()->format('Y-m-d'),
                    'changefreq' => 'daily',
                    'priority' => 0.8,
                    'images' => [
                        [
                            'loc' => $post->getImage(),
                            'caption' => $post->getTitle(),
                            'title' => $post->getTitle(),
                            'license' => 'https://creativecommons.org/licenses/by-sa/4.0/',
                        ]
                    ]
                ];
            }
        }

        // return response in XML format
        $response = new Response(
            $this->renderView('sitemap/index.html.twig', array( 'urls' => $urls,
                'hostname' => $host)),
            200
        );
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
