<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/mycart", name="cart_show")
     */
    public function showMyCart(CartService $service)
    {
        return $this->render('cart/cart.html.twig', [
            'cart' => $service->getFullCart(),
            'totalPrice' => $service->getTotalPrice(),
            'totalQuantity' => $service->getTotalQuantity()
        ]);
    }

    /**
     * @Route("/addtomycart/{product_id}", name="cart_add")
     */
    public function addToMyCart($product_id, CartService $service)
    {
        $service->add($product_id);

        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/removefrommycart/{product_id}", name="cart_remove_one")
     */
    public function removeOneItemFromCart($product_id, CartService $service)
    {
        $service->removeOne($product_id);

        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/removefromcart/{product_id}", name="cart_remove")
     */
    public function removeItemFromCart($product_id, CartService $service)
    {
        $service->removeAll($product_id);

        return $this->redirectToRoute('cart_show');
    }
}
