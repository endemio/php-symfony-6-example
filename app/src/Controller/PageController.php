<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{

    #[Route('/global-config', name: 'app_twig_global_config')]
    public function pageGlobalConfig(): Response
    {
        return $this->render('/page/global-config.html.twig');
    }

    #[Route('/global-service', name: 'app_twig_global_service')]
    public function pageGlobalService(): Response
    {
        return $this->render('/page/global-service.html.twig');
    }
}