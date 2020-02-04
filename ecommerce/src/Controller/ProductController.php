<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\CreateProductType;
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
}
