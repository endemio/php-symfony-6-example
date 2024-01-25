<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{

    #[Route('/', name: 'app_index', methods: ['GET'])]
    public function index(): Response
    {
        return new Response(sprintf('<html><body><h1>%s</h1></body>', ucfirst(__FUNCTION__)));
    }

    #[Route('/about-us', name: 'app_about', methods: ['GET'])]
    public function about(): Response
    {
        return new Response(sprintf('<html><body><h1>%s</h1></body>', ucfirst(__FUNCTION__)));
    }

    #[Route('/news', name: 'app_news', methods: ['GET'])]
    public function news(): JsonResponse
    {
//        return new Response(sprintf('<html><body><h1>%s</h1></body>', ucfirst(__FUNCTION__)));
        return new JsonResponse(['test'=>true]);
    }

}