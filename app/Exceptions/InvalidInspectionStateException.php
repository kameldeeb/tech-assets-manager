<?php

namespace App\Exceptions;

use Exception;

class InvalidInspectionStateException extends Exception
{
    protected $message = 'Asset is not currently under inspection.';
}