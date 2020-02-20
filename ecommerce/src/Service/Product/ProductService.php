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
}
