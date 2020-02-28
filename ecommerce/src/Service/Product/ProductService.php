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
    public function getProductsByCriteria($key,$value, $products = null)
    {

        if($key == "priceFilter" && $value == "decreasingPrice")
        {
            $products = $this->productRepo->findBy([], ['price' => 'DESC']);
        }
        elseif($key == "priceFilter" && $value == "ascendingPrice")
        {
            $products = $this->productRepo->findBy([],['price' => 'ASC']);
        }
        elseif($key == "yearFilter")
        {
            $products = $this->productRepo->findBy([],['year' => $value]);
        }
        elseif($key == "regionFilter")
        {
            $products = $this->productRepo->findBy([],['region' => $value]);
        }
        elseif($key == "coutryFilter")
        {
            $products = $this->productRepo->findBy([],['country' => $value]);
        }
        elseif($key == "grappeFilter")
        {
            $products = $this->productRepo->findBy([],['grappe' => $value]);
        }
        elseif($key == "literFilter")
        {
            $products = $this->productRepo->findBy([],['liter' => $value]);
        }
        elseif($key == "typeFilter")
        {
            $products = $this->productRepo->findBy([],['type' => $value]);
        }
        return $products;
    }
}
