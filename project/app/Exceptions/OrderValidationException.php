<?php

namespace App\Exceptions;

use Exception;

class OrderValidationException extends Exception
{
    public $errors = [];
}
