<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Validation\ValidationException;

class ExceptionLogger
{
    /**
     * Log exception if it should be logged.
     */
    public static function logIfNeeded(Throwable $exception): void
    {
        if (self::shouldLog($exception)) {
            Log::error('Application Exception', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'code' => $exception->getCode(),
                'trace' => array_slice($exception->getTrace(), 0, ErrorConfiguration::getMaxTraceEntries()),
            ]);
        }
    }

    /**
     * Determine if the exception should be logged.
     */
    private static function shouldLog(Throwable $exception): bool
    {
        // Don't log 404 errors as they're common and not critical
        if ($exception instanceof HttpExceptionInterface && $exception->getStatusCode() === 404) {
            return false;
        }

        // Don't log validation exceptions
        if ($exception instanceof ValidationException) {
            return false;
        }

        return true;
    }
}
