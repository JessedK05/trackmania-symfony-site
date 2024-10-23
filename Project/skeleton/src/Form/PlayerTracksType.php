<?php

namespace App\Form;

use App\Entity\PlayerTracks;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class PlayerTracksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $imageConstraints = [
            new Image([
                'maxSize' => '5M',
            ])
        ];

        $builder
            ->add('title')
            ->add('description')
            ->add('AuthorTime', null, [
                'widget' => 'single_text'
            ])
            ->add('Difficulty')
            ->add('tracktype')
            ->add('Image', FileType::class, [
                'mapped' => false,
                'required' => true,
                'constraints' => new Image([
                    'maxSize' => '5M'
                ]),
            ])
            ->add('Replay', FileType::class, [
                'mapped' => false,
                'required' => true,
            ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('upload', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PlayerTracks::class,
        ]);
    }
}
