<?php

namespace App\Service\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{

    protected $session;
    protected $productRepo;

    public function __construct(SessionInterface $session, ProductRepository $productRepo)
    {
        $this->session = $session;
        $this->productRepo = $productRepo;
    }

    public function add(int $product_id)
    {

        $cart = $this->session->get('cart', []);

        if (!empty($cart[$product_id])) {
            $cart[$product_id]++;
        } else {
            $cart[$product_id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    public function removeOne(int $product_id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$product_id])) {
            if ($cart[$product_id] == 1) {
                unset($cart[$product_id]);
            } else {
                $cart[$product_id]--;
            }
        } else {
            return;
        }

        $this->session->set('cart', $cart);
    }

    public function removeAll(int $product_id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$product_id])) {
            unset($cart[$product_id]);
        }

        $this->session->set('cart', $cart);
    }

    public function getFullCart()
    {
        $cart = $this->session->get('cart', []);
        $cartWithData = [];

        foreach ($cart as $product_id => $quantity) {
            $cartWithData[] = [
                'product' => $this->productRepo->findOneBy(['id' => $product_id]),
                'quantity' => $quantity
            ];
        }
        return $cartWithData;
    }

    public function getTotalPrice(): float
    {
        $totalPrice = 0;

        foreach ($this->getFullCart() as $item) {
            $totalPrice += $item['product']->getPrice() * $item['quantity'];
        }

        return $totalPrice;
    }

    public function getTotalQuantity(): int
    {
        $totalQuantity = 0;

        foreach ($this->getFullCart() as $item) {
            $totalQuantity += $item['quantity'];
        }

        return $totalQuantity;
    }
}
