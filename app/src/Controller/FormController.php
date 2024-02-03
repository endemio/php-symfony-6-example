<?php

namespace App\Controller;

use App\Entity\User;
use App\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class FormController extends AbstractController
{

    const ROUTE_FORM = 'app_form_generate';
    const ROUTE_SUBMIT = 'app_form_submit';
    const ROUTE_SUCCESS = 'app_form_success';

    public function __construct(private RouterInterface $router)
    {
    }

    #[Route('/', name: self::ROUTE_FORM, methods: ['GET'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(UserType::class, new User(),['attr' => ['id' => UserType::NAME,'name'=>'1111']]);

        $session = $request->getSession();

        if ($session->has(UserType::NAME)) {
            $request->request->set( $form->getName(), unserialize($session->get(UserType::NAME)));
            $request->setMethod(Request::METHOD_POST);
            $form->handleRequest($request);
        }

        return $this->render('/page/form.html.twig',  ['form' => $form]);
    }

    #[Route('/', name: self::ROUTE_SUBMIT, methods: ['POST'])]
    public function form(Request $request): RedirectResponse
    {
        $session = $request->getSession();

        $form = $this->createForm(UserType::class, new User(),['attr' => ['id' => UserType::NAME]]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $session->remove(UserType::NAME);
            $routeName = self::ROUTE_SUCCESS;
        } else {
            $session->set(UserType::NAME, serialize($request->request->all($form->getName())));
            $routeName = self::ROUTE_FORM;
        }

        return new RedirectResponse($this->router->generate($routeName));
    }

    #[Route('/success', name: self::ROUTE_SUCCESS, methods: ['GET'])]
    public function success(): Response
    {
        return $this->render('/page/success.html.twig');
    }

}