<?php

namespace App\Service\Product;

use App\Entity\Category;
use App\Entity\SubCategory;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Error;

class CategoryService
{

    protected $categoryRepo;
    protected $productRepo;

    public function __construct(CategoryRepository $categoryRepo, ProductRepository $productRepo, EntityManagerInterface $em)
    {
        $this->categoryRepo = $categoryRepo;
        $this->productRepo = $productRepo;
        $this->em = $em;
    }

    /**
     * Add the specified category to the specified product
     */
    public function addSubCategoriesBasedOnProductFields()
    {
        $product_subCategories = [];
        $products = $this->productRepo->findAll();
        $designation = "";

        foreach ($products as $key => $product) {
            if ($product->getDesignation() !== $designation) {
                $designation = $product->getDesignation();
                array_push($product_subCategories, $designation);
            }
        }

        $designationCategory = $this->categoryRepo->findOneBy(['name' => 'designation']);

        foreach ($product_subCategories as $key => $subCategories) {
            $subCategory = (new SubCategory())->setName($subCategories);
            $designationCategory->addSubCategory($subCategory);
            $this->em->persist($subCategory);
        }
        $this->em->persist($designationCategory);
        $this->em->flush();
    }

    public function setSub()
    {
        $subCategory = (new SubCategory())->setName('Alsace');
        $designationCategory = $this->categoryRepo->findBy(['name' => 'designation']);
        $designationCategory[0]->addSubCategory($subCategory);
        $this->em->persist($designationCategory[0]);
        $this->em->persist($subCategory);
        $this->em->flush();
    }


    public function createACategory(string $category)
    {
        $product_categories = [];
        $existingCategory = $this->categoryRepo->findBy(['type' => $type]);

        if ($existingCategory === []) {
            $newCategory = (new Category())->setName($category);
            $this->em->persist($newCategory);
            $this->em->flush();
        } else {
            throw new Error("You're trying to recreate an existing category (this type name is already used)");
        }
    }

    public function addCategory($categoryName)
    {
        $newCategory = (new Category())->setName($categoryName);
        $this->em->persist($newCategory);
        $this->em->flush();
    }
}
