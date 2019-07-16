<?php

namespace App\Repository;

use App\Entity\Referentie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Referentie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Referentie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Referentie[]    findAll()
 * @method Referentie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferentieRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Referentie::class);
    }

    /**
     * Functie: findPlaatsen
     * Doel:    vind alle plaatsen uit de referentietabel
     */
    public function findPlaatsen() {
        return $this->findAllFromDomain('P');
    }

    /**
     * Functie: findNiveaus
     * Doel:    vind alle niveau's uit de referentietabel
     */
    public function findNiveaus() {
        return $this->findAllFromDomain('N');
    }

    /**
     * Functie: findAllFromDomain
     * Doel:    vind alles uit de referentietabel behorende bij een specifiek domein
     */
    private function findAllFromDomain($domain) {
        return $this->createQueryBuilder('r')
            ->andWhere('r.domein = :val')
            ->setParameter('val', $domain)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Referentie[] Returns an array of Referentie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Referentie
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
