<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OnGoingVisiteController extends AbstractController
{
    #[Route('/visiteEnCours', name: 'app_on_going_visite')]
    public function index(): Response
    {
        return $this->render('on_going_visite/index.html.twig', [
            'controller_name' => 'OnGoingVisiteController',
        ]);
    }
}
