<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class PostController extends AbstractController
{
    public function __construct(
        /** @var EntityManagerInterface */
        private EntityManagerInterface $entityManager,
        private CacheInterface $cache
    )
    {
    }

    #[Route('/{category_slug}/{post_slug}', name: 'show_post')]
    public function show(
        string         $category_slug,
        string         $post_slug
    ): Response
    {
        $post = $this->cache->get('post_' . $post_slug, function () use ($post_slug) {
            return $this->entityManager->getRepository(Post::class)->findOneBy([
                'slug' => $post_slug,
                'isPublished' => true,
            ]);
        });

        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }
        return $this->render('post/index.html.twig', [
            'post' => $post,
        ]);
    }

    // list post by category
    #[Route('/{slug}', name: 'list_post_by_category', requirements: ['slug' => '^(?!admin).*$'])]
    public function listByCategory(
        string       $slug,
        Request        $request
    ): Response
    {
        $category = $this->cache->get('category_' . $slug, function () use ($slug) {
            return $this->entityManager->getRepository(Category::class)->findOneBy([
                'slug' => $slug,
            ]);
        });
        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }
        $page = $request->query->getInt('page', 1);
        $posts = $this->cache->get('posts_by_category_' . $slug . '_' . $page, function () use ($category, $page) {
            return $this->entityManager->getRepository(Post::class)->findByCategory(
                $category,
                true,
                10,
                $page==0 ? 0 : ($page-1)*10
            );
        });
        $count_posts = $this->cache->get('count_posts_by_category_' . $slug, function () use ($category) {
            return $this->entityManager->getRepository(Post::class)->countByCategory($category);
        });
        return $this->render('post/by_category.html.twig', [
            'posts' => $posts,
            'category' => $category,
            'count_posts' => $count_posts
        ]);
    }

    // list post by tag

    #[Route('/tag/{slug}', name: 'list_post_by_tag')]
    public function listByTag(
        string            $slug,
        Request        $request
    ): Response
    {

        $tag = $this->cache->get('tag_' . $slug, function () use ($slug) {
            return $this->entityManager->getRepository(Tag::class)->findOneBy([
                'slug' => $slug,
            ]);
        });
        if (!$tag) {
            throw $this->createNotFoundException('Tag not found');
        }
        $page = $request->query->getInt('page', 1);
        $posts = $this->cache->get('posts_by_tag_' . $slug . '_' . $page, function () use ($tag, $page) {
            return $this->entityManager->getRepository(Post::class)->findByTag(
                $tag,
                true,
                10,
                $page==0 ? 0 : ($page-1)*10
            );
        });
        $count_posts = $this->cache->get('count_posts_by_tag_' . $slug, function () use ($tag) {
            return $this->entityManager->getRepository(Post::class)->countByTag($tag);
        });
        return $this->render('post/by_tag.html.twig', [
            'posts' => $posts,
            'count_posts' => $count_posts,
        ]);
    }
}
