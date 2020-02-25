<?php

namespace App\Controller;

use Exception;
use App\Service\Cart\CartService;
use App\Service\Order\OrderService;
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
    public function preconfirmation(SessionInterface $session, OrderService $orderService)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $orderService->storeCartInSession();

        if ($session->get('preorder', []) !== []) {
            return $this->redirectToRoute('order_confirmation');
        }
    }

    /**
     * @Route("/confirm", name="order_confirmation")
     */
    public function confirmation(SessionInterface $session, CartService $cartService, AdressService $adressService)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $orderService->validateOrder();
        $cartService->removeCart();

        return $this->render('order/thanks.html.twig');
    }

    /**
     * @Route("/tele", name="tele")
     */
    public function tele(ProductRepository $productRepo, EntityManagerInterface $em)
    {
        $products = $productRepo->findAll();


        return $this->render('order/thanks.html.twig');
    }
}
