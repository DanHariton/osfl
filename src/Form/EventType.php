<?php

namespace App\Form;

use App\Entity\Preparing;
use App\Service\EntityTranslator;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EventType extends AbstractType
{
    private EntityTranslator $entityTranslator;

    public function __construct(EntityTranslator $entityTranslator)
    {
        $this->entityTranslator = $entityTranslator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('preparing', EntityType::class, [
                'class' => Preparing::class,
                'choice_label' => function (Preparing $preparing) {
                    return $this->entityTranslator->read($preparing->getMonth());
                },
            ])
            ->add('date', DateType::class, [
                'label' => 'Datum',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('descriptionEn', TextType::class, [
                'label' => 'Popis EN',
                'required' => false,
            ])
            ->add('descriptionCs', TextType::class, [
                'label' => 'Popis CZ',
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