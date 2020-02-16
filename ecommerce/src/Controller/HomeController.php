<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use App\Repository\AdressRepository;
use App\Repository\ProductRepository;
use App\Service\Product\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(ProductService $productService, PaginatorInterface $paginator, Request $request)
    {
        $topThreeProducts = $productService->getTop3MostLikedProducts();
        $allTheProducts = $paginator->paginate($productService->getAllTheProducts(), 
            $request->query->getInt('page', 1),
            12);

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
