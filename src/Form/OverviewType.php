<?php

namespace App\Form;

use App\Entity\Overview;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class OverviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotNull(),
                ]
            ])
            ->add('type', TextType::class, [
                'constraints' => [
                    new NotNull(),
                ]
            ])
            ->add('tags', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'constraints' => [
                    new NotNull(),
                ]
            ])
            ->add('text', TextType::class, [
                'constraints' => [
                    new NotNull(),
                ]
            ])
            ->add('grade', IntegerType::class, [
                'constraints' => [
                    new NotNull(),
                ]
            ])
            ->add('images',  CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'constraints' => [
                    new NotNull(),
                ]
            ])
            ->add('dateTime', DateTimeType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotNull(),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Overview::class
        ]);
    }
}
