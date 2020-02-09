<?php

namespace App\Controller;

use App\Service\Favorite\FavoriteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
    /**
     * @Route("/favorite", name="favorite_show")
     */
    public function showFavoriteList()
    {
        return $this->render('favorite/showFavorite.html.twig', [
            'favoriteProducts' => $this->getUser()->getFavoriteList()->getFavoriteProducts()
        ]);
    }

    /**
     * @Route("/addtoFavorite/{product_id}", name="favorite_add")
     */
    public function addToFavoriteList($product_id, FavoriteService $service)
    {
        $service->add($product_id);

        return $this->redirectToRoute('favorite_show');
    }

    /**
     * @Route("/removeFromFavorite/{favoriteProduct_id}", name="favorite_remove")
     */
    public function removeFromFavoriteList($favoriteProduct_id, FavoriteService $service)
    {
        $service->remove($favoriteProduct_id);

        return $this->redirectToRoute('favorite_show');
    }
}
