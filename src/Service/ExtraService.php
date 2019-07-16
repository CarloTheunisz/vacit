<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Vacature;

class ExtraService {
    private $em;
    private $um;
    private $encoder;

    private $rep;

    /**
     * Functie: __construct
     * Doel:    Constructor van de klasse
     * Taken:   Voert de autowiring voor de klasse uit
     */
    public function __construct(EntityManagerInterface $em,
        UserManagerInterface $um, 
        UserPasswordEncoderInterface $encoder) {
            $this->em = $em;
            $this->um = $um;
            $this->encoder = $encoder;

            $this->rep = $this->em->getRepository(Vacature::class);
    }

    /**
     * Functie: findVacature
     * Doel:    vind de vacature met het meegegeven id
     */
    public function findVacature($vacId) {
        return $this->rep->find($vacId);
    }
}