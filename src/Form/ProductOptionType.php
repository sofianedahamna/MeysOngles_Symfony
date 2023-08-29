<?php

namespace App\Form;

use App\Entity\ProductOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductOptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       
        $builder
            ->add('quantity', HiddenType::class, [
                'data' => 1,
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter au panier',
                'attr' => [
                    'class' => 'btn-block btn-success'
                ]
            ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        
        $resolver->setDefaults([
            'data_class' => ProductOption::class,
        ]);
    }
}
