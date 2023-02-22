<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Repository\CategoryRepository;
use App\Repository\PanierRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController

// route pour index
{
    #[Route('/', name: 'app_index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('app/index.html.twig', [
            'Categoryname' => $categoryRepository->findAll(),
        ]);

    }


    // route pour ajouter au panier
    #[Route('/cart', name: 'app_cart', methods: ['GET', 'POST'])]
    public function cart(Request $request, \Doctrine\Persistence\ManagerRegistry $managerRegistry, ProductRepository $productRepository): Response
    {
        $User = $this->getUser('id');
        $produit = $request->request->get('product');
        $quantity = $request->request->get('quantity');
        $cart = new Panier();
        $cart->setUser($User);

        $product = $productRepository->find(intval($produit));
        $cart->setProduct($product);
        $cart->setQuantiter(intval($quantity));
        $managerRegistry->getManager()->persist($cart);
        $managerRegistry->getManager()->flush();
        return $this->redirectToRoute('app_index');
    }



    // route pour le produit

    #[Route('/product/{id}', name: 'app_product')]
    public function product($id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
        return $this->render('app/product.html.twig', [
            'product' => $product,
        ]);
    }


    // route pour la categorie

    #[Route('/category/{id}/{tri}', name: 'app_category')]
    public function category($id, CategoryRepository $categoryRepository, ProductRepository $productRepository, $tri): Response
    {
        if ($tri == 'asc') {
            $category = $categoryRepository->find($id);
            $product = $productRepository->findby(['category' => $id], ['prix' => 'ASC']);
        } elseif ($tri == 'desc') {
            $category = $categoryRepository->find($id);
            $product = $productRepository->findby(['category' => $id], ['prix' => 'DESC']);
        } else {
            $category = $categoryRepository->find($id);
            $product = 'rien';
        }
        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }
        return $this->render('app/categorie.html.twig', [
            'category' => $category,
            'product' => $product,
            'tri' => $tri,

        ]);
    }


    // route pour le panier
    #[Route('/panier', name: 'app_panier')]
    public function panier(PanierRepository $panierRepository): Response
    {
        $user = $this->getUser();
        $panier = $panierRepository->findby(['user' => $user]);
        return $this->render('app/panier.html.twig', [
            'panier' => $panier,
        ]);
    }





}