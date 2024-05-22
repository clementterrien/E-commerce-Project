<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\SubCategoryRepository;
use App\Service\Product\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function search(ProductRepository $productRepo)
    {
        $products = $productRepo->findSearch();

        return $this->render('search/search.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/addCategory", name="addCategory")
     */
    public function addCategory(EntityManagerInterface $em, ProductRepository $productRepo, CategoryService $service)
    {
        // $service->addSubCategoriesBasedOnProductFields();
        // $service->categorizeAllProducts(array('region', 'grape', 'year'));

        // Delas Hermitage Les Bessards 1996 Magnum

        // dd($service->howAProductAttributeIsCategorized('region'));
        $service->createCategoriesByEntityAttributes(array('liter'));

        return $this->render('search/search.html.twig', []);
    }
}
