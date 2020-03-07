<?php

namespace App\Service\Payment;

use App\Service\Cart\CartService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\Encryption\EncryptionService;

class StripePaymentService
{
    protected $session;
    protected $cartService;

    public function __construct(SessionInterface $session, CartService $cartService, EncryptionService $encryptionService)
    {
        $this->session = $session;
        $this->cartService = $cartService;
        $this->encryption = $encryptionService;
    }

    /**
     * Function for Stripe API // Create a PaymentIntent to Stripe 
     * Stripe returns a request with this payment 
     */
    public function preStripePayment()
    {
        \Stripe\Stripe::setApiKey('sk_test_ZzEiJVT54kAAOxvzRxIyHY2K00Vr0AaYy6');

        $intent = \Stripe\PaymentIntent::create([
            'amount' => $this->cartService->getTotalPrice() * 100,
            'currency' => 'eur',
            'payment_method_types' => ['card'],
        ]);

        return $intent;
    }

    public function getStripePaymentIntent($stripePI_id)
    {
        \Stripe\Stripe::setApiKey('sk_test_ZzEiJVT54kAAOxvzRxIyHY2K00Vr0AaYy6');
        $stripePI = \Stripe\PaymentIntent::retrieve($stripePI_id);

        return $stripePI;
    }
}
