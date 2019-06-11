<?php

namespace Api\Http\Validator;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class Errors
{
    private $errors;

    public function __construct(ConstraintViolationListInterface $violations)
    {
        if ($violations->count() > 0) {
            foreach ($violations as $violation) {
                $this->errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}