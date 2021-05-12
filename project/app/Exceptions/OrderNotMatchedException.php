<?php

namespace App\Exceptions;

use Exception;

class OrderNotMatchedException extends Exception
{
    public $errors = [];
}
