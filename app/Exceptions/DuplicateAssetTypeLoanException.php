<?php

namespace App\Exceptions;

use Exception;

class DuplicateAssetTypeLoanException extends Exception
{
    protected $message = 'Employee already has an active loan for this asset type.';
}