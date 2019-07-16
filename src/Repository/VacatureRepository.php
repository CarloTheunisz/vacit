<?php

namespace App\Repository;

use App\Entity\Vacature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Vacature|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vacature|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vacature[]    findAll()
 * @method Vacature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacatureRepository extends ServiceEntityRepository
{
    /**
     * Functie: __construct
     * Doel:    Constructor van de klasse
     * Taken:   Voert de autowiring voor de klasse uit
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Vacature::class);
    }

    /**
     * Functie: getAllVacatures
     * Doel:    vind alle vacatures en ordert ze
     */
    public function getAllVacatures() {
        return $this->createQueryBuilder('v')
            ->orderBy('v.datum DESC, v.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Functie: findVacaturesFromUser
     * Doel:    vind alle vacatures van een specifieke werkgever en ordert ze
     */
    public function findVacaturesFromUser($user) {
        return $this->createQueryBuilder('v')
            ->andWhere('v.user = :val')
            ->setParameter('val', $user->getId())
            ->orderBy('v.datum DESC, v.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Functie: getAndereVacatures
     * Doel:    vind alle vacatures van een specifieke werkgever behalve de meegegeven vacature en ordert ze
     */
    public function getAndereVacatures($vacature) {
        return $this->createQueryBuilder('v')
            ->andWhere('v.user = :val')
            ->setParameter('val', $vacature->getUser())
            ->andWhere('v.id != :self')
            ->setParameter('self', $vacature->getId())
            ->orderBy('v.datum DESC, v.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Vacature[] Returns an array of Vacature objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Vacature
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
