<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use App\Entity\ProductOption;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface  $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mon-panier', name: 'app_cart')]
    public function index(Cart $cart): Response
    {
        return $this->render(
            'cart/index.html.twig',
            [
                'cart' => $cart->getFull(),
                //dd($cart)
            ]
        );
    }
    #[Route('/app_cart/add/{id}', name: 'add_to_cart')]
    public function add(Cart $cart, $id, Request $request): Response
    {
        // Récupérer les options de produit depuis la session
        $productOption = $request->getSession()->get('productOptions');
       
        //dd($productOption);
        // Vérifier si les options de produit ont été définies
        if ($productOption && isset($productOption['taille']) && isset($productOption['forme'] ) && $id ==  $productOption['productId']) {
            $taille = $productOption['taille'];
            $forme = $productOption['forme'];
            $quantity = $productOption['quantity'] ?? 0;
            // Ajouter le produit avec les options au panier
            $cart->add($id, $taille, $forme,$quantity);
           
        } else {
            // Ajouter le produit sans option au panier
            $cart->add($id);
            //dd($cart);
        }
        return $this->redirectToRoute('app_cart');
    }




    #[Route('/app_cart/add_inside_cart/{id}', name: 'add_inside_cart')]
public function addInsideCart(Cart $cart, $id, Request $request): Response
{
    // Récupérer les options de produit depuis la session
    $productOption = $request->getSession()->get('productOptions');
    $panier = $request->getSession()->get('cart',[]);
       
    // Vérifier si les options de produit ont été définies
    if ( $panier && isset( $productOption['taille'] ) && isset( $productOption['forme'] ) && $id ==  $productOption['productId'] ) {
        $taille = $productOption['taille'];
        $forme = $productOption['forme'];
        $quantity = $productOption['quantity'] ?? 1; // par défaut, la quantité est de 1

        // Ajouter le produit avec les options au panier
        //dd($cart);
        $cart->addInCart($id, $taille, $forme, $quantity);
    } else {
        // Ajouter le produit sans option au panier avec une quantité de 1
         //dd($cart);
        $cart->addInCart($id, null, null, null); 
       
    }

    return $this->redirectToRoute('app_cart');
}


    

    #[Route('/cart/remove', name: 'remove_my_cart')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();

        return $this->redirectToRoute('app_product');
    }

    #[Route('/cart/delete/{id}', name: 'delete_to_cart')]
    public function delete(Cart $cart, $id): Response
    {
        $cart->delete($id);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/decrease/{id}', name: 'decrease_to_cart')]
    public function decrease(Cart $cart, $id): Response
    {
        $cart->decrease($id);

        return $this->redirectToRoute('app_cart');
    }
}
