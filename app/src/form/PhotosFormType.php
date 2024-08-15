<?php

namespace App\Form;

use App\Entity\Photos;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhotosFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Title',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', null, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control']
            ])
            ->add('imagePath', null, [
                'label' => 'Image Path',
                'attr' => ['class' => 'form-control']
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Select Users',
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Photos::class,
        ]);
    }
}