<?php

namespace App\Exceptions;

use Exception;

class ApiErrorMessageException extends Exception
{
    public function render($request, Exception $exception)
    {
        return response()->json([
            'status' => 'error',
            'message'    => $exception->getMessage()
        ]);
    }
}
