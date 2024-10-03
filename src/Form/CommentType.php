<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Wish;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
              'label' => 'Commentaire',
              'label_attr' => ['class' => 'text-gray-700 font-bold'],
              'attr' => [
                'class' => 'w-full mb-4 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
                'placeholder' => 'Rentrer votre commentaire ici...',
              ],
            ])
            ->add('score', HiddenType::class, [
              'label' => 'Score (Entre 0 et 5)',
              'label_attr' => ['class' => 'text-gray-700 font-bold'],
              'attr' => [
                'class' => 'star-rating-value',
              ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
