<?php

namespace App\Controller;

use App\Entity\Exposition;
use App\Entity\Visite;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateVisiteController extends AbstractController
{
    #[Route('/', name: 'app_create_visite')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {

        $lesExpos = $doctrine->getRepository(Exposition::class)->findAll();
        $lesVisites = $doctrine->getRepository(Visite::class)->findAll();

        $jauge = 10;
        $nbNisitesEnCours = "";
        $tarif = 0;

        $message = "";

        $nbAdultes = $request->get('nbAdultes');
        $nbEnfants = $request->get('nbEnfants');
        $valider = $request->get('valider');

        $i = 1;
        for ($i = 1; $i = count($lesExpos); $i++) {
            $request->get($i);
        }


        if ($nbEnfants != null && $nbAdultes != null) {

            if ($valider != null) {

            }
        }

        return $this->render('create_visite/index.html.twig', [
            'lesExpos' => $lesExpos,
            'lesVisites' => $lesVisites,
        ]);
    }
}
