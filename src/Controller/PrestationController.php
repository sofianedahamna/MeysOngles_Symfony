<?php

namespace App\Controller;

use App\Entity\Prestation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class PrestationController extends AbstractController
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/prestation', name: 'app_prestation')]
    public function index(): Response
    {

        $prestation = $this->entityManager->getRepository(Prestation::class)->findAll();

        return $this->render('prestation/index.html.twig', [
            'prestation'=> $prestation,
        ]);

    }
}
