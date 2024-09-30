<?php

namespace App\Form;

use App\Entity\Wish;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Titre', 'attr' => [
              'class' => 'w-full mb-4 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
              'placeholder' => 'Nom du voeu',
            ]])
            ->add('content', TextareaType::class, ['label' => 'Description', 'required' => false, 'attr' => [
              'class' => 'w-full mb-4 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
              'placeholder' => 'Description du voeu',
            ]])
            ->add('author', TextType::class, ['label' => 'Auteur', 'attr' => [
              'class' => 'w-full mb-4 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
              'placeholder' => 'Auteur du voeu',
            ]])
            ->add('duration', IntegerType::class, ['label' => 'Durée (jours)', 'attr' => [
              'class' => 'w-full mb-4 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
              'placeholder' => 'Temps pour réaliser le voeu',
            ]])
            ->add('published', CheckboxType::class, ['label' => 'Publié', 'required' => false, 'attr' => [
              'class' => 'w-4 h-4 mb-4 border-gray-300 rounded',
            ]])
            ->add('dateCreated', null, [
                'widget' => 'single_text',
                'attr' => [
                  'class' => 'w-full mb-4 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
                ]
            ])
          ->add('image', FileType::class, [
            'label' => 'Image',
            'mapped' => false,
            'required' => false,
            'attr' => [
              'class' => 'w-full mb-4 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
            ],
            'constraints' => [
              new File([
                'maxSize' => '1024k',
                'mimeTypes' => [
                  'image/png',
                  'image/jpeg',
                ],
                'mimeTypesMessage' => 'Please upload a valid image',
              ])
            ],
          ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
