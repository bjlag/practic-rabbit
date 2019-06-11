<?php

namespace Api\Http\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class SymfonyValidator
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($object): ?Errors
    {
        $violations = $this->validator->validate($object);

        return $violations->count() > 0 ? new Errors($violations) : null;
    }
}