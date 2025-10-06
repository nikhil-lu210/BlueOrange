<?php

namespace App\Exceptions;

use Psr\Log\LoggerInterface;
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
            // Resolve a PSR logger from the container to avoid using facades
            // (facades may not be available very early in the bootstrap/exception
            // flow which causes "A facade root has not been set" errors).
            $logger = null;
            try {
                $logger = app(LoggerInterface::class);
            } catch (\Throwable $e) {
                // If the container isn't available for some reason, fall back.
                $logger = null;
            }

            $context = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'code' => $exception->getCode(),
                'trace' => array_slice($exception->getTrace(), 0, ErrorConfiguration::getMaxTraceEntries()),
            ];

            if ($logger instanceof LoggerInterface) {
                $logger->error('Application Exception', $context);
            } else {
                // Last-resort fallback for very early exceptions
                error_log('Application Exception: ' . $exception->getMessage());
                error_log(print_r($context, true));
            }
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
