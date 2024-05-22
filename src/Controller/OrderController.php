<?php

namespace App\Controller;

use App\Entity\ConfirmedOrder;
use App\Service\Cart\CartService;
use App\Form\ProceedToPaymentType;
use App\Repository\ConfirmedOrderRepository;
use App\Service\Order\OrderService;
use App\Service\Adress\AdressService;
use App\Service\Stripe\PaymentService;
use App\Service\Stripe\StripeGeneralService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    /**
     * @Route("/buy/overview", name="order_preconfirmation")
     */
    public function preconfirmation(
        SessionInterface $session,
        CartService $cartService,
        PaymentService $paymentService,
        AdressService $adressService
    ) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!empty($session->get('cart'))) {
            $user = $this->getUser();
            $preOrder = (new ConfirmedOrder)
                ->setUser($user)
                ->setAdress($adressService->getDefaultAdress())
                ->setCart(serialize($cartService->getLightCart()))
                ->setTotalPrice($cartService->getTotalPrice())
                ->setStripePaymentID('temp');

            $intent = $paymentService->preStripePayment($preOrder);
            $form = $this->createForm(ProceedToPaymentType::class, null, [
                'attr' => ['id' => 'payment-form'],
            ]);
            return $this->render('order/confirmorder.html.twig', [
                'cart' => $cartService->getFullCart(),
                'totalPrice' => $cartService->getTotalPrice(),
                'totalQuantity' => $cartService->getTotalQuantity(),
                'adress' => $adressService->getDefaultAdress(),
                'form' => $form->createView(),
                'clientsecret' => $intent['client_secret'],
                'stripe_public_key' => $this->getParameter('stripe_public_key'),
                'redirect' => '/confirmed'
            ]);
        } else {
            $this->redirectToRoute('cart_show');
        }
    }

    /**
     * @Route("/confirmed", name="order_validated")
     */
    public function validateOrder(OrderService $orderService, CartService $cartService, SessionInterface $session)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));

        return $this->render('order/thanks.html.twig');
    }

    /**
     * @Route("/webhook", name="order_stripewebhook")
     */
    public function handlePaymentWebhook(PaymentService $paymentService)
    {
        return $paymentService->handlePaymentWebhook();
    }

    /**
     * @Route("/maman", name="order_encoreuntest")
     */
    public function maman(ConfirmedOrderRepository $repo, StripeGeneralService $stripeService)
    {
        $oh = gettype($stripeService->getStripePaymentIntent('pi_1GLR3VATjl2NQrq3EXJqlEFB'));
        // $orderService->manageStocks(serialize($cartService->getLightCart()));
        dd($oh);
        return $this->redirectToRoute('cart_show');
    }
}
