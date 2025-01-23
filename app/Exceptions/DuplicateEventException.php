<?php

namespace App\Exceptions;

use Exception;

class DuplicateEventException extends Exception
{
    /**
     * Create a new DuplicateEventException instance.
     *
     * @param string|null $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(?string $message = 'Event is already exists for this time slot', int $code = 409, ?Exception $previous = null)
    {

        parent::__construct($message, $code, $previous);
    }

}
