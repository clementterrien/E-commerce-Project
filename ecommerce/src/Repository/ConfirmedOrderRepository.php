<?php

namespace App\Repository;

use App\Entity\ConfirmedOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ConfirmedOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfirmedOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfirmedOrder[]    findAll()
 * @method ConfirmedOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfirmedOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfirmedOrder::class);
    }

    // /**
    //  * @return ConfirmedOrder[] Returns an array of ConfirmedOrder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ConfirmedOrder
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
