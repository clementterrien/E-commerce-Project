<?php

namespace App\Controller;

use Stripe\Webhook;
use App\Service\Cart\CartService;
use App\Form\ProceedToPaymentType;
use App\Service\Order\OrderService;
use App\Service\Adress\AdressService;
use App\Service\Webhook\WebhookService;
use App\Service\Payment\StripePaymentService;
use Psr\Log\LoggerInterface;
use Stripe\Stripe;
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
            $intent = $paymentService->preStripePayment();
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

        $endpoint = \Stripe\WebhookEndpoint::create([
            'url' => 'https://6600cfb0.ngrok.io/webhook',
            'enabled_events' => ['charge.failed', 'charge.succeeded'],
        ]);

        return $this->render('order/thanks.html.twig');
    }

    /**
     * @Route("/webhook", name="order_stripewebhook")
     */
    public function stripe(OrderService $orderService, Request $request)
    {
        \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));

        // If you are testing your webhook locally with the Stripe CLI you
        // can find the endpoint's secret by running `stripe listen`
        // Otherwise, find your endpoint's secret in your webhook settings in the Developer Dashboard
        $endpoint_secret = 'whsec_XM1Xu97NfE18kftfJJyjjjkOxknIChBc';

        $payload = @file_get_contents('php://input');
        $sig_header = $request->server->get('HTTP_HOST');

        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        // // Handle the event
        // switch ($event->type) {
        //     case 'payment_intent.succeeded':
        //         $paymentIntent = $event->data->object; // contains a StripePaymentIntent
        //         break;
        //     case 'payment_method.attached':
        //         $paymentMethod = $event->data->object; // contains a StripePaymentMethod
        //         break;
        //         // ... handle other event types
        //     default:
        //         // Unexpected event type
        //         http_response_code(400);
        //         exit();
        // }

        return new Response();
    }
}
