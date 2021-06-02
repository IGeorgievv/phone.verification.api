<?php
declare(strict_types=1);

namespace App\Error;

use JsonSchema\Exception\ValidationException;

class JsonValidationException extends ValidationException
{
    private $errors = null;

    function __construct($message = "", $errors = [])
    {
        parent::__construct($message, 400);

        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}