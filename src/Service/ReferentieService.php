<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Referentie;

class ReferentieService {
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

            $this->rep = $this->em->getRepository(Referentie::class);
    }

    /**
     * Functie: find
     * Doel:    vind de record uit de referentietabel met het meegegeven id
     */
    public function find($id) {
        return $this->rep->find($id);
    }

    /**
     * Functie: findAll
     * Doel:    vind alle records uit de referentietabel
     */
    public function findAll() {
        return $this->rep->findAll();
    }
    
    /**
     * Functie: findPlaatsen
     * Doel:    vind alle plaatsen uit de referentietabel
     */
    public function findPlaatsen() {
        return $this->rep->findPlaatsen();
    }

    /**
     * Functie: findNiveaus
     * Doel:    vind alle niveau's uit de referentietabel
     */
    public function findNiveaus() {
        return $this->rep->findNiveaus();
    }
}