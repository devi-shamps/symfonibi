<?php

namespace App\Controller;

use App\Entity\Visite;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OnGoingVisiteController extends AbstractController
{
    #[Route('/visiteEnCours', name: 'app_on_going_visite')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $visitesEnCours = $doctrine->getRepository(Visite::class)->findBy(['dateHeureDepart' => null]);
        $visiteAValidee = [];

        foreach ($visitesEnCours as $uneVisite) {
            if ($request->get($uneVisite->getId()) != null) {
                $visiteAValidee[] = $uneVisite;
            }
        }

        foreach ($visiteAValidee as $uneVisite) {
            $uneVisite->setDateHeureDepart(new \DateTime('Europe/Paris'));
            $doctrine->getManager()->persist();
        }

        return $this->render('on_going_visite/index.html.twig', [
            'lesVisites' => $visitesEnCours,
        ]);
    }
}
