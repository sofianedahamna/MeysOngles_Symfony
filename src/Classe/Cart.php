<?php

namespace App\Classe;

use App\Entity\Product;
use App\Entity\ProductOption;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{

    private $session;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function add($id, $taille = null, $forme = null, $quantity = 1)
    {
        $cart = $this->session->get('cart', []);
    
        if (!empty($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
            if ($taille !== null && $forme !== null) {
                $found = false;
                foreach ($cart[$id]['productOption'] as &$option) {
                    if ($option['taille'] === $taille && $option['forme'] === $forme) {
                        $option['quantity'] += $quantity;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $cart[$id]['productOption'][] = ['productId' => $id, 'taille' => $taille, 'forme' => $forme, 'quantity' => $quantity];
                }
            }
        } else {
            $cart[$id] = ['quantity' => $quantity, 'productOption' => []];
            if ($taille !== null && $forme !== null) {
                $cart[$id]['productOption'][] = ['productId' => $id, 'taille' => $taille, 'forme' => $forme, 'quantity' => $quantity];
            }
        }
    
        $this->session->set('cart', $cart);
    }
    

    public function addInCart($id, $taille = null, $forme = null, $quantity = 1)
    {
        $cart = $this->session->get('cart', []);
        //dd($cart);
    
        if (empty($cart[$id]['productOption'])) {
            //dd($cart[$id]['productOption']);
            if (($cart[$id]) != null) {
                //dd(($cart[$id]));
                // Si le produit est déjà dans le panier, incrémenter la quantité
                $cart[$id]['quantity'] ++;
                //dd($cart[$id]['quantity']);
            } else {
                // Si le produit n'est pas encore dans le panier, l'ajouter avec la quantité spécifiée
                $cart[$id] = [
                    'quantity' => $quantity,
                    'productOption' => []
                ];
            }
            
        } else {
            if (!empty($cart[$id])) {
                // Rechercher l'option correspondante si elle existe déjà
                $optionIndex = -1;
                foreach ($cart[$id]['productOption'] as $i => $productOption) {
                    if ($productOption['taille'] === $taille && $productOption['forme'] === $forme) {
                        $optionIndex = $i;
                        break;
                    }
                }
                // Si l'option existe, mettre à jour la quantité, sinon l'ajouter
                if ($optionIndex >= 0) {
                    $cart[$id]['productOption'][$optionIndex]['quantity'] += $quantity;
                } else {
                    $cart[$id]['productOption'][] = [
                        'productId' => $id,
                        'taille' => $taille,
                        'forme' => $forme,
                        'quantity' => $quantity,
                    ];
                }
                // Mettre à jour la quantité totale
                $cart[$id]['quantity'] ++;
            } else {
                $cart[$id] = [
                    'quantity' => $quantity,
                    'productOption' => [
                        [
                            'productId' => $id,
                            'taille' => $taille,
                            'forme' => $forme,
                            'quantity' => $quantity,
                        ],
                    ],
                ];
            }
        }
    
        $this->session->set('cart', $cart);
    }
    

    public function get()
    {
        return $this->session->get('cart');
    }

    public function remove()
    {
        return $this->session->remove('cart');
    }

    public function delete($id)
    {
        $cart = $this->session->get('cart', []);

        unset($cart[$id]);

        return $this->session->set('cart', $cart);
    }

    public function decrease($id)
    {
        $cart = $this->session->get('cart', []);
    
        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
                if (count($cart[$id]['productOption']) > 0) {
                    $productOption = array_pop($cart[$id]['productOption']);
                    // décrémenter la quantité de productOption s'il y en a déjà dans le panier
                    if (isset($productOption['quantity']) && $productOption['quantity'] > 1) {
                        $productOption['quantity']--;
                        $cart[$id]['productOption'][] = $productOption;
                    }
                }
            } else {
                unset($cart[$id]);
            }
        }
    
        return $this->session->set('cart', $cart);
    }
    
    

    public function getFull()
    {
        $cartComplete = [];
    
        if ($this->get()) {
            foreach ($this->get() as $id => $cartItem) {
                $product_object = $this->entityManager->getRepository(Product::class)->findOneById($id);
    
                if (!$product_object) {
                    $this->delete($id);
                    continue;
                }
    
                $productOptions = [];
                if (isset($cartItem['productOption'])) {
                    foreach ($cartItem['productOption'] as $optionId) {
                        $productOption = $this->entityManager->getRepository(ProductOption::class)->findOneById($optionId);
    
                        if (!$productOption) {
                            continue;
                        }
    
                        $productOptions[] = $productOption;
                    }
                }
    
                $cartComplete[] = [
                    'product' => $product_object,
                    'quantity' => $cartItem['quantity'],
                    'productOption' => $productOptions,
                ];
            }
        }
    
        return $cartComplete;
    }
    
    
    
}
