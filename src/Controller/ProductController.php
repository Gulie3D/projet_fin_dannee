<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/nos-produits', name: 'app_products')]
    public function index(): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll(); //on récupère l'entité product avec toutes ces données 

        // $search = new Search();
        // $form = $this->createForm(SearchType::class, $search);
        // if($form->isSubmitted() && $form->isValid())
        // {
        //     $product = $this->entityManager->getRepository(Product::class)->findWitchSearch();

        // }


        return $this->render('product/index.html.twig', [
            'products' => $products,
        //    'form' => $form->createView(),
        ]);
    }

    #[Route('/produit/{slug}', name: 'app_product')]
    public function show($slug): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug); //on récupère l'entité product avec toutes les données du slug récupéré

        if(!$product)
        {
            return $this->redirectToRoute('app_products');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
}
