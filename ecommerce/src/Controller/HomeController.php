<?php

namespace App\Controller;

use App\Repository\AdressRepository;
use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
use App\Service\Product\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(ProductService $productService)
    {
        $topThreeProducts = $productService->getTop3MostLikedProducts();
        $allTheProducts = $productService->getAllTheProducts();

        return $this->render('/home/home.html.twig', [
            "topThreeProducts" => $topThreeProducts,
            "allTheProducts" => $allTheProducts
        ]);
    }

    /**
     * @Route("/hubert", name="hubert")
     */
    public function hubert(ProductRepository $productRepo, EntityManagerInterface $em, SessionInterface $session)
    {
        return $this->redirectToRoute('home');
    }
}
