<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\TestType;
use Symfony\Component\Asset\Package;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/test", name="test")
     */
    public function test(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(TestType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                dd('ok');
            } else {
                $this->addFlash('failure', 'Mauvais mot de passe');
                $this->redirectToRoute('test');
            }
        }

        return $this->render('/test/test.html.twig', [
            'form' => $form->createView()
        ]);
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
