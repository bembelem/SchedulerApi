<?php

namespace App\Exceptions;

use App\Traits\ApiResponses;
use Exception;
use Illuminate\Http\JsonResponse;

class AlreadyAuthenticatedException extends Exception
{
    use ApiResponses{
        alreadyAuthorized as authorizedResponse;
    }
    
    public function render() : JsonResponse {
        return $this->authorizedResponse();
    }
}
