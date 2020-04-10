<?php

namespace App\Service\Favorite;

use App\Entity\FavoriteProduct;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FavoriteProductRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FavoriteService
{

    protected $user;
    protected $security;
    protected $session;
    protected $favoriteList;
    protected $favoriteListRepo;
    protected $productRepo;
    protected $em;

    public function __construct(
        SessionInterface $session,
        Security $security,
        FavoriteProductRepository $favoriteProductRepo,
        ProductRepository $productRepo,
        EntityManagerInterface $em
    ) {
        $this->session = $session;
        $this->security = $security;
        $this->user = $this->security->getUser();
        if ($this->user != null) {
            $this->favoriteList = $this->user->getFavoriteList();
            $this->favoriteProductRepo = $favoriteProductRepo;
            $this->productRepo = $productRepo;
            $this->em = $em;
        }
    }

    public function add(int $product_id)
    {
        $favoriteList_id = $this->favoriteList->getId();

        if ($this->favoriteProductRepo->findOneBy(['favoriteList' => $favoriteList_id, 'Product' => $product_id]) === null) {

            $new_favorite = new FavoriteProduct;
            $new_favorite->setFavoriteList($this->favoriteList);
            $productToAdd = $this->productRepo->findOneBy(['id' => $product_id]);
            $new_favorite->setProduct($productToAdd);

            $this->em->persist($new_favorite);
            $this->em->flush();
        }
    }

    public function remove(int $favProduct_id)
    {
        $productToRemove = $this->favoriteProductRepo->findOneBy(['id' => $favProduct_id]);

        $this->em->remove($productToRemove);
        $this->em->flush();
    }
}
