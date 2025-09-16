<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/dispatch', name: 'app_dispatch')]
    public function appDispatch(): Response{
        return $this->render('dispatch.html.twig');
    }
}
