<?php

namespace App\Repository;

use App\Entity\Product;
use App\Data\SearchData;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{

    /**
     * paginator
     *
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Product::class);
        $this->paginator = $paginator;
    }

    /**
     * findSearch returns Product array linked with the search performed by the user
     *
     * @param  SearchData $search
     * @return PaginationInterface
     */
    public function findSearch(SearchData $search): PaginationInterface
    {
        $query = $this->getSearchQuery($search)->getQuery();

        return $this->paginator->paginate(
            $query,
            $search->page,
            15
        );
    }

    public function findProductsIntwocat($search)
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->select('p')
            ->join('p.categories', 'c')
            ->andwhere('p IN (:regionCategories)')
            ->setParameter('regionCategories', $search->regionCategories);
        // ->andwhere('c.id IN (:grapeCategories)')
        // ->setParameter('grapeCategories', 'Albert Boxler');

        $qb = $qb->getQuery()->getResult();
        dd($search, $qb);
    }

    /**
     * findMinMaxPrice Retrieve the min and max prices corresponding to a search
     *
     * @param  SearchData $data
     * @return integer[]
     */
    public function findMinMaxPrice(SearchData $search): array
    {

        $results = $this->getSearchQuery($search, true)
            ->select('MIN(p.price) as min', 'MAX(p.price) as max')
            ->getQuery()
            ->getScalarResult();
        return [(int) $results[0]['min'], (int) $results[0]['max']];
    }

    /**
     * getSearchQuery
     *
     * @param  SearchData $search
     * @param  bool $ignorePrice
     * @return QueryBuilder
     */
    private function getSearchQuery(SearchData $search, $ignorePrice = false): QueryBuilder
    {
        $query = $this->createQueryBuilder('p');

        $query = $query
            ->select('c', 'p')
            ->join('p.categories', 'c');

        if (!empty($search->min) && $ignorePrice == false) {
            $query = $query
                ->andwhere('p.price >= :min')
                ->setParameter('min', $search->min);
        }
        if (!empty($search->max) && $ignorePrice == false) {
            $query = $query
                ->andwhere('p.price <= :max')
                ->setParameter('max', $search->max);
        }

        if (!empty($search->q)) {
            $query = $query
                ->andwhere('p.name LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        /**
         * Priority 1 : if grape category is defined, only this searchData will be used to find products 
         * example: A Albert Boxler grape bottle could only come from Alsace
         */
        if (!empty($search->grapeCategories)) {
            $grapeCategories = [];
            foreach ($search->grapeCategories as $key => $grapeCategory) {
                array_push($grapeCategories, $grapeCategory->getValue());
            }
            $query = $query
                ->andwhere('p.grape IN (:grapeCategories)')
                ->setParameter('grapeCategories', $grapeCategories);

            return $query;
        }

        /**
         * Priority 1 : if designation category is defined, only this searchData will be used to find products 
         * example: An Alsace Designation bottle could only come from Alsace
         */
        if (!empty($search->designationCategories)) {
            $designationCategories = [];
            foreach ($search->designationCategories as $key => $designationCategory) {
                array_push($designationCategories, $designationCategory->getValue());
            }
            $query = $query
                ->andwhere('p.designation IN (:designationCategories)')
                ->setParameter('designationCategories', $designationCategories);
            return $query;
        }


        if (!empty($search->regionCategories)) {
            $regionCategories = [];
            foreach ($search->regionCategories as $key => $regionCategory) {
                array_push($regionCategories, $regionCategory->getValue());
            }
            $query = $query
                ->andwhere('p.region IN (:regionCategories)')
                ->setParameter('regionCategories', $regionCategories);
        }

        if (!empty($search->typeCategories)) {
            $typeCategories = [];
            foreach ($search->typeCategories as $key => $typeCategory) {
                array_push($typeCategories, $typeCategory->getValue());
            }
            $query = $query
                ->andwhere('p.type IN (:typeCategories)')
                ->setParameter('typeCategories', $typeCategories);
        }

        return $query;
    }
}
