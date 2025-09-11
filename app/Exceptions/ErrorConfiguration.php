<?php

namespace App\Exceptions;

class ErrorConfiguration
{
    /**
     * Error configurations for different HTTP status codes.
     */
    public static function getErrorData(int $statusCode, string $context = null): array
    {
        $baseErrors = match ($statusCode) {
            401 => [
                'title' => 'Authentication Required',
                'message' => 'Please log in to access this page.',
                'image' => 'assets/img/error/401.gif',
            ],
            403 => [
                'title' => 'Access Restricted',
                'message' => 'You don\'t have the required permissions to perform this action.',
                'image' => 'assets/img/error/403.gif',
            ],
            404 => [
                'title' => 'Page Not Found',
                'message' => 'The page you\'re looking for doesn\'t exist or has been moved.',
                'image' => 'assets/img/error/404.gif',
            ],
            419 => [
                'title' => 'Session Expired',
                'message' => 'Your session has expired. Please refresh the page and try again.',
                'image' => 'assets/img/error/419.gif',
            ],
            429 => [
                'title' => 'Too Many Requests',
                'message' => 'You\'ve made too many requests. Please wait a moment before trying again.',
                'image' => 'assets/img/error/429.gif',
            ],
            500 => [
                'title' => 'Server Error',
                'message' => 'Something went wrong on our end. Our team has been notified.',
                'image' => 'assets/img/error/500.gif',
            ],
            default => [
                'title' => 'Unexpected Error',
                'message' => 'An unexpected error occurred. Please try again later.',
                'image' => 'assets/img/error/default.gif',
            ],
        };

        // Add context-specific messages
        if ($context) {
            $baseErrors = array_merge($baseErrors, self::getContextualMessages($statusCode, $context));
        }

        return $baseErrors;
    }

    /**
     * Get contextual error messages based on the module or feature.
     */
    private static function getContextualMessages(int $statusCode, string $context): array
    {
        return match ($context) {
            'leave' => match ($statusCode) {
                403 => [
                    'title' => 'Leave Access Restricted',
                    'message' => 'You don\'t have permission to manage leave requests. Contact your administrator or team leader for assistance.',
                    'image' => 'assets/img/error/leave-403.gif',
                ],
                404 => [
                    'title' => 'Leave Request Not Found',
                    'message' => 'The leave request you\'re looking for doesn\'t exist or has been removed.',
                    'image' => 'assets/img/error/leave-404.gif',
                ],
                429 => [
                    'title' => 'Leave Request Limit Reached',
                    'message' => 'You\'ve reached the limit for leave requests. Please wait a moment before submitting another request.',
                    'image' => 'assets/img/error/leave-429.gif',
                ],
                default => [],
            },
            default => [],
        };
    }

    /**
     * Get the error view template.
     */
    public static function getErrorView(): string
    {
        return 'errors.custom';
    }

    /**
     * Get the maximum number of trace entries to log.
     */
    public static function getMaxTraceEntries(): int
    {
        return 5;
    }
}
