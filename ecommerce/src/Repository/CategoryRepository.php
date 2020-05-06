<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\ORM\Query\Expr\Join;
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
     * @return Category[] Returns an array of Category objects
     */
    public function findPopularRegion()
    {
        $region = 'region';

        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.name = :region')
            ->innerJoin('c.products', 'p')
            ->groupBy('c')
            ->having('SIZE(c.products) > :minimum')
            ->setParameter('region', $region)
            ->setParameter('minimum', 20);


        //  SELECT category.*,COUNT(*) 
        //  FROM category_product INNER JOIN category 
        //  ON category_product.category_id = category.id 
        //  WHERE category.name = 'region' 
        //  GROUP BY category_product.category_id 
        //  HAVING COUNT(*) > 20

        return $qb;
    }

    public function findPopularGrapes()
    {
        $name = 'grape';

        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.name = :name')
            ->innerJoin('c.products', 'p')
            ->groupBy('c')
            ->having('SIZE(c.products) > :minimum')
            ->setParameter('name', $name)
            ->setParameter('minimum', 30);

        return $qb;
    }

    public function findPopularTypes()
    {
        $name = 'type';

        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.name = :name')
            ->innerJoin('c.products', 'p')
            ->groupBy('c')
            ->having('SIZE(c.products) > :minimum')
            ->setParameter('name', $name)
            ->setParameter('minimum', 30);

        return $qb;
    }
}
