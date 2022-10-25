<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class AdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $ad = $options['data'] ?? null;
        $isEdit = $ad && $ad->getId();
        $imageConstraints = [
            new All([
                new Image([
                    'maxSize' => '1M',
                    'maxSizeMessage' => 'Fichier trop volumineux!'
                ])
            ])
        ];

        if (!$isEdit) {
            $imageConstraints[] = new NotNull();
        }

        $builder
            ->add('title')
            ->add('description')
            ->add('price')
            ->add('thumbnailsUrls', FileType::class, [
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'constraints' => $imageConstraints,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
