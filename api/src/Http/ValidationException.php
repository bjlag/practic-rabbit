<?php

namespace Api\Http;

use Api\Http\Validator\Errors;

class ValidationException extends \LogicException
{
    private $errors;

    public function __construct(Errors $errors)
    {
        parent::__construct('Validation error.');

        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors->getErrors();
    }
}