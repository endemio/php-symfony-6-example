<?php

namespace App\Controller;

use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends AbstractController
{
    /**
     * @param string $type
     * @param string $filename
     * @param FileService $service
     * @return JsonResponse
     */
    #[Route('/file/generate/{type}/{filename}', name: 'app_file_generate_encoded_string', methods: ['GET'])]
    public function generate(string $type, string $filename, FileService $service): JsonResponse
    {
        $hex = $service->link($filename . '.' . $type);

        return new JsonResponse(['filename' => $hex??'']);
    }

    /**
     * @param string $string
     * @param FileService $service
     * @return Response
     */
    #[Route('/file/download/{string}', name: 'app_file_download', methods: ['GET'])]
    public function download(string $string, FileService $service): Response
    {

        try {
            return $service->download($string);
        } catch (NotFoundHttpException $e) {
            return new Response('File not found', 404);
        }
    }
}