<?php

namespace App\Service\Webhook;

use App\Service\Order\OrderService;
use Symfony\Component\HttpFoundation\Response;

class WebhookService
{

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function handleChargeSucceeded($event)
    {
        $this->OrderService->triggerOrder($event);
    }

    public function handleChargeFailed($event)
    {
    }
}
