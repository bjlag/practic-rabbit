<?php

namespace Api\Http\Validator;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SymfonyValidator
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($object): array
    {
        $violations = $this->validator->validate($object);
        $errors = [];

        if ($violations->count() > 0) {
            /** @var ConstraintViolationListInterface $violation */
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
        }

        return $errors;
    }
}