<?php

namespace App\Controller;

use App\Service\Product\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/CreateCategories", name="create_category_by_products")
     */
    public function createCategories(CategoryService $categoryService)
    {
        $attributesWanted = array('alcool');
        $categoryService->createCategoriesByEntityAttributes($attributesWanted);

        return $this->redirectToRoute('home');
    }
}
