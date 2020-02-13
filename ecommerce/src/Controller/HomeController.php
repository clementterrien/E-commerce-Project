<?php

namespace App\Controller;

use App\Service\Product\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(ProductService $productService)
    {
        $productService->getTop3MostLikedProducts();

        return $this->render('/home/home.html.twig');
    }
}
