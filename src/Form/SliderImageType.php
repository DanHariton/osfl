<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class SliderImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class, [
                'label' => 'Obrázek (5Mb max)',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Nahrajte obrázek (max size 5MB)!',
                    ])
                ],
            ])
            ->add('altName', TextType::class, [
                'label' => 'Krátký popis obrázku',
                'required' => false,
            ])
            ->add('displayOrder', NumberType::class, [
                'label' => 'Pořadí zobrazení',
                'required' => false,
            ])
            ->add('mainTextCs', TextType::class, [
                'label' => 'Velký nadpis CZ',
                'required' => false,
            ])
            ->add('mainTextEn', TextType::class, [
                'label' => 'Velký nadpis EN',
                'required' => false,
            ])
            ->add('smallTextCs', TextType::class, [
                'label' => 'Malý nadpis CZ',
                'required' => false,
            ])
            ->add('smallTextEn', TextType::class, [
                'label' => 'Malý nadpis EN',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Uložit',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }
}