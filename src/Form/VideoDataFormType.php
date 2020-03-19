<?php

namespace App\Form;

use App\Entity\LexaniVideos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VideoDataFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('youtubeLink', TextType::class)
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('thumbnails', TextType::class)
            ->add('parseType', ChoiceType::class, [
                'choices' => [
                    'new' => 'new',
                    'old' => 'old',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LexaniVideos::class,
        ]);
    }
}