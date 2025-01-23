<?php

namespace App\Exceptions;

use App\Traits\ApiResponses;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ExceptionHandler
{
    use ApiResponses;

    public function handleApiExceptions(Throwable $e)
    {
        // Обработка ModelNotFoundException
        if ($e->getPrevious() instanceof ModelNotFoundException){
            return $this->resourceNotFound();
        }

        // Обработка NotFoundHttpException
        else if ($e instanceof NotFoundHttpException){
            return $this->resourceNotFound('Route not found');
        }

        // Обработка ValidationException
        else if ($e instanceof ValidationException){
            return $this->error(
                $e->errors(), // Массив ошибок валидации
                422,
            );
        }

        else if ($e instanceof DuplicateEventException){
            return $this->error(
                $e->getMessage(),
                $e->getCode(),
            );
        }

        else if ($e instanceof NotAuthorizedException){
            return $this->error(
                $e->getMessage(),
                $e->getCode(),
            );
        }

        // Обработка других исключений (по необходимости)
    }
}
