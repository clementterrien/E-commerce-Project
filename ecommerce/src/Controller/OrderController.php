<?php

namespace App\Controller;

use Exception;
use App\Entity\ConfirmedOrder;
use App\Service\Cart\CartService;
use App\Service\Order\OrderService;
use App\Repository\AdressRepository;
use App\Repository\ProductRepository;
use App\Service\Adress\AdressService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="order_preconfirmation")
     */
    public function preconfirmation(EntityManagerInterface $em, AdressRepository $adressRepo, SessionInterface $session, CartService $cartService, AdressService $adressService)
    {
        $user = $this->getUser();
        $adress = $adressService->getDefaultAdress();
        $cart = serialize($session->get('cart', []));
        $totalPrice = $cartService->getTotalPrice();
        $order = new ConfirmedOrder;
        $order
            ->setCart($cart)
            ->setUser($user)
            ->setCreatedAt(new \DateTime())
            ->setAdress($adress)
            ->setTotalPrice($totalPrice);

        $session->set('preorder', $order);

        if ($session->get('preorder', []) !== []) {
            return $this->redirectToRoute('order_confirmation');
        }
    }

    /**
     * @Route("/confirm", name="order_confirmation")
     */
    public function confirmation(SessionInterface $session, CartService $cartService, AdressService $adressService)
    {

        return $this->render('order/confirmorder.html.twig', [
            'cart' => $cartService->getFullCart(),
            'totalPrice' => $cartService->getTotalPrice(),
            'totalQuantity' => $cartService->getTotalQuantity(),
            'adress' => $adressService->getDefaultAdress(),
        ]);
    }

    /**
     * @Route("/merci", name="order_validated")
     */
    public function validateOrder(OrderService $orderService, CartService $cartService, SessionInterface $session)
    {
        $orderService->validateOrder();
        $cartService->removeCart();

        return $this->render('order/thanks.html.twig');
    }

    /**
     * @Route("/hello", name="hello")
     */
    public function hello(ProductRepository $productRepo, EntityManagerInterface $em)
    {
        $products = $productRepo->findAll();


        return $this->render('order/thanks.html.twig');
    }
}
