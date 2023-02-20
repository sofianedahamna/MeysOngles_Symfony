<?php

namespace App\Controller;


use App\Classe\Mail;
use App\Entity\Client;
use App\Entity\Rdv;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RdvType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RdvController extends AbstractController
{


    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/rdv', name: 'app_rdv')]
    public function index(Request $request): Response
    {
        $notification = null;
        $Rdv = new Rdv();
        $form = $this->createForm(RdvType::class, $Rdv);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $Rdv = $form->getData();
            $dateRdv = $Rdv->getDateRdv();
    
            // Récupération de tous les RDV pour la date sélectionnée
            $search_Rdvs = $this->entityManager->getRepository(Rdv::class)->findBy([
                'dateRdv' => $dateRdv
            ]);
    
            $userRdv = null;
            foreach ($search_Rdvs as $search_Rdv) {
                if ($search_Rdv->getClient() == $this->getUser()) {
                    // L'utilisateur a déjà un RDV pour cette date
                    $userRdv = $search_Rdv;
                    break;
                } else if ($search_Rdv->getHeureRdv() == $Rdv->getHeureRdv()) {
                    // Un autre utilisateur a un RDV pour cette date et cette heure
                    $notification = "Un autre utilisateur a déjà pris rendez-vous pour cette date et cette heure. Veuillez sélectionner une autre heure.";
                    break;
                }
            }
    
            if ($userRdv) {
                // L'utilisateur a déjà un RDV pour cette date, on modifie ce RDV
                $userRdv->setPrestations($Rdv->getPrestations());
                $this->entityManager->persist($userRdv);
                $this->entityManager->flush();
                $notification = "Votre rendez-vous a été modifié.";
            } else if (!$notification) {
                // L'utilisateur n'a pas de RDV pour cette date et cette heure, on crée un nouveau RDV
                $Rdv->setClient($this->getUser());
                $this->entityManager->persist($Rdv);
                $this->entityManager->flush();
    
                // Envoi du mail de confirmation
                $mail = new Mail();
                $content = "Bonjour " . $this->getUser()->getFirstName() . "<br> Bienvenue chez Mey's Ongles nous avons pris en compte votre demande de Rendez-vous nous sommes heureux de vous compter parmi nos clients.";
                $mail->send($this->getUser()->getEmail(), $this->getUser()->getFirstName(), "Bienvenue sur la boutique Mey's Ongles", $content);
    
                $notification = "Votre demande a été prise en compte.";
            }
        }
    
        return $this->render('rdv/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
    

}
