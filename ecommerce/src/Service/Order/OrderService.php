<?php

namespace App\Service\Order;

use App\Entity\ConfirmedOrder;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderService
{

    protected $session;
    protected $em;
    protected $productRepo;

    public function __construct(SessionInterface $session, EntityManagerInterface $em, ProductRepository $productRepo)
    {
        $this->session = $session;
        $this->em = $em;
        $this->productRepo = $productRepo;
    }

    public function validateOrder()
    {

        $order = $this->session->get('preorder', []);

        if (!$order instanceof ConfirmedOrder) {
            throw new Exception("oups");
        }

        $this->manageStocks($order->getCart());

        $order = $this->em->merge($order);
        $this->em->persist($order);
        $this->em->flush();
    }

    public function manageStocks(string $cart)
    {
        $cart = unserialize($cart);
        foreach ($cart as $product_id => $quantity) {
            $product = $this->productRepo->findOneBy(['id' => $product_id]);
            $product->setOrderCounter($product->getOrderCounter() + $quantity);
            $product->setStock($product->getStock() - $quantity);
            $this->em->persist($product);
        }
        $this->em->flush();
    }
}
