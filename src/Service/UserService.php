<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\User;

class UserService {
    private $em;
    private $um;
    private $encoder;

    private $rep;
    private $referentie;
    private $vacature;
    private $sollicitatie;

    /**
     * Functie: __construct
     * Doel:    Constructor van de klasse
     * Taken:   Voert de autowiring voor de klasse uit
     */
    public function __construct(EntityManagerInterface $em,
        UserManagerInterface $um, 
        UserPasswordEncoderInterface $encoder,
        ReferentieService $referentie,
        VacatureService $vacature,
        SollicitatieService $sollicitatie) {
            $this->em = $em;
            $this->um = $um;
            $this->encoder = $encoder;

            $this->rep = $this->em->getRepository(User::class);
            $this->referentie = $referentie;
            $this->vacature = $vacature;
            $this->sollicitatie = $sollicitatie;
    }

    /**
     * Functie: find
     * Doel:    vind de user met een specifiek id
     */
    public function find($id) {
        return $this->rep->find($id);
    }

    /**
     * Functie: findAll
     * Doel:    vind alle users
     */
    public function findAll() {
        return $this->rep->findAll();
    }

    /**
     * Functie: findSollicitatieByFK
     * Doel:    vind de sollicitatie met een specifiek foreign key (user en vacature)
     */
    public function findSollicitatieByFK($userId, $vacId, $remove = false) {
        return $this->sollicitatie->findSollicitatieByFK($userId, $vacId, $remove);
    }

    /**
     * Functie: findSollicitatiesFromUser
     * Doel:    vind alle sollicitaties met een specifiek user id
     */
    public function findSollicitatiesFromUser($user) {
        return $this->sollicitatie->findSollicitatiesFromUser($user);
    }

    /**
     * Functie: findVacaturesFromUser
     * Doel:    vind de vacatures met een specifiek user id
     */
    public function findVacaturesFromUser($user) {
        return $this->vacature->findVacaturesFromUser($user);
    }

    /**
     * Functie: findSollicitatiesFromVacatures
     * Doel:    vind de sollicitaties met een specifiek vacature id
     */
    public function findSollicitatiesFromVacatures($vacatures) {
        $sollicitaties = [];
        foreach($vacatures as $vacature) {
            $sollicitaties[] = $this->sollicitatie->findSollicitatiesFromVacature($vacature);
        }

        return $sollicitaties;
    }

    /**
     * Functie: setSollicitatie
     * Doel:    maak een nieuwe sollicitatie aan
     */
    public function setSollicitatie($userId, $vacId) {
        return $this->sollicitatie->setSollicitatie($userId, $vacId);
    }

    /**
     * Functie: toggleUitgenodigd
     * Doel:    toggle de uitgenodigd boolean van een sollicitatie
     */
    public function toggleUitgenodigd($id, $isChecked) {
        return $this->sollicitatie->toggleUitgenodigd($id, $isChecked);
    }

    /**
     * Functie: importEmployers
     * Doel:    maak nieuwe user records aan als er nog geen records met de gevonden e-mails bestaan
     */
    public function importEmployers($rows) {
        foreach ($rows as $row) {
            $u = $this->um->findUserByEmail($row["email"]);
            if (!$u) {
                $this->createNewUser($row);
            }
        }
        return 'All employers have been added.'; 
    }

    /**
     * Functie: createNewUser
     * Doel:    maak een nieuwe gebruiker aan. 
     *          Gebruikers die met deze functie aangemaakt worden krijgen de rol 'ROLE_EMPLOYER'.
     */
    private function createNewUser($row) {
        $datum = new \DateTime();
        $plaats = $this->em->getReference(Referentie::class, $row["plaats_id"]);
        $user = $this->um->createUser();

        $user->setUsername($row["username"]);
        $user->setUsernameCanonical($row["username"]);
        $user->setEmail($row["email"]);
        $user->setEmailCanonical($row["email"]);
        $user->setEnabled(true);
        $user->setSalt($row["salt"]);
        $password = $this->encoder->encodePassword(
            $user, "default");
        $user->setPassword($password);
        $user->setLastLogin($datum);
        $user->setPasswordRequestedAt($datum);
        $user->addRole("ROLE_EMPLOYER");
        $user->removeRole("ROLE_CANDIDATE");
        $user->setPlaats($plaats);
        $user->setVoornaam($row["naam"]);
        $user->setTelefoonnummer($row["telefoonnummer"]);
        $user->setAdres($row["adres"]);
        $user->setPostcode($row["postcode"]);
        $user->setOmschrijving($row["omschrijving"]);
        $user->setAfbeelding($row["afbeelding"]);
        $user->setSector($row["sector"]);
        $user->setContactpersoon($row["contactpersoon"]);

        $this->um->updateUser($user);
    }
}