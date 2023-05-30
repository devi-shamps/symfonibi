<?php

namespace App\Controller;

use App\Entity\Exposition;
use App\Entity\Visite;
use Doctrine\Persistence\ManagerRegistry;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use phpDocumentor\Reflection\Types\This;
use phpDocumentor\Reflection\Types\True_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;



class CreateVisiteController extends AbstractController
{
    #[Route('/', name: 'app_create_visite')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {

        $lesExpos = $doctrine->getRepository(Exposition::class)->findAll();
        $uneVisite = new Visite();
        $uneVisite ->setNbVisiteursAdultes(0);
        $uneVisite ->setNbVisiteursEnfants(0);

        $jauge = 10;
        $nbNisitesEnCours = "";
        $tarif = 0;

        $message = "";
        if ($request->get('nbAdultes') !== null){
            $uneVisite ->setNbVisiteursAdultes($request->get('nbAdultes')) ;
        }
        if ($request->get('nbEnfants') !== null){
            $uneVisite ->setNbVisiteursEnfants($request->get('nbEnfants'));
        }
        $uneVisite->setDateHeureArrivee(new \DateTime('Europe/Paris'));

        foreach ($lesExpos as $expo) {
            if ($request->get($expo->getId()) != null) {
                $uneVisite ->addExposition($expo);
            }
        }

        if ($request->get('valider') !== null and !$uneVisite->getExpositions()->isEmpty() and ($uneVisite->getNbVisiteursAdultes() > 0 or $uneVisite->getNbVisiteursEnfants() > 0)){
            $doctrine->getManager()->persist($uneVisite);
            $doctrine->getManager()->flush();
            // Create QR code
            $text = base64_encode($uneVisite->getId() . ";" . $uneVisite->getDateHeureArrivee()->format("d / m / F") . ";" .$uneVisite->getNbVisiteursEnfants() . ";" . $uneVisite->getNbVisiteursAdultes());
            $qrCode = QrCode::create($text)
                ->setEncoding(new Encoding('UTF-8'))
                ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
                ->setSize(300)
                ->setMargin(0)
                ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
                ->setForegroundColor(new Color(0, 0, 0))
                ->setBackgroundColor(new Color(255, 255, 255));
            $writer = new PngWriter();
            $result = $writer->write($qrCode)->getDataUri();

            return $this->render('create_visite/confirmVisite.html.twig', [
                'uneVisite' => $uneVisite,
                'qrCode' => $result,
            ]);
        }

        return $this->render('create_visite/index.html.twig', [
            'lesExpos' => $lesExpos,
            'uneVisite' => $uneVisite,
        ]);
    }
}
