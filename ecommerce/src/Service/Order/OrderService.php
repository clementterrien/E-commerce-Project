<?php

namespace App\Service\Order;

use App\Entity\ConfirmedOrder;
use App\Repository\ConfirmedOrderRepository;
use App\Service\Cart\CartService;
use App\Repository\ProductRepository;
use App\Service\Adress\AdressService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderService
{

    protected $session;
    protected $em;
    protected $productRepo;
    protected $orderRepo;
    protected $user;
    protected $adressService;
    protected $cartService;

    public function __construct(
        SessionInterface $session,
        EntityManagerInterface $em,
        ProductRepository $productRepo,
        Security $security,
        AdressService $adressService,
        CartService $cartService,
        ConfirmedOrderRepository $orderRepo
    ) {
        $this->session = $session;
        $this->em = $em;
        $this->productRepo = $productRepo;
        $this->user = $security->getUser();
        $this->adressService = $adressService;
        $this->cartService = $cartService;
        $this->orderRepo = $orderRepo;
    }

    private function manageStocks(string $cart)
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

    private function storeCartInSession()
    {
        $adress = $this->adressService->getDefaultAdress();
        $cart = serialize($this->session->get('cart', []));
        $totalPrice = $this->cartService->getTotalPrice();
        $order = new ConfirmedOrder;
        $order
            ->setCart($cart)
            ->setUser($this->security->getUser())
            ->setCreatedAt(new \DateTime())
            ->setAdress($adress)
            ->setTotalPrice($totalPrice);

        $this->session->set('preorder', $order);
    }

    private function createOrderInDB($stripePI_id)
    {
        $order_adress = $this->adressService->getDefaultAdress();
        $order = (new ConfirmedOrder())
            ->setUser($this->user)
            ->setCart(serialize($this->cartService->getLightCart()))
            ->setAdress($order_adress)
            ->setTotalPrice($this->cartService->getTotalPrice())
            ->setStripePaymentID($stripePI_id);

        $this->em->persist($order);
        $this->em->flush();
    }


    public function storeOrderIdInStripePaymentIntent(int $order_id, $stripePI_id)
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

    public function triggerOrder($event)
    {
        $this->createOrderInDB($event->data->object->payment_intent);
        $this->manageStocks(serialize($this->cartService->getLightCart()));
        $stripePI_id = $event->data->object->payment_intent;
        $order_id = $this->orderRepo->findOneBy(['stripePaymentId' => $stripePI_id])->getId();
        $this->storeOrderIdInStripePaymentIntent($order_id, $stripePI_id);
    }
}
