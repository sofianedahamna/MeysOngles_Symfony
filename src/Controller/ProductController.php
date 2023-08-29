<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use App\Form\ProductOptionType;
use App\Classe\Search;
use App\Entity\ProductOption;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/nos-produit', name: 'app_product')]
    public function index(Request $request): Response
    {
        
        $search = new Search();

        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
           $products=$this->entityManager->getRepository(Product::class)->findWithSearch($search);
        }else{
            $products = $this->entityManager->getRepository(Product::class)->findAll();
        }

        return $this->render('product/index.html.twig', [
            'products'=> $products,
            'form' => $form->createView()
        ]);
    }

    #[Route('/produit/{slug}', name: 'products')]
    public function show($slug, Request $request): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
        $products = $this->entityManager->getRepository(Product::class)->findByIsBest(1);
        $productId = $product->getId();
        
        
        // Créer le formulaire HTML pour sélectionner l'option de produit
        $form = $this->createForm(ProductOptionType::class, null);
            // on vérifie si le formulaire est soumis et valide
            $form->handleRequest($request);
            //dd($form);
             if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                return $this->redirectToRoute('add_to_cart', ['id' => $productId]);
            }
        
        
    
        return $this->render('product/show.html.twig', [
            'product' => $product,
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }
}