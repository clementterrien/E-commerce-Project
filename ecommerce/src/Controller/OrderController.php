<?php

namespace App\Controller;

use Exception;
use App\Service\Cart\CartService;
use App\Form\ProceedToPaymentType;
use App\Service\Order\OrderService;
use App\Repository\ProductRepository;
use App\Service\Adress\AdressService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    public function confirmation(SessionInterface $session, CartService $cartService, OrderService $orderService, AdressService $adressService, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!empty($session->get('cart'))) {
            $intent = $orderService->preStripePayment();
        } else {
            $this->redirectToRoute('cart_show');
        }

        $form = $this->createForm(ProceedToPaymentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $oh = \Stripe\PaymentIntent::retrieve(
                $intent['id']
            );

            dd($oh);


            return $this->redirectToRoute('adress_show');
        }


        return $this->render('order/confirmorder.html.twig', [
            'cart' => $cartService->getFullCart(),
            'totalPrice' => $cartService->getTotalPrice(),
            'totalQuantity' => $cartService->getTotalQuantity(),
            'adress' => $adressService->getDefaultAdress(),
            'form' => $form->createView(),
            'intent' => $intent['client_secret']
        ]);
    }

    /**
     * @Route("/testons", name="order_test")
     */
    public function teststripe()
    {
        dd('testons');
    }
    /**
     * 
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
