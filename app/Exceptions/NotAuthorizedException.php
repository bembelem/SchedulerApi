<?php

namespace App\Exceptions;

use Exception;

class NotAuthorizedException extends Exception
{
    /**
     * Create a new NotAuthorizedException instance.
     *
     * @param string|null $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(?string $message = "Access for this resource is denied", int $code = 403, ?Exception $previous = null)
    {

        parent::__construct($message, $code, $previous);
    }
}
