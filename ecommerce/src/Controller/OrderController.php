<?php

namespace App\Controller;

use App\Entity\ConfirmedOrder;
use App\Service\Cart\CartService;
use App\Form\ProceedToPaymentType;
use App\Repository\UserRepository;
use App\Service\Order\OrderService;
use App\Service\Adress\AdressService;
use App\Service\Payment\StripePaymentService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends AbstractController
{
    /**
     * @Route("/buy/overview", name="order_preconfirmation")
     */
    public function preconfirmation(
        SessionInterface $session,
        CartService $cartService,
        StripePaymentService $paymentService,
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
    public function stripe(OrderService $orderService, Request $request, UserRepository $userRepo)
    {
        \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));

        // If you are testing your webhook locally with the Stripe CLI you
        // can find the endpoint's secret by running `stripe listen`
        // Otherwise, find your endpoint's secret in your webhook settings in the Developer Dashboard
        $endpoint_secret = 'whsec_Ki2A69SIxbinDXVBLKhayrJt3AKZF6FT';

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return new Response('Invalid Payload', http_response_code(400));
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return new Response('Invalid Signature', http_response_code(400));
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'charge.succeeded':
                // $cart = $event->data->object->metadata->cart;
                $orderService->triggerOrder($event);
                break;
            case 'payment_method.attached':
                $paymentMethod = $event->data->object; // contains a StripePaymentMethod
                break;
                // ... handle other event types
            default:
                return new Response('other case', http_response_code(400));
                exit();
        }

        // $orderService->triggerOrder($event);
        return new Response($event, http_response_code(200));
    }

    /**
     * @Route("/maman", name="order_encoreuntest")
     */
    public function maman(OrderService $orderService, CartService $cartService)
    {

        // $orderService->manageStocks(serialize($cartService->getLightCart()));

        return $this->redirectToRoute('cart_show');
    }
}
