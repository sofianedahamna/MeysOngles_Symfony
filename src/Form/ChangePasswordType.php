<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email' , EmailType::class, 
            [
                'disabled' => true,
                'label' => 'mon adresse email'
            ])
           
            ->add('firstname', TextType::class,
             [
                'disabled' => true,
                'label'=> 'mon prénom'
            ])
            ->add('lastname', TextType::class, 
            [
                'disabled' => true,
                'label' => 'mon nom'
            ])
             ->add('old_password', PasswordType::class, [
                
                'label' => 'Mon mot de passe actuel',
                'mapped' => false,
                'attr'=>
                [
                    'placeholder' => 'veuillez saisir votre mot de passe'
                ]
             ])
             
             ->add('new_password',RepeatedType::class,
             [
                 'type'=> PasswordType::class,
                 'invalid_message'=> 'Le mot de passe et la confirmation doivent etre identique',
                 'label' => 'Mon nouveau mot de passe',
                 'required' => true,
                 'mapped' => false,
                 'first_options' =>
                 [   
                     'label' => 'Mon nouveau mot de passe',
                     'attr' =>
                     [
                         'placeholder' => 'Merci de saisir votre mot de passe.'
                     ]
                 ],
                 'second_options' =>
                 [
                     'label' => 'Confirmez votre nouveau mot de passe',
                     'attr' =>
                     [
                         'placeholder' => 'Merci de confirmer votre mot de passe.'
                     ]
                 ]
             ])
             ->add('submit',SubmitType::class,
            [
                'label' => "Mettre a jour"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
