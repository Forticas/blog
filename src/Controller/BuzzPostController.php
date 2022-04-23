<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BuzzPostController extends AbstractController
{
    #[Route('/buzz/post', name: 'app_buzz_post')]
    public function index(): Response
    {
        return $this->render('buzz_post/index.html.twig', [
            'controller_name' => 'BuzzPostController',
        ]);
    }
}
