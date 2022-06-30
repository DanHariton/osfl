<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class IsValidPassword extends Constraint
{
    /** @var string */
    public string $message = 'Nesprávné heslo!';
}