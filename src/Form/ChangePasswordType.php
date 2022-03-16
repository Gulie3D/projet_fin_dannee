<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true, //permet de na pas changer l'email
                'label' => 'Mon adresse email'
            ])
            ->add('firstname', TextType::class, [
                'disabled' => true, //permet de na pas changer le firstname
                'label' => 'Mon prénom'
            ])
            ->add('lastname', TextType::class, [
                'disabled' => true, //permet de na pas changer le lastname
                'label' => 'Mon nom'
            ])
            ->add('old_password', PasswordType::class, [
                'mapped' => false, //permet de ne pas lié ce champ avec l'entité 
                'label' => 'Mon mot de passe actuelle',
                'attr' => [
                    'placeholder' => 'veuillez saisir votre mot de passe actuelle'
                ]
            ])
            ->add('new_password', RepeatedType::class, [ //extension qui permet 2 fois le même champs qui dois avoir le même contenue
                'type' => PasswordType::class, //extensions qui permet de masquer la saisie du mot de passe 
                'mapped' => false, 
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identique',
                'label' => 'Mon npuveau mot de passe',
                'required' => true,
                'first_options' => [
                    'label' => 'Mon nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre nouveau mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de confirmer votre nouveau mot de passe'
                    ]
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Mettre à jour'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
