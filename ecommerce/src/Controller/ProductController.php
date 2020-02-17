<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\CreateProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/newproduct", name="product_create")
     */
    public function createProduct(Request $request)
    {
        $product = new Product;
        $manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(CreateProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/like/{product_id}", name="product_like")
     */
    public function likeProduct($product_id, ProductRepository $productRepo, EntityManagerInterface $em)
    {
        $product = $productRepo->findOneBy(['id' => $product_id]);
        $product->setLikeCounter($product->getLikeCounter() + 1);
        $em->flush();

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/product/{product_id}", name="product_details")
     */
    public function productDetails($product_id, ProductRepository $productRepo)
    {
        $product = $productRepo->findOneBy(['id' => $product_id]);
        return $this->render("product/product.html.twig", ["product" => $product]);
    }
}
