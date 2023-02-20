<?php

namespace App\Form;

use App\Entity\Prestation;
use App\Entity\Rdv;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RdvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        
        $builder
        ->add('dateRdv', DateTimeType::class, [
            'label' => 'Date et heure du rendez-vous',
            'widget' => 'single_text',
            'html5' => true,
            'attr' => [
                'class' => 'js-datepicker',
                'autocomplete' => 'off',
                'min' => Date('Y-m-d'), // Limiter les dates disponibles à partir d'aujourd'hui
                'step' => 1800, // Intervalle de 30 minutes entre les heures
            ],
        ])
            ->add('description',TextType::class,[
                'label'=> 'votre message',
            ])
            ->add('prestations', EntityType::class, [
                'label' => false,
                'required' => true,
                'class' => Prestation::class,
                'choice_label' => function (Prestation $prestation) {
                    return $prestation->getLibelle() .' '.$prestation->getLibelle(). ' (' . $prestation->getPrix() / 100 . ' €)';
                },
                'multiple' => true,
                'expanded' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rdv::class,
        ]);
    }
}
