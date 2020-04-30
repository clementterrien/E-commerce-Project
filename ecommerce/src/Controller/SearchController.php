<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\Product\CategoryService;
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
    public function addCategory(CategoryService $service)
    {
        $service->addSubCategoriesBasedOnProductFields();

        return $this->render('search/search.html.twig', []);
    }
}
