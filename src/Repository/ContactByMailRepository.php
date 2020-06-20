<?php

namespace App\Repository;

use App\Entity\ContactByMail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ContactByMail|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactByMail|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactByMail[]    findAll()
 * @method ContactByMail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactByMailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactByMail::class);
    }

    // /**
    //  * @return ContactByMail[] Returns an array of ContactByMail objects
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
    public function findOneBySomeField($value): ?ContactByMail
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
