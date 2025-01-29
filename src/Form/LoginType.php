<?php
// src/Form/LoginType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'attr' => [
                    'placeholder' => 'Votre adresse e-mail',
                    'class' => 'form-control', // Ajout d'une classe CSS pour le style
             
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer une adresse e-mail.']),
                    new Email(['message' => 'Veuillez entrer une adresse e-mail valide.']),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => 'Votre mot de passe',
                    'class' => 'form-control', // Ajout d'une classe CSS pour le style
                ],

                'constraints' => [
            new NotBlank(['message' => 'Veuillez entrer un mot de passe.']),
        ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Se connecter',
                'attr' => [
                    'class' => 'btn btn-primary w-100 mt-3', // Style bouton Bootstrap ou autre
                ],
            ]);
    }
}