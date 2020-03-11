<?php

namespace App\Service\Order;

use App\Entity\User;
use App\Entity\ConfirmedOrder;
use App\Repository\AdressRepository;
use App\Service\Cart\CartService;
use App\Repository\ProductRepository;
use App\Service\Adress\AdressService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConfirmedOrderRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderService
{

    protected $session;
    protected $em;
    protected $productRepo;
    protected $orderRepo;
    public $user;
    protected $adressService;
    protected $cartService;
    protected $userRepo;
    protected $adressRepo;

    public function __construct(
        SessionInterface $session,
        EntityManagerInterface $em,
        ProductRepository $productRepo,
        Security $security,
        AdressService $adressService,
        CartService $cartService,
        ConfirmedOrderRepository $orderRepo,
        UserRepository $userRepo,
        AdressRepository $adressRepo
    ) {
        $this->session = $session;
        $this->em = $em;
        $this->productRepo = $productRepo;
        $this->user = $security->getUser();
        $this->adressService = $adressService;
        $this->cartService = $cartService;
        $this->orderRepo = $orderRepo;
        $this->userRepo = $userRepo;
        $this->adressRepo = $adressRepo;
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

    public function createOrderInDB($event)
    {
        $user = $this->userRepo->findOneBy(['id' => $event->data->object->metadata->user_id]);
        $adress = $this->adressRepo->findOneBy(['id' => $event->data->object->metadata->adress_id]);
        $cart = $event->data->object->metadata->cart;
        $totalPrice = $event->data->object->metadata->totalprice;
        $stripePI_id = $event->data->object->payment_intent;

        $order = (new ConfirmedOrder())
            ->setUser($user)
            ->setCart($cart)
            ->setAdress($adress)
            ->setTotalPrice($totalPrice)
            ->setStripePaymentID($stripePI_id);

        $this->em->persist($order);
        $this->em->flush();
    }


    // public function storeOrderIdInStripePaymentIntent(int $order_id, $stripePI_id)
    // {
    //     \Stripe\Stripe::setApiKey('sk_test_ZzEiJVT54kAAOxvzRxIyHY2K00Vr0AaYy6');

    //     \Stripe\PaymentIntent::update(
    //         $stripePI_id,
    //         [
    //             'metadata' => [
    //                 'order_id' => $order_id
    //             ]
    //         ]
    //     );
    // }

    public function triggerOrder($event)
    {
        $stripePI_id = $event->data->object->payment_intent;
        $this->createOrderInDB($event);
        $this->manageStocks($event->data->object->metadata->cart);
        $order_id = $this->orderRepo->findOneBy(['stripePaymentId' => $stripePI_id])->getId();
        $this->storeOrderIdInStripePaymentIntent($order_id, $stripePI_id);
    }
}
