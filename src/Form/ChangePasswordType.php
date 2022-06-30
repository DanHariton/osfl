<?php

namespace App\Form;

use App\Validator\IsValidPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('passwordBefore', PasswordType::class, [
                'required' => true,
                'label' => 'Staré heslo',
                'constraints' => [new IsValidPassword()]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Hesla nejsou stejná!',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Nové heslo'],
                'second_options' => ['label' => 'Nové heslo ještě jednou'],
                'constraints' => [new Length(null, 6)]
            ])
            ->add('submit', SubmitType::class, [
                'label' => '#ICON_SAVE# Uložit',
                'attr' => [
                    'class' => 'button'
                ]
            ]);
    }
}