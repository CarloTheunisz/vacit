<?php

namespace App\Repository;

use App\Entity\Sollicitatie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sollicitatie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sollicitatie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sollicitatie[]    findAll()
 * @method Sollicitatie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SollicitatieRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sollicitatie::class);
    }

    /**
     * Functie: findSollicitatiesFromUser
     * Doel:    vind alle sollicitaties van een specifieke gebruiker
     */
    public function findSollicitatiesFromUser($user) {
        return $this->createQueryBuilder('s')
            ->andWhere('s.user = :val')
            ->setParameter('val', $user->getId())
            ->orderBy('s.datum DESC, s.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Functie: findSollicitatiesFromVacature
     * Doel:    vind alle sollicitatie die horen bij een specifieke vacature
     */
    public function findSollicitatiesFromVacature($vacature) {
        return $this->createQueryBuilder('s')
            ->andWhere('s.vacature = :val')
            ->setParameter('val', $vacature->getId())
            ->orderBy('s.datum DESC, s.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Sollicitatie[] Returns an array of Sollicitatie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sollicitatie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
