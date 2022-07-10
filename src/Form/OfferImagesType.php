<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class OfferImagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descriptionEn', TextType::class, [
                'label' => 'Popis EN',
                'required' => false,
            ])
            ->add('descriptionCs', TextType::class, [
                'label' => 'Popis CZ',
                'required' => false,
            ])
            ->add('files', FileType::class, [
                'label' => 'Přidejte obrázky',
                'required' => false,
                'mapped' => false,
                'multiple' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Uložit',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }

}