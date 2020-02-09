<?php

namespace App\Controller;

use App\Entity\FavoriteProduct;
use App\Repository\FavoriteProductRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
    /**
     * @Route("/favorite", name="favorite_show")
     */
    public function showFavoriteList()
    {
        $favoriteList = $this->getUser()->getFavoriteList();
        $favoriteProducts = $favoriteList->getFavoriteProducts();

        return $this->render('favorite/showFavorite.html.twig', [
            'favoriteProducts' => $favoriteProducts
        ]);
    }

    /**
     * @Route("/addtoFavorite/{product_id}", name="favorite_add")
     */
    public function addToFavoriteList($product_id, FavoriteProductRepository $favorite, ProductRepository $productRepo)
    {
        $favoriteList = $this->getUser()->getFavoriteList();
        $favoriteList_id = $favoriteList->getId();

        if ($favorite->findOneBy(['favoriteList' => $favoriteList_id, 'Product' => $product_id]) === null) {

            $new_favorite = new FavoriteProduct;
            $new_favorite->setFavoriteList($favoriteList);
            $productToAdd = $productRepo->findOneBy(['id' => $product_id]);
            dump($productToAdd);
            $new_favorite->setProduct($productToAdd);

            $em = $this->getDoctrine()->getManager();

            $em->persist($new_favorite);
            $em->flush();
        }

        return $this->redirectToRoute('favorite_show');
    }

    /**
     * @Route("/removeFromFavorite/{favoriteProduct_id}", name="favorite_remove")
     */
    public function removeFromFavoriteList($favoriteProduct_id, FavoriteProductRepository $FavoriteProductRepo)
    {
        $productToRemove = $FavoriteProductRepo->findOneBy(['id' => $favoriteProduct_id]);
        $em = $this->getDoctrine()->getManager();

        $em->remove($productToRemove);
        $em->flush();

        return $this->redirectToRoute('favorite_show');
    }
}
