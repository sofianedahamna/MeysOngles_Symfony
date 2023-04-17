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
        $productOptions = $this->entityManager->getRepository(ProductOption::class)->getProductOptionsByProductId($productId);
    
        // Créer le formulaire HTML pour sélectionner l'option de produit
        $form = $this->createForm(ProductOptionType::class, null, [
            'productId' => $productId
        ]);
        // Récupérer la valeur de $productOption depuis la session
        $selectedOption = $request->getSession()->get('productOptions');
    
        if ($request->isMethod('POST')) {
            if (empty($productOptions)) {
                // Si le produit n'a pas d'option, on redirige directement vers l'action add_to_cart
                return $this->redirectToRoute('add_to_cart', ['id' => $productId]);
            }
            // Sinon, on vérifie si le formulaire est soumis et valide
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $productOption = [
                    'taille' => $data->getTaille(),
                    'forme' => $data->getForme(),
                    'productId' => $productId,
                    'quantity' => 1
                ];
                $request->getSession()->set('productOptions', $productOption);
                return $this->redirectToRoute('add_to_cart', ['id' => $productId]);
            }
        }
        
    
        return $this->render('product/show.html.twig', [
            'product' => $product,
            'products' => $products,
            'productOptions' => $productOptions,
            'selectedOption' => $selectedOption,
            'form' => $form->createView(),
        ]);
    }
    
    
    

}
