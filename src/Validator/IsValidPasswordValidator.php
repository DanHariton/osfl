<?php

namespace App\Validator;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IsValidPasswordValidator extends ConstraintValidator
{
    /** @var TokenInterface|null  */
    private ?TokenInterface $token;

    /** @var UserPasswordHasherInterface  */
    private UserPasswordHasherInterface $passwordEncoder;

    /**
     * IsValidPasswordValidator constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param UserPasswordHasherInterface $passwordEncoder
     */
    public function __construct(TokenStorageInterface $tokenStorage, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->token = $tokenStorage->getToken();
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsValidPassword) {
            throw new UnexpectedTypeException($constraint, IsValidPassword::class);
        }

        if (!$this->token) {
            return;
        }

        $user = $this->token->getUser();

        if (!($user instanceof User)) {
            return;
        }

        if ($this->passwordEncoder->isPasswordValid($user, $value)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}