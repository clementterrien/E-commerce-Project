<?php

namespace App\Service\Product;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProductService
{

    protected $productRepo;
    protected $session;

    public function __construct(ProductRepository $productRepo, SessionInterface $session)
    {
        $this->productRepo = $productRepo;
        $this->session = $session;
    }

    /**
     * Return the 3 most ordered products
     */
    public function getTop3BestSellers()
    {
        $products = $this->productRepo->findBy([], ['orderCounter' => 'DESC'], 3);
        return $products;
    }

    /**
     * Return the 3 most liked products
     */
    public function getTop3MostLikedProducts(): array
    {
        $products = $this->productRepo->findBy([], ['likeCounter' => 'DESC'], 3);
        return $products;
    }

    /**
     * Return list of all the products
     */
    public function getAllTheProducts():array
    {
        $products = $this->productRepo->findAll([]);
        return $products;
    }

    /**
     * Return list of products based on a criteria
     */
    public function getProductsByCriteria($field, $value):array
    {
        $products = $this->productRepo->findBy([$field => $value]);
        return $products;
    }
}
