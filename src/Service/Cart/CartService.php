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

    /**
     * Add a specific product to the cart of the current session
     */
    public function add(int $product_id, int $quantity)
    {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$product_id])) {
            $cart[$product_id] += $quantity;
        } else {
            $cart[$product_id] = $quantity;
        }
        $this->session->set('cart', $cart);
        $this->setFullCart();
    }

    /**
     * Remove one item specific product from the cart of the current session
     */
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
        $this->setFullCart();
    }

    /**
     * Remove all items of a specific product from the cart of the current session
     */
    public function removeAll(int $product_id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$product_id])) {
            unset($cart[$product_id]);
        }

        $this->session->set('cart', $cart);
    }

    /**
     * Remove the cart of the current session
     */
    public function removeCart()
    {
        $this->session->remove('cart');
    }

    /**
     * This function return the cart content
     * Return array with associative array // Entity $Product => int $quantity 
     */
    public function getFullCart(): array
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

    public function setFullCart()
    {
        $cartWithData = $this->getFullCart();
        $this->session->set('fullcart', $cartWithData);
    }

    /**
     * Return the cart content but the array returned is just
     * an association // product->id => quantity // this array is serializable to be stored in DB
     */
    public function getLightCart(): array
    {
        return $this->session->get('cart', []);
    }

    /**
     * Returns the Cart total price
     */
    public function getTotalPrice(): float
    {
        $totalPrice = 0;

        foreach ($this->getFullCart() as $item) {
            $totalPrice += $item['product']->getPrice() * $item['quantity'];
        }


        return $totalPrice;
    }

    /**
     * Returns the number of items in the cart
     */
    public function getTotalQuantity(): int
    {
        $totalQuantity = 0;

        foreach ($this->getFullCart() as $item) {
            $totalQuantity += $item['quantity'];
        }

        return $totalQuantity;
    }

    public function GetAvailableQuantity($product_id)
    {
        return $this->productRepo->findOneBy(['id' => $product_id])->getStock();
    }
}
