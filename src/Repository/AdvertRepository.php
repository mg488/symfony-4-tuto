<?php

namespace App\Repository;

use App\Entity\Advert;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Advert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advert[]    findAll()
 * @method Advert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advert::class);
    }

    public function myFindAll()
    {
        //*Méthode 1 **//
        // $queryBuilder = $this->_em->createQueryBuilder()
        //                 ->select('a')
        //                 ->from($this->_entityName,'a') 
                        //**Dans un repository, $this->_entityName est le namespace de l'entité gérée
                        //**Ici, il vaut donc App\Entity\Advert
                        ;
        //*Méthode 2 avec le raccourci **//
        $queryBuilder = $this->createQueryBuilder('a'); //select * from tabadvert
        //************************************************************************************************ */
         // On récupère la Query à partir du QueryBuilder
        $query = $queryBuilder->getQuery();

        // On récupère les résultats à partir de la Query
        $results = $query->getResult();

        // On retourne ces résultats
        return $results;
    }
    //************************************************************************************** */
    public function myFindOne($id)
    {
        $qB = $this->createQueryBuilder('a');
        $qB 
            ->where('a.id = :id')
            ->setParameter(':id',(int)$id)
        ; 
        // dd((int)$id);
        return $qB
                  ->getQuery()
                  ->getResult()
                    ;
    }
    //***************************************************************************************** */
    public function myFind()
    {
        $qB = $this->createQueryBuilder('a');
        $qB
            ->where('a.author = :author')
            ->setParameter(':author','Moustapha')
            ;
        $this->whereCurrentYear($qB);
        $qB->orderBy('a.date_crea','DESC');
        
        return $qB
                ->getQuery() 
                ->getResult()
                ;
                
    }
    public function myCount()
    {
        $qB = $this->createQueryBuilder('a');
        $qB->select('count(a)');
        return $qB
                ->getQuery() 
                ->getSingleScalarResult()
                ;  
    }
    //********************************************************************************************** */
    public function whereCurrentYear(QueryBuilder $qb)
    {
        $qb
            ->andWhere('a.date_crea BETWEEN :start AND :end')
            ->setParameter(':start', new \DateTime(date('Y').'-01-01'))
            ->setParameter(':end', new \DateTime(date('Y').'12-31'))
            ;
    }
//***************************************************************************************************************** */
    public function getAdvertWithApplications()
    {
        $qb = $this
                    ->createQueryBuilder('a')
                    ->leftJoin('a.applications', 'app')
                    ->addSelect('app')
                    ;
        return 
            $qb
                ->getQuery()
               ->getResult()
               ;
    }    



    // /**
    //  * @return Advert[] Returns an array of Advert objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Advert
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
