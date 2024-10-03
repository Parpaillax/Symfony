<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
              'label' => 'Email',
              'label_attr' => ['class' => 'text-gray-700 font-bold'],
              'attr' => [
                'class' => 'w-full mb-4 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
                'placeholder' => 'Adresse e-mail',
              ],
            ])
            ->add('username', TextType::class, [
              'label' => 'Nom d\'utilisateur',
              'label_attr' => ['class' => 'text-gray-700 font-bold'],
              'attr' => [
                'class' => 'w-full mb-4 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
                'placeholder' => 'Nom d\'utilisateur',
              ],
            ])
//            ->add('agreeTerms', CheckboxType::class, [
//                'mapped' => false,
//                'constraints' => [
//                    new IsTrue([
//                        'message' => 'You should agree to our terms.',
//                    ]),
//                ],
//            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => 'Mot de passe',
                'label_attr' => ['class' => 'text-gray-700 font-bold'],
                'attr' => [
                  'autocomplete' => 'new-password',
                  'class' => 'w-full mb-4 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
                  'placeholder' => 'Mot de passe',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
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
