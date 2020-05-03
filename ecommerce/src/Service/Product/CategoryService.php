<?php

namespace App\Service\Product;

use Error;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\SubCategory;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\SubCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Console\Helper\Dumper;
use Symfony\Component\Validator\Constraints\IsFalseValidator;

class CategoryService
{

    protected $productRepo;
    protected $categoryRepo;
    protected $subCategoryRepo;

    public function __construct(CategoryRepository $categoryRepo, ProductRepository $productRepo, EntityManagerInterface $em, SubCategoryRepository $subCategoryRepo)
    {
        $this->productRepo = $productRepo;
        $this->categoryRepo = $categoryRepo;
        $this->subCategoryRepo = $subCategoryRepo;
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

    public function getter()
    {
        $object = $this->productRepo->findOneBy(['id' => 18]);
        return $object->__get('askip');
    }

    /**
     * This function creates a new Category and his subCategories if the category's
     * name match with an entity attribute 
     * Example: createCategoryandHisSubCategories("designation"); will:
     *          1. create a designation Category (checking if exists or not)
     *          2. check if "designation" his an attribute of the existinf entity
     *          3. create allSubCategories based on all different values contained in DB
     *             & affecting all corresponding product for the subCategories created
     */
    public function createCategoryandHisSubCategories(string $categoryToCreate)
    {
        $productAttributes = (new Product())->getAttributes();
        if (array_key_exists($categoryToCreate, $productAttributes)) {
            $this->createAndFillSubCategories($categoryToCreate);
        } else {
            throw new Exception("Your Category isn't based on a product attribute, 
            your Category will be empty..");
        }
    }

    public function createAndFillSubCategories(String $CategoryToCreate)
    {
        $category = $this->categoryRepo->findOneBy(['name' => $CategoryToCreate]);
        $allProducts = $this->productRepo->findAll();
        $existingSubCategories = $category->getSubCategories();
        $attribute = $CategoryToCreate;
        $testedSub = "";

        foreach ($allProducts as $key => $product) {
            $fieldValue = $product->__get($attribute);
            if ($testedSub !== $fieldValue) {
                $Sub = (new SubCategory())
                    ->setName($product->__get($attribute))
                    ->setCategory($category);
                $Sub->addProduct($product);
                $this->em->persist($Sub, $category);
                $this->em->persist($product);
                $this->em->flush();
            } else {
                $Sub = $this->subCategoryRepo->findOneBy(['name' => $fieldValue]);
                $Sub->addProduct($product);
                $this->em->persist($Sub);
                $this->em->persist($product);
            }
            $testedSub = $Sub->getName();
        }
        $this->em->flush();
    }

    public function categorizeAllProducts(array $categorizeBy)
    {
        // Step 1. The list of all possible Categorization
        $neededCategorization = $this->listSubCategoriesOfSelectedCategories($categorizeBy);
        // Step 2. Make the Categorization well stored in DB.
        $this->categorize($neededCategorization);
        // Step 3. Make the SubCategorization well stored in DB.
        foreach ($neededCategorization as $categoryName => $neededSubCategories) {
            $this->subcategorize($categoryName, $neededSubCategories);
        }
        /***************************************************************
         *** THE DATABASE CATEGORIZATION IS WELL ORGANIZED AND STORED ** 
         **************************************************************/

        // Step 4. List All Products
        $allProducts = $this->productRepo->findAll();
        // Boucle sur les catégories recherchées 
        foreach ($categorizeBy as $categoryName => $subCategoriesName) {
            $category = $this->categoryRepo->findBy(['name' => $categoryName]);
            $subCategories = $category->getSubCategories();
            // Objet Produits qui ont dans la categorie concernées la valeur d'une sous categorie
            // ex: $category = 'region' $subCategorie = 'Rhône' => va donner les produits ayant pour region
            // le rhone
            foreach ($subCategories as $key => $subCategory) {
                $matchingroducts = $this->getAllElementsWithASpecifiedAttribute($allProducts, $categoryName, $subCategory->getName());
                foreach ($matchingroducts as $key => $product) {
                    $this->subcategorizeAProduct($product, $subCategory);
                }
            }
        }
    }


    /**
     * This function Compares if the Needed Categories are well stored in DB.
     * If not it creates missed ones.
     */
    public function categorize(array $neededCategories)
    {
        $storedCategories = $this->transformObjectArrayInStringArray($this->categoryRepo->findAll());

        foreach ($neededCategories as $neededCategory => $value) {
            if (!in_array($neededCategory, $storedCategories)) {
                $newCategory = (new Category())->setName($neededCategory);
                $this->em->persist($newCategory);
            }
        }
        $this->em->flush();
    }

    /**
     * This function Compares if the Needed SubCategories are well stored in DB.
     * If not it creates missed ones.
     */
    public function subcategorize(string $categoryName, array $neededSubCategories)
    {
        $storedCategory = $this->categoryRepo->findOneBy(['name' => $categoryName]);
        $storedSubcategories = $this->transformObjectArrayInStringArray($storedCategory->getSubCategories());

        foreach ($neededSubCategories as $key => $neededSubcategoryName) {
            if (!in_array($neededSubcategoryName, $storedSubcategories)) {
                $this->createAndStoreSubCategory($storedCategory, $neededSubcategoryName);
            }
        }
    }


    public function transformObjectArrayInStringArray($objectArray)
    {
        $stringArray = [];

        foreach ($objectArray as $key => $object) {
            $objectName = $object->getName();
            array_push($stringArray, $objectName);
        }

        return $stringArray;
    }



    public function listSubCategoriesOfSelectedCategories(array $categorizeBy): array
    {
        $list = [];

        foreach ($categorizeBy as $key => $categoryName) {

            if (!array_key_exists($categoryName, $list)) {
                $possibleSubCategories = $this->listAllPossibleSubCategories($categoryName);
                $list[$categoryName] = $possibleSubCategories;
            }
        }

        return $list;
    }

    /**
     * This function returns an array with all possible SubCategorization
     */
    public function listAllPossibleSubCategories(string $category): array
    {
        $allProducts = $this->productRepo->findAll();
        $allPossibleCategories = [];

        foreach ($allProducts as $key => $product) {
            $value = $product->__get($category);
            if (!in_array($value, $allPossibleCategories)) {
                array_push($allPossibleCategories, $value);
            }
        }
        return $allPossibleCategories;
    }


    public function createAndStoreSubCategory(Category $category, $newSubcategoryName)
    {
        $newSubCategory = (new SubCategory())->setName($newSubcategoryName)->setCategory($category);
        $category->addSubCategory($newSubCategory);
        $this->em->persist($category, $newSubCategory);
        $this->em->flush();
    }

    public function addCategory($categoryName)
    {
        $newCategory = (new Category())->setName($categoryName);
        $this->em->persist($newCategory);
        $this->em->flush();
    }

    public function test()
    {
        $productList = $this->productRepo->findAll();
        $attribute = 'region';
        $val = 'Rhône';

        return $this->getAllElementsWithASpecifiedAttribute($productList, $attribute, $val);
    }
    public function getAllElementsWithASpecifiedAttribute(array $productList, string $attribute, string $val): array
    {
        $resultList = [];

        foreach ($productList as $key => $product) {
            $attributeVal = $product->__get($attribute);
            if ($attributeVal == $val) {
                array_push($resultList, $product);
            }
        }
        return $resultList;
    }

    // public function addMatchingProductsInMatchingSubCategory(array $productList, array $subCategories) 
    // {
    //     foreach ($subCategories as $key => $subCategory) {

    //     }
    // }


    public function subcategorizeAProductIfItsNot(Product $product, SubCategory $subCategory)
    {
        if ($this->isSubCategorized($product, $subCategory) === false) {
            $subCategory->addProduct($product);
        }
    }
    public function isSubCategorized($product, $subCategory)
    {
        if (in_array($product, $subCategory->getProduct())) {
            return true;
        } else {
            return false;
        }
    }
}
