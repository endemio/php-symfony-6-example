<?php

namespace App\Controller\v1;

use App\Services\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DataController extends AbstractController
{

    const VERSION = 'v1';

    #[Route('/data', name:'data', methods: ['GET'])]
    public function data(DataService $service): JsonResponse
    {
        return new JsonResponse(array_merge(['version' => self::VERSION], $service->random()));
    }

}