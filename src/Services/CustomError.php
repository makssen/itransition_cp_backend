<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;

class CustomError
{
    public static function UnauthorizedError(string $message): array
    {
        return [
            'message' => $message,
            'status' => Response::HTTP_UNAUTHORIZED
        ];
    }
}
