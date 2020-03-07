<?php

namespace Application\BackendBundle\EventListener;

use App\Service\Email\EmailService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Entity\ConfirmedOrder;
use App\Service\Cart\CartService;
use App\Service\Order\OrderService;

class ConfirmedOrderEntityListener
{
    protected $emailService;

    public function __construct(EmailService $emailService, OrderService $orderService, CartService $cartService)
    {
        $this->emailService = $emailService;
        $this->orderService = $orderService;
        $this->cartService = $cartService;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof ConfirmedOrder) {
            $entity->setCreatedAt(new \DateTime());
            $this->orderService->manageStocks($entity->getCart());
            $this->cartService->removeCart();
        }
    }
}
