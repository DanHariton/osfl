<?php

namespace App\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PreparingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('monthEn', TextType::class, [
                'label' => 'Měsíc EN',
                'required' => false,
            ])
            ->add('monthCs', TextType::class, [
                'label' => 'Měsíc CZ',
                'required' => false,
            ])
            ->add('scheduleEn', CKEditorType::class, [
                'label' => 'Rozvrh EN',
                'required' => false,
            ])
            ->add('scheduleCs', CKEditorType::class, [
                'label' => 'Rozvrh CZ',
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