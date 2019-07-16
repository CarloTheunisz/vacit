<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Vacature;

class VacatureService {
    private $em;
    private $um;
    private $encoder;
    private $rep;
    private $referentie;
    private $sollicitatie;

    /**
     * Functie: __construct
     * Doel:    Constructor van de klasse
     * Taken:   Voert de autowiring voor de klasse uit
     */
    public function __construct(EntityManagerInterface $em,
        UserManagerInterface $um, 
        UserPasswordEncoderInterface $encoder, 
        ReferentieService $rs,
        SollicitatieService $ss) {
            $this->em = $em;
            $this->um = $um;
            $this->encoder = $encoder;
            $this->referentie = $rs;
            $this->sollicitatie = $ss;

            $this->rep = $this->em->getRepository(Vacature::class);
    }

    /**
     * Functie: find
     * Doel:    vind de vacature met het meegegeven id
     */
    public function find($id) {
        return $this->rep->find($id);
    }

    /**
     * Functie: findAll
     * Doel:    vind alle vacatures
     */
    public function findAll() {
        return $this->rep->findAll();
    }

    /**
     * Functie: getAllVacatures
     * Doel:    vind alle vacatures en ordert ze
     */
    public function getAllVacatures() {
        return $this->rep->getAllVacatures();
    }

    /**
     * Functie: findVacaturesFromUser
     * Doel:    vind alle vacatures van een specifieke werkgever en ordert ze
     */
    public function findVacaturesFromUser($user) {
        return $this->rep->findVacaturesFromUser($user);
    }

    /**
     * Functie: getAndereVacatures
     * Doel:    vind alle vacatures van een specifieke werkgever behalve de meegegeven vacature en ordert ze
     */
    public function getAndereVacatures($vacature) {
        return $this->rep->getAndereVacatures($vacature);
    }

    /**
     * Functie: findVacaturesByFK
     * Doel:    vind een sollicitatie via de foreign key (gebruiker en vacature)
     */
    public function findSollicitatieByFK($userId, $vacId) {
        return $this->sollicitatie->findSollicitatieByFK($userId, $vacId);
    }

    /**
     * Functie: getNewVacatureForm
     * Doel:    retourneert een formulier om een vacature toe te voegen of te bewerken
     */
    public function getNewVacatureForm($vacature, $user, $submit) {
        if ($user == null) {
            return Forms::createFormFactory()->createBuilder()
                ->getForm();
        }

        return Forms::createFormFactory()->createBuilder(FormType::class, $vacature)
            ->add('user', ChoiceType::class, [
                'choices' => [$user],
                'choice_label' => 'getVoornaam', 
                'attr' => ['class' => 'no-display'],
                'label' => false])
            ->add('niveau', ChoiceType::class, [
                'choices' => $this->referentie->findNiveaus(),
                'choice_label' => 'getOmschrijving'])
            ->add('plaats', ChoiceType::class, [
                'choices' => $this->referentie->findPlaatsen(), 
                'choice_label' => 'getOmschrijving'])
            ->add('titel', TextType::class)
            ->add('datum', DateType::class, ['data' => new \DateTime(), 
                'attr' => ['class' => 'no-display'],
                'label' => false])
            ->add('icoon', TextType::class, ['attr' => ['placeholder' => 'icoon_placeholder']])
            ->add('omschrijving', TextareaType::class)
            ->add('save', SubmitType::class, ['label' => $submit,
                'attr' => ['class' => 'bebase orange larger right-float']])
            ->getForm();
    }
}