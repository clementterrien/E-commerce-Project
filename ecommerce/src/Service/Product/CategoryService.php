<?php

namespace App\Service\Product;

use Exception;
use App\Entity\Product;
use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    /**
     * @var ProductRepository
     */
    protected $productRepo;
    /**
     * @var CategoryRepository
     *
     * @var mixed
     */
    protected $categoryRepo;

    /**
     * __construct
     *
     * @param  CategoryRepository $categoryRepo
     * @param  ProductRepository $productRepo
     * @param  EntityManagerInterface $em
     * @return void
     */
    public function __construct(CategoryRepository $categoryRepo, ProductRepository $productRepo, EntityManagerInterface $em)
    {
        $this->productRepo = $productRepo;
        $this->categoryRepo = $categoryRepo;
        $this->em = $em;
    }

    /**
     * Identifies how can be grouped Categories with specified product attributes. It identifies all possible
     * combinations of the product field (ex: region, type, designation, grape etc...)
     * Example: createCategoriesByProductAttributes("product" ,array('region'));
     * If there are two products coming from "Alsace" Region this function will 
     * create and store a new Category if it doesn't exists each;
     *      
     *
     * @param string[] $productAttributes
     * 
     * @return void
     */
    public function createCategoriesByEntityAttributes(array $productAttributes): void
    {
        $categories = $this->categoryRepo->findAll();

        foreach ($productAttributes as $key => $attribute) {
            (array) $neededCategorization = $this->howToCategorizeAProductAttribute($attribute);
            (array) $actualCategorization = $this->howAProductAttributeIsCategorized($attribute);
            $this->createAndFillMissingCategories($attribute, $neededCategorization, $actualCategorization);
        }
    }

    /**
     * createAndFillMissingCategories is the main function of createCategoriesByEntityAttributes
     *
     * @param  mixed $productAttribute
     * @param  mixed $neededCategorization
     * @param  mixed $actualCategorization
     * @return void
     */
    public function createAndFillMissingCategories(string $productAttribute, array $neededCategorization, array $actualCategorization): void
    {
        $allProducts = $this->productRepo->findAll();
        $categories = $this->categoryRepo->findBy(['name' => $productAttribute]);
        /**
         * $key ='Alsace' ; $product_id = 1 ; neededproducts = array(1....36) ; 
         */
        foreach ($neededCategorization as $key => $neededproducts) {
            if ($categories === []) {
                $category = (new Category)
                    ->setName($productAttribute)
                    ->setValue($key)
                    ->setType('product-attribute');
                $actualCategorization[$key] = [];
            } else {
                $category = $this->getCategoryByProductValue($categories, $productAttribute, $key);
            }

            foreach ($neededproducts as $subkey => $product_id) {

                if (!in_array($product_id, $actualCategorization[$key])) {
                    $message = 'product: ' . $product_id . ' is missing in the ' . $key . ' catégorie';
                    // ajouter le produit manquant
                    $product = $this->getProductByID($allProducts, $product_id);
                    $product->addCategory($category);
                    $category->addProduct($product);
                    $this->em->persist($product);
                    $this->em->persist($category);
                }
            }
        }
        $this->em->flush();
    }

    /**
     * howToCategorizeAProductAttribute returns an array of arrays with all possible combinations of product
     * example: howToCategorizeAProductAttribute('region)
     * returns array([Bordeaux] => $bordeauxProducts, [Bourgogne] => $bourgogneProducts) 
     * where $bordeauxProducts = array(5, 6, 7, 8, 9, 10 etc) => values are products ID's.
     *
     * @param  mixed $attributeName
     * @return array[]
     */
    public function howToCategorizeAProductAttribute(string $attributeName): array
    {
        (array) $productAttributes = (new Product())->getAttributes();
        (array) $allProducts = $this->productRepo->findAll();

        if (array_key_exists($attributeName, $productAttributes)) {

            $categories = [];
            foreach ($allProducts as $key => $product) {
                $attributeName = $attributeName;
                $attributeVal = $product->__get($attributeName);

                if (empty($categories[$attributeVal])) {
                    $categories[$attributeVal] = array($product->getId());
                } else {
                    array_push($categories[$attributeVal], $product->getId());
                }
            }
            return $categories;
        } else {
            throw new Exception("The attribute your trying to analyse isn't a product attribute.");
        }
    }

    /**
     * howAProductAttributeIsCategorized returns the same form of array than the howToCategorizeAProductAttribute function
     * This one will make an overview of the actual mapping stored in DB. This function is used jointly 
     * the howToCategorize function. It allows to not make any unnecessary query to the DB storing product linked 
     * to the category
     *
     * @param  string $attribute -> is the attribute you want a see the actual's organization
     * ex: $attribute = 'region'
     *   
     * @return array
     */
    public function howAProductAttributeIsCategorized(string $attribute): array
    {
        $categorization = $this->categoryRepo->findBy(['name' => $attribute]);
        $result = [];
        foreach ($categorization as $key => $category) {
            $value = $category->getValue();
            $products = $this->getAllProductsId($category);
            $result[$value] = $products;
        }
        return $result;
    }

    /**
     * getAllProductsId
     * Returns an array with allProducts Id of a specified Category
     * 
     *
     * @param  Category $category
     * @return array
     */
    public function getAllProductsId(Category $category): array
    {
        $products = $category->getProducts();
        $result = [];
        foreach ($products as $key => $product) {
            array_push($result, $product->getId());
        }
        return $result;
    }

    /**
     * getProductsBy Returns a products array
     * This function works like a productRepository but in a Collection
     *
     * @param  array $productList 
     * ex: $productList = $this->productRepo->findAll();
     * @param  string $attribute
     * ex: $attribute = 'region'
     * @param  mixed $val
     * ex: $val = 'Bordeaux';
     * @return array
     */
    public function getProductsBy(array $productList, string $attribute, string $val): array
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

    /**
     * getProductByID Returns the searched product in a collection. Useful to avoid unnecessary queries when 
     * you already have a product array.
     *
     * @param  Products[] $allProducts
     * @param  int $product_id
     * 
     * @return Product
     */
    public function getProductByID(array $allProducts, int $product_id): object
    {
        foreach ($allProducts as $key => $product) {
            if ($product->getId() === $product_id) {
                break;
            }
        }

        return $product;
    }

    /**
     * getCategoryByProductValue Returns a Category needed in a Category Collection. Useful to avoid unnecessary queries when 
     * you already have a category array.
     *
     * @param  Category[] $allCategories
     * @param  string $productAttribute
     * @param  string $attribute
     * @return null|Category
     */
    public function getCategoryByProductValue(array $allCategories, $productAttribute, $attribute)
    {
        foreach ($allCategories as $key => $category) {
            if ($category->getName() == $productAttribute && $category->getValue() == $attribute) {
                break;
            } else {
                return null;
            }
        }
        return $category;
    }
}
