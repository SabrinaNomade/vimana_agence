<?php
// src/Form/RegistrationType.php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Champ pour l'email avec validations
            ->add('email', EmailType::class, [
                'label' => 'Adresse Email',
                'required' => true, // Champ obligatoire
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une adresse email.',
                    ]),
                    new Email([
                        'message' => 'Veuillez saisir une adresse email valide.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'exemple@domaine.com',
                ],
            ])
            
            // Champ pour le prénom
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre prénom.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Votre prénom',
                ],
            ])
            
            // Champ pour le nom
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre nom.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Votre nom',
                ],
            ])
            
            // Champ pour le téléphone (optionnel)
            ->add('phone', TextType::class, [
                'label' => 'Téléphone (optionnel)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Numéro de téléphone (facultatif)',
                ],
            ])
            
            // Champ pour le mot de passe avec validations
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false, // Ce champ n'est pas directement lié à l'entité User
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                        'max' => 4096, // Limite recommandée pour le hashage
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Choisissez un mot de passe sécurisé',
                ],
            ])
            
            // Bouton de soumission
            ->add('submit', SubmitType::class, [
                'label' => 'Créer mon compte',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ]);

        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    

}
