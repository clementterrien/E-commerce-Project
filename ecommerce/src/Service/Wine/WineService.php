<?php

namespace App\Service\Wine;

use App\Repository\WineRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class WineService
{

    protected $wineRepo;
    protected $session;

    public function __construct(WineRepository $wineRepo, SessionInterface $session)
    {
        $this->wineRepo = $wineRepo;
        $this->session = $session;
    }

    /**
     * Return the 3 most ordered wines
     */
    public function getTop3BestSellers()
    {
        $wines = $this->wineRepo->findBy([], ['id' => 'DESC'], 3);
        return $wines;
    }

    public function getTop3GoodPlans()
    {
        return $this->productRepo->findBy([], ['likeCounter' => 'DESC'], 3);
    }

    public function getTop3RedWineSelection()
    {
        return $this->productRepo->findBy(['type' => 'rouge'], ['likeCounter' => 'DESC'], 3);
    }

    public function getTop3GrandsCrusSelection()
    {
        return $this->productRepo->findBy([], ['price' => 'DESC'], 3);
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
    public function getAllTheProducts(): array
    {
        $products = $this->productRepo->findAll([]);
        return $products;
    }

    /**
     * Return list of products based on a criteria
     */
    public function getProductsByCriteria($key, $value, $products = null)
    {

        if ($key == "priceFilter" && $value == "decreasingPrice") {
            $products = $this->productRepo->findBy([], ['price' => 'DESC']);
        } elseif ($key == "priceFilter" && $value == "ascendingPrice") {
            $products = $this->productRepo->findBy([], ['price' => 'ASC']);
        } elseif ($key == "yearFilter") {
            $products = $this->productRepo->findByYear(['year' => $value]);
        } elseif ($key == "regionFilter") {
            $products = $this->productRepo->findByRegion(['region' => $value]);
        } elseif ($key == "coutryFilter") {
            $products = $this->productRepo->findByCountry(['country' => $value]);
        } elseif ($key == "grappeFilter") {
            $products = $this->productRepo->findByGrappe(['grappe' => $value]);
        } elseif ($key == "literFilter") {
            $products = $this->productRepo->findByLiter(['liter' => $value]);
        } elseif ($key == "typeFilter") {
            $products = $this->productRepo->findByType(['type' => $value]);
        }
        return $products;
    }
}
