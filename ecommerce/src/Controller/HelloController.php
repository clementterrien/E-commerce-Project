<?php

namespace App\Controller;

use Symfony\Component\Asset\Package;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;

class HelloController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     */
    public function hello()
    {
        return $this->render('/hello/index.html.twig');
    }

    /**
     * @Route("/huber", name="huber")
     */
    public function huber(ProductRepository $productRepo, EntityManagerInterface $em)
    {
        $products = $productRepo->findAll();
        foreach ($products as $key => $product) {
            $product->setStock(100);
            $em->persist($product);
        }
        $em->flush();

        return $this->redirectToRoute('home');
    }
}
