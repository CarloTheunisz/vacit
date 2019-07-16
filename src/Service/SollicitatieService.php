<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\Sollicitatie;

class SollicitatieService {
    private $em;
    private $um;
    private $encoder;

    private $rep;
    private $vacature;

    /**
     * Functie: __construct
     * Doel:    Constructor van de klasse
     * Taken:   Voert de autowiring voor de klasse uit
     */
    public function __construct(EntityManagerInterface $em,
        UserManagerInterface $um, 
        UserPasswordEncoderInterface $encoder,
        ExtraService $es) {
            $this->em = $em;
            $this->um = $um;
            $this->encoder = $encoder;

            $this->rep = $this->em->getRepository(Sollicitatie::class);
            $this->vacature = $es;
    }

    /**
     * Functie: find
     * Doel:    vind de sollicitatie met een specifiek id
     */
    public function find($id) {
        return $this->rep->find($id);
    }

    /**
     * Functie: findAll
     * Doel:    vind alle sollicitaties
     */
    public function findAll() {
        return $this->rep->findAll();
    }

    /**
     * Functie: findSolliciatieByFK
     * Doel:    geeft terug of een sollicitatie met een specifieke foreign key (user en vacature) bestaat.
     *          Als $remove true is, wordt een eventueel gevonden record verwijderd.
     */
    public function findSollicitatieByFK($userId, $vacId, $remove = false) {
        if ($sollicitatie = $this->rep->findOneBy(["user" => $userId, "vacature" => $vacId])) {
            if ($remove) {
                $this->em->remove($sollicitatie);
                $this->em->flush();
            }
            return true;
        }
        return false;
    }

    /**
     * Functie: findSollicitatiesFromUser
     * Doel:    vind alle sollicitaties van een specifieke gebruiker
     */
    public function findSollicitatiesFromUser($user) {
        return $this->rep->createQueryBuilder('s')
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
        return $this->rep->createQueryBuilder('s')
            ->andWhere('s.vacature = :val')
            ->setParameter('val', $vacature->getId())
            ->orderBy('s.datum DESC, s.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Functie: setSollicitatie
     * Doel:    maak een nieuwe sollicitatie aan
     */
    public function setSollicitatie($userId, $vacId) {
        $user = $this->um->findUserBy(['id' => $userId]);
        $vacature = $this->vacature->findVacature($vacId);
        $datum = new \DateTime();

        $sollicitatie = new Sollicitatie();
        $sollicitatie->setUser($user);
        $sollicitatie->setVacature($vacature);
        $sollicitatie->setDatum($datum);

        $this->em->persist($sollicitatie);
        $this->em->flush();
    }

    /**
     * Functie: toggleUitgenodigd
     * Doel:    toggle de uitgenodigd boolean van een sollicitatie
     */
    public function toggleUitgenodigd($id, $isChecked) {
        $sollicitatie = $this->rep->findOneBy(["id" => $id]);

        if ($isChecked) {
            $sollicitatie->setUitgenodigd(1);
            $this->em->flush();
            return new JsonResponse(['output' => true]);
        }
        $sollicitatie->setUitgenodigd(0);
        $this->em->flush();
        return new JsonResponse(['output' => false]);
    }

    /**
     * Functie: findVacature
     * Doel:    vind de vacature met een specifiek id
     */
    private function findVacature(VacatureService $vacature) {
        return $vacature->find($vacId);
    }
}