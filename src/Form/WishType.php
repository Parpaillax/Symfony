<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Player;
use App\Entity\Wish;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('name', TextType::class, ['label' => 'Titre',
              'attr' => [
                'class' => 'w-full mb-4 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
                'placeholder' => 'Nom du voeu',
              ],
              'label_attr' => ['class' => 'text-gray-700 font-bold']
            ])
            ->add('content', TextareaType::class, ['label' => 'Description', 'required' => false,
              'attr' => [
                'class' => 'w-full mb-4 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
                'placeholder' => 'Description du voeu',
              ], 'label_attr' => ['class' => 'text-gray-700 font-bold']
            ])
//            ->add('author', TextType::class, ['label' => 'Auteur', 'attr' => [
//              'class' => 'w-full mb-4 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
//              'placeholder' => 'Auteur du voeu',
//            ], 'label_attr' => ['class' => 'text-gray-700 font-bold']
//            ])
            ->add('category', EntityType::class, ['label' => 'Catégorie',
              'attr' => [
                'class' => 'w-full mb-4 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
                'placeholder' => '--Choisir une catégorie--',
              ],
              'label_attr' => ['class' => 'text-gray-700 font-bold'],
              'choice_label' => 'name',
              'class' => Categorie::class,
            ])
            ->add('players', EntityType::class, ['label' => 'Participants',
              'attr' => [
                'class' => 'w-full mb-4 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
                'placeholder' => '--Choisir un participant--',
              ],
              'label_attr' => ['class' => 'text-gray-700 font-bold'],
              'choice_label' => 'fullName',
              'class' => Player::class,
              'required' => false,
              'multiple' => true,
              'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('p')
                  ->orderBy('p.lastName', 'ASC')
                  ->addOrderBy('p.firstName', 'ASC');
              }
            ])
            ->add('duration', IntegerType::class, ['label' => 'Durée (jours)', 'attr' => [
              'class' => 'w-full mb-4 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
              'placeholder' => 'Temps pour réaliser le voeu',
            ], 'label_attr' => ['class' => 'text-gray-700 font-bold']
            ])
            ->add('realised', CheckboxType::class, ['label' => 'Réalisé', 'required' => false, 'attr' => [
              'class' => 'w-4 h-4 mb-4 border-gray-300 rounded mx-2',
            ], 'label_attr' => ['class' => 'text-gray-700 font-bold px-4']
            ])
            ->add('dateCreated', null, [
                'label' => 'Date limite',
                'widget' => 'single_text',
                'attr' => [
                  'class' => 'w-full mb-4 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
                ],
                'label_attr' => ['class' => 'text-gray-700 font-bold']
            ])
          ->add('image', FileType::class, [
            'label' => 'Image',
            'mapped' => false,
            'required' => false,
            'attr' => [
              'class' => 'w-full mb-4 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
            ],
            'label_attr' => ['class' => 'text-gray-700 font-bold'],
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
          ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $wish = $event->getData();
            if ($wish && $wish->getImageFilename()) {
              $form = $event->getForm();
              $form->add('deleteImage', CheckboxType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Supprimer l\'image',
                'attr' => [
                  'class' => 'w-4 h-4 mb-4 border-gray-300 rounded mx-2',
                ], 'label_attr' => ['class' => 'text-gray-700 font-bold px-4']
              ]);
            }
          })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
