<?php

namespace App\Repository;

use App\Entity\Exemplaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Exemplaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exemplaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exemplaire[]    findAll()
 * @method Exemplaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExemplaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exemplaire::class);
    }

    /**
    * @return Exemplaire[] Returns an array of Exemplaire objects
    */
    
    public function findLastExemplaireOfTheBook($livreId)
    {
        return $this->createQueryBuilder('e')
            ->select('e.numExemplaire')
            ->andWhere('e.livre = :livreid')
            ->setParameter('livreid', $livreId)
            ->orderBy('e.numExemplaire', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Exemplaire
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
