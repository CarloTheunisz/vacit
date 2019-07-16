<?php

namespace App\Form;

use App\Entity\User;
use App\Service\ReferentieService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CandidateForm extends AbstractType
{
    private $referentie;

    public function __construct(ReferentieService $rs) {
        $this->referentie = $rs;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('voornaam', TextType::class)
            ->add('achternaam', TextType::class)
            ->add('afbeelding', FileType::class, ['data_class' => null,
                'attr' => ['class' => 'no-display']])
            ->add('email', TextType::class)
            ->add('geboortedatum', DateType::class, ['widget' => 'single_text'])
            ->add('telefoonnummer', TextType::class)
            ->add('adres', TextType::class)
            ->add('postcode', TextType::class)
            ->add('plaats', ChoiceType::class, [
                'choices' => $this->referentie->findPlaatsen(), 
                'choice_label' => 'getOmschrijving'])
            ->add('omschrijving', TextareaType::class, ['attr' => ['class' => 'richtext'],
                'label_attr' => ['class' => 'bebase orange larger']])
            ->add('cv', FileType::class, ['data_class' => null,
                'attr' => ['class' => 'no-display']])
            ->add('save', SubmitType::class, ['label' => 'Opslaan',
                'attr' => ['class' => 'bebase orange larger right-float']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
