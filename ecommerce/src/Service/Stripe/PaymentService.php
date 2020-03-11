<?php

namespace App\Service\Stripe;

use App\Entity\ConfirmedOrder;
use App\Service\Cart\CartService;
use App\Service\Order\OrderService;
use Symfony\Component\HttpFoundation\Response;

class PaymentService
{
    protected $publickey;
    protected $secretkey;
    protected $cartService;
    protected $orderService;

    public function __construct($publickey, $secretkey, CartService $cartService, OrderService $orderService)
    {
        $this->publickey = $publickey;
        $this->secretkey = $secretkey;
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    /**
     * Function for Stripe API // Create a PaymentIntent to Stripe 
     * Stripe returns a request with this paymentIntent in JSON 
     */
    public function preStripePayment(ConfirmedOrder $confirmedOrder): object
    {
        \Stripe\Stripe::setApiKey($this->secretkey);
        $intent = \Stripe\PaymentIntent::create([
            'amount' => $this->cartService->getTotalPrice() * 100,
            'currency' => 'eur',
            'payment_method_types' => ['card'],
            'metadata' =>
            [
                'user_id' => $confirmedOrder->getUser()->getId(),
                'adress_id' => $confirmedOrder->getAdress()->getId(),
                'cart' => $confirmedOrder->getCart(),
                'totalprice' => $confirmedOrder->getTotalPrice()
            ]
        ]);

        return $intent;
    }

    /**
     * Function call a defined Stripe Webhook, it verify that's the event sent is trully sent by stripe
     * verifying the unique endpoint secret inherited from the matching webhook 
     * Next it can handle the event and trigger actions depending on the event data. 
     * It must return an HTTP response to the API
     */
    public function HandlePaymentWebhook(): object
    {
        \Stripe\Stripe::setApiKey($this->secretkey);

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
                $this->orderService->triggerOrder($event);
                break;
            case 'payment_method.attached':
                $paymentMethod = $event->data->object; // contains a StripePaymentMethod
                break;
                // ... handle other event types
            default:
                return new Response('other case', http_response_code(400));
                exit();
        }

        return new Response($event, http_response_code(200));
    }
}
