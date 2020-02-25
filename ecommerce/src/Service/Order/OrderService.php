<?php

namespace App\Service\Order;

use App\Entity\ConfirmedOrder;
use App\Service\Cart\CartService;
use App\Repository\ProductRepository;
use App\Service\Adress\AdressService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderService
{

    protected $session;
    protected $em;
    protected $productRepo;
    protected $user;
    protected $adressService;
    protected $cartService;

    public function __construct(
        SessionInterface $session,
        EntityManagerInterface $em,
        ProductRepository $productRepo,
        Security $security,
        AdressService $adressService,
        CartService $cartService
    ) {
        $this->session = $session;
        $this->em = $em;
        $this->productRepo = $productRepo;
        $this->user = $security->getUser();
        $this->adressService = $adressService;
        $this->cartService = $cartService;
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

    public function storeCartInSession()
    {
        $adress = $this->adressService->getDefaultAdress();
        $cart = serialize($this->session->get('cart', []));
        $totalPrice = $this->cartService->getTotalPrice();
        $order = new ConfirmedOrder;
        $order
            ->setCart($cart)
            ->setUser($this->user)
            ->setCreatedAt(new \DateTime())
            ->setAdress($adress)
            ->setTotalPrice($totalPrice);

        $this->session->set('preorder', $order);
    }
}
