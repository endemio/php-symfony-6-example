<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class StreamedController extends AbstractController
{

    const REPEAT = 4;
    const SLEEP = 1;

    #[Route('/streamed', name: 'app_twig_streamed')]
    public function page(): Response
    {
        return $this->render('/page/streamed.html.twig');
    }

    #[Route('/streamed-ajax', name: 'app_twig_streamed_ajax', methods: ["GET"])]
    public function ajax(Request $request): Response
    {
        if (!$request->isXmlHttpRequest()) {
            throw new BadRequestHttpException('AJAX request expected.');
        }

        $response = new StreamedResponse();
        $response->setCallback(function () {
            $repeat = self::REPEAT;
            for ($i = 1; $i < $repeat; ++$i) {
                echo 'Ответ ', $i;

                if (ob_get_level() > 0) {ob_flush();}
                flush();

                sleep(self::SLEEP);
            }
        });

        return $response;
    }

}