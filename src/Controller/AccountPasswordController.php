<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountPasswordController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    #[Route('/compte/modifier-mon-mot-de-passe', name: 'app_account_password')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $notification = null;


        $Client = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $Client);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $old_Pwd = $form->get('old_password')->getData();
            if($encoder->isPasswordValid($Client,$old_Pwd)){
                $new_pwd = $form->get('new_password')->getData();
                $password = $encoder->hashPassword($Client, $new_pwd);

                $Client->setPassword($password);
                $this->entityManager->flush();

                $notification = "Votre mot de passe a bien ete mis a jour";
            }else{
                $notification = "Votre mot de passe actuel n'est pas le bon";
            }
        }
        return $this->render('account/password.html.twig',[

            'form' => $form->createView(),
            'notification'=>$notification
        ]);
    }
}
