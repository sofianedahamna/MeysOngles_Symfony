<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Order;
use App\Classe\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }


    #[Route('/commande/merci/{stripeSessionId}', name: 'app_order_validate')]
    public function index(Cart $cart,$stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        

        if(!$order || $order->getUser() != $this->getUser()){
            return $this->render('app_accueil');
        }
        if($order->getState() == 0){
            $cart->remove();

            $order->setState(1);
            $this->entityManager->flush();


            $mail = new Mail();
            $content= "Bonjour ".$order->getUser()->getFirstName()."<br> Merci pour votre commande nous sommes heureux de vous compter parmie nos client";
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstName(), "Votre commande la boutique Mey's Ongles est bien valider",$content);
        }

        return $this->render('order_success/index.html.twig',[
            'order' => $order
        ]);
    }
}
