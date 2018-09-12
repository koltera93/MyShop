<?php

namespace App\Repository;

use App\Entity\FeedBackRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FeedBackRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeedBackRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeedBackRequest[]    findAll()
 * @method FeedBackRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedBackRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FeedBackRequest::class);
    }

//    /**
//     * @return FeedBackRequest[] Returns an array of FeedBackRequest objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FeedBackRequest
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
