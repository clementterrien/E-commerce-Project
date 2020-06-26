<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Product;
use App\Entity\Category;
use App\Data\SearchStructure;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * findPopularCategory
     *
     * @param  string $type
     * @param  integer|null $limit
     * @return QueryBuilder
     */
    public function findPopularCategory(string $type, int $limit = null): QueryBuilder
    {
        $name = $type;
        $limit == null ? $limit = 0 : $limit;

        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.name = :name')
            ->innerJoin('c.products', 'p')
            ->groupBy('c')
            ->having('SIZE(c.products) > :minimum')
            ->setParameter('name', $name)
            ->setParameter('minimum', $limit);

        return $qb;
    }

    //SELECT category.* FROM category JOIN product ON product.region = 'Alsace' AND category.value = product.grape
    //GROUP BY category.id

    /**
     * findGrapesBySearch Returns a QueryBuilder with GrapeCategories corresponding to the search 
     * If a region is selected by the user: This will return all Grapes of this region
     * ex: Alsace is selected 
     * findGrapesBySearch will return all Alsace grapes 
     * 
     *
     * @param  Category[] $searchData
     * @return void
     */
    public function findGrapesByRegions(array $regionCategories): QueryBuilder
    {
        $qb = $this
            ->createQueryBuilder('c')
            ->select('c')
            ->where('c.name = :grape')
            ->join('c.products', 'p');

        if (!empty($regionCategories)) {
            $regions = [];
            foreach ($regionCategories as $key => $regionCategory) {
                array_push($regions, $regionCategory->getValue());
            }

            $qb = $qb
                ->AndWhere('p.region IN (:regions) AND c.value = p.grape')
                ->setParameter('regions', $regions);
        }

        $qb = $qb
            ->groupBy('c')
            ->setParameter('grape', 'grape');

        return $qb;
    }

    public function findCategoryByRegions(string $categoryName, array $regionCategories): QueryBuilder
    {
        $qb = $this
            ->createQueryBuilder('c')
            ->select('c')
            ->where('c.name = :category_name')
            ->join('c.products', 'p');

        if (!empty($regionCategories)) {
            $regions = [];
            foreach ($regionCategories as $key => $regionCategory) {
                array_push($regions, $regionCategory->getValue());
            }

            $qb = $qb
                ->AndWhere('p.region IN (:regions) AND c.value = p.' . $categoryName)
                ->setParameter('regions', $regions);
        }

        $qb = $qb
            ->groupBy('c')
            ->setParameter('category_name', $categoryName);

        return $qb;
    }
}
