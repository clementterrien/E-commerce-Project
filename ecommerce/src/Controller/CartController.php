<?php

namespace App\Controller;

use App\Service\Adress\AdressService;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/mycart", name="cart_show")
     */
    public function showMyCart(CartService $cartService, AdressService $adressService)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('cart/cart.html.twig', [
            'user' => $this->getUser(),
            'cart' => $cartService->getFullCart(),
            'totalPrice' => $cartService->getTotalPrice(),
            'totalQuantity' => $cartService->getTotalQuantity(),
            'adress' => $adressService->getDefaultAdress(),
            'adresses' => $adressService->getFullAdresses()
        ]);
    }

    /**
     * @Route("/addtomycart/{product_id}", name="cart_add")
     */
    public function addToMyCart($product_id, CartService $service, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($request->isMethod('POST')) {
            $expectedQuantity = $request->request->get('quantity');
            $availableQuantity = $service->GetAvailableQuantity($product_id);
            if ($availableQuantity >= $expectedQuantity) {
                $service->add($product_id, $expectedQuantity);
            } else {
                $this->addFlash("failure", "Vous ne pouvez commander plus de " . $availableQuantity . " bouteille(s) pour cette référence");
                return $this->redirectToRoute('home');
            }
        }
        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/removefrommycart/{product_id}", name="cart_remove_one")
     */
    public function removeOneItemFromCart($product_id, CartService $service)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $service->removeOne($product_id);

        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/removefromcart/{product_id}", name="cart_remove")
     */
    public function removeItemFromCart($product_id, CartService $service)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $service->removeAll($product_id);

        return $this->redirectToRoute('cart_show');
    }
}
