<?php

namespace Mchuluq\Larv\EncryptedStorage\Exceptions;

use Exception;
use Throwable;

class InvalidCipherMethod extends Exception
{
    public function __construct(string $cipherMethod)
    {
        $message = "\"$cipherMethod\" must implement Mchuluq\Larv\EncryptedStorage\Interfaces\CipherMethodInterface.";
        parent::__construct($message);
    }
}