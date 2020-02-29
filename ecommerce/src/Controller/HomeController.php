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
        
        $formQuery = $request->query->all();
        $key = key($formQuery);

        $filteredProducts = null;
        
        dump($key);
        if(!is_null($key) && $key !== "page")
        {
            $value = $request->query->get($key);
            $filteredProducts = $paginator->paginate($productService->getProductsByCriteria($key, $value), $request->query->getInt('page', 1));
        }


        $topThreeProducts = $productService->getTop3MostLikedProducts();
        $allTheProducts = $paginator->paginate($productService->getAllTheProducts(), 
            $request->query->getInt('page', 1),
            12);

        return $this->render('/home/home.html.twig', [
            "topThreeProducts" => $topThreeProducts,
            "allTheProducts" => $allTheProducts,
            "filteredProducts" => $filteredProducts
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
