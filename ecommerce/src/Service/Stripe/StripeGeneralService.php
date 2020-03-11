<?php

namespace App\Service\Stripe;

use Doctrine\DBAL\Types\ObjectType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Json;

class StripeGeneralService
{
    protected $publickey;
    protected $secretkey;

    public function __construct($publickey, $secretkey)
    {
        $this->publickey = $publickey;
        $this->secretkey = $secretkey;
    }

    /**
     * Returns a specific paymentIntent in JSON with his Stripe_id
     */
    public function getStripePaymentIntent($stripePI_id): object
    {
        \Stripe\Stripe::setApiKey($this->secretkey);
        $stripePI = \Stripe\PaymentIntent::retrieve($stripePI_id);

        return $stripePI;
    }

    /**
     * Store the id of the order(in local DB) in the Stripe PaymentIntent (in Stripe DB)
     */
    public function storeOrderIdInStripePaymentIntent(int $order_id, string $stripePI_id)
    {
        \Stripe\Stripe::setApiKey('sk_test_ZzEiJVT54kAAOxvzRxIyHY2K00Vr0AaYy6');

        \Stripe\PaymentIntent::update(
            $stripePI_id,
            [
                'metadata' => [
                    'order_id' => $order_id
                ]
            ]
        );
    }
}
