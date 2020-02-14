<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\Product\ProductService;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * @Route("/hello", name="hello")
     */
    public function hello(ProductRepository $productRepo, EntityManagerInterface $em)
    {
        $mailer = sfContext::getInstance()->getMailer();


        return $this->redirectToRoute('home');
    }
}
