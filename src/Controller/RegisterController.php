<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Client;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $notification = null;
        $Client = new Client();
        $form = $this->createForm(RegisterType::class, $Client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Client = $form->getData();

            $search_email = $this->entityManager->getRepository(Client::class)->findOneByEmail($Client->getEmail());

            if (!$search_email) {
                $password = $encoder->hashPassword($Client, $Client->getPassword());
                $Client->setPassword($password);

                $this->entityManager->persist($Client);
                $this->entityManager->flush();

                $mail = new Mail();
                $content= "Bonjour ".$Client->getFirstName()."<br> Bienvenue chez Mey's Ongles nail shop nous sommes heureux de vous compter parmie nos client";
                $mail->send($Client->getEmail(), $Client->getFirstName(), "Bienvenue sur la boutique Mey's Ongles",$content);

                $notification="Votre inscription s'est correctement déroulée. Vous pouvez dés a present vous connectr à votre compte.";

            } else {
                $notification="L'email que vous avez renseigné existe deja.";
            }
        }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
