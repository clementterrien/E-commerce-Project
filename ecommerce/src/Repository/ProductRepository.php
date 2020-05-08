<?php

namespace App\Repository;

use App\Entity\Product;
use App\Data\SearchData;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * findSearch returns Product array linked with the search performed by the user
     *
     * @return Product[]
     */
    public function findSearch(SearchData $search): array
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.categories', 'c');

        if (!empty($search->q)) {
            $query = $query
                ->where('p.name LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->regionCategories)) {
            $query = $query
                ->andwhere('c.id IN (:regionCategories)')
                ->setParameter('regionCategories', $search->regionCategories);
        }

        if (!empty($search->grapeCategories)) {
            $query = $query
                ->andwhere('c.id IN (:grapeCategories)')
                ->setParameter('grapeCategories', $search->grapeCategories);
        }

        return $query->getQuery()->getResult();
    }
}
