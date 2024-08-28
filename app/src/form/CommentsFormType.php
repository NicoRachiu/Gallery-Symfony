<?php

// src/Form/CommentsFormType.php
namespace App\form;

use App\Entity\Comment;
use App\Entity\Photos;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Title',
                'attr' => ['class' => 'form-control']
            ])
            ->add('body', null, [
                'label' => 'Body',
                'attr' => ['class' => 'form-control']
            ])
            ->add('users', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'username',
                'label' => 'Select User',
                'attr' => ['class' => 'form-control']
            ])
            ->add('photos', EntityType::class, [
                'class' => Photos::class,
                'choice_label' => 'title',
                'label' => 'Select Photo',
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
