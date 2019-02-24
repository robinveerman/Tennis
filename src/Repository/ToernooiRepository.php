<?php

namespace App\Repository;

use App\Entity\Toernooi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Toernooi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Toernooi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Toernooi[]    findAll()
 * @method Toernooi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToernooiRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Toernooi::class);
    }

    // /**
    //  * @return Toernooi[] Returns an array of Toernooi objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Toernooi
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
