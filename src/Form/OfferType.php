<?php

namespace App\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameEn', TextType::class, [
                'label' => 'Název EN',
                'required' => false,
            ])
            ->add('nameCs', TextType::class, [
                'label' => 'Název CZ',
                'required' => false,
            ])
            ->add('descriptionEn', CKEditorType::class, [
                'label' => 'Popis EN',
                'required' => false,
            ])
            ->add('descriptionCs', CKEditorType::class, [
                'label' => 'Popis CZ',
                'required' => false,
            ])
            ->add('orderShow', NumberType::class, [
                'label' => 'Pořadí zobrazení',
                'required' => true,
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