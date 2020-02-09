<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bridge\Twig\Node\DumpNode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/mycart", name="cart_show")
     */
    public function showMyCart(SessionInterface $session, ProductRepository $productRepo)
    {

        $cart = $session->get('cart', []);
        $cartWithData = [];

        foreach ($cart as $product_id => $quantity) {
            $cartWithData[] = [
                'product' => $productRepo->findOneBy(['id' => $product_id]),
                'quantity' => $quantity
            ];
        }

        $totalPrice = 0;
        $totalQuantity = 0;

        foreach ($cartWithData as $item) {
            $productPrice = $item['product']->getPrice() * $item['quantity'];
            $totalPrice += $productPrice;
            $totalQuantity += $item['quantity'];
        }

        return $this->render('cart/cart.html.twig', [
            'cart' => $cartWithData,
            'totalPrice' => $totalPrice,
            'totalQuantity' => $totalQuantity
        ]);
    }

    /**
     * @Route("/addtomycart/{product_id}", name="cart_add")
     */
    public function addToMyCart($product_id, SessionInterface $session)
    {
        $cart = $session->get('cart', []);

        if (!empty($cart[$product_id])) {
            $cart[$product_id]++;
        } else {
            $cart[$product_id] = 1;
        }

        $session->set('cart', $cart);
        dump($session->get('cart'));

        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/removefrommycart/{product_id}", name="cart_remove_one")
     */
    public function removeOneItemFromCart($product_id, SessionInterface $session)
    {
        $cart = $session->get('cart', []);
        // dd($cart[$product_id]);

        if (!empty($cart[$product_id])) {
            if ($cart[$product_id] == 1) {
                unset($cart[$product_id]);
            } else {
                $cart[$product_id]--;
            }
        } else {
            return;
        }

        $session->set('cart', $cart);
        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/removefromcart/{product_id}", name="cart_remove")
     */
    public function removeItemFromCart($product_id, SessionInterface $session)
    {
        $cart = $session->get('cart', []);

        if (!empty($cart[$product_id])) {
            unset($cart[$product_id]);
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart_show');
    }
}
