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
    public function search(ProductRepository $repo)
    {
        $products = $repo->findAll();
        $nbDesignation = 0;
        $testedDesignation = "";
        $designation = "";
        $counter = 1;

        dump($nbDesignation);

        return $this->render('search/search.html.twig', []);
    }

    /**
     * @Route("/addCategory", name="addCategory")
     */
    public function addCategory(EntityManagerInterface $em, SubCategoryRepository $subRepo, ProductRepository $productRepo)
    {
        // $service->addSubCategoriesBasedOnProductFields();
        // $service->categorizeAllProducts(array('region', 'grape', 'year'));
        // dd($service->test());

        // Delas Hermitage Les Bessards 1996 Magnum

        $product = $productRepo->findOneBy(['id' => 4022]);
        $subCategory = 0;
        dd($subRepo->findOneBy(['name' => '1997', 'category' => 5]));



        return $this->render('search/search.html.twig', []);
    }
}
