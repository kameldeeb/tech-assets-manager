<?php

namespace App\Exceptions;

use Exception;

class InvalidReturnOperationException extends Exception
{
    protected $message = 'This loan has already been returned.';
}