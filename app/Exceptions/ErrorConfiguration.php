<?php

namespace App\Exceptions;

class ErrorConfiguration
{
    /**
     * Error configurations for different HTTP status codes.
     */
    public static function getErrorData(int $statusCode): array
    {
        return match ($statusCode) {
            401 => [
                'title' => 'Access Denied',
                'message' => 'Sorry, you don\'t have permission to access this page.',
                'image' => 'assets/img/error/401.gif',
            ],
            403 => [
                'title' => 'Forbidden',
                'message' => 'You do not have permission to access this resource.',
                'image' => 'assets/img/error/403.gif',
            ],
            404 => [
                'title' => 'Page Not Found',
                'message' => 'We\'re sorry, the page you requested could not be found.<br />
                            Please go back to the homepage.',
                'image' => 'assets/img/error/404.gif',
            ],
            419 => [
                'title' => 'Page Expired',
                'message' => 'Page expired, please refresh and try again.',
                'image' => 'assets/img/error/419.gif',
            ],
            500 => [
                'title' => 'Server Error',
                'message' => 'Something went wrong on our end.',
                'image' => 'assets/img/error/500.gif',
            ],
            default => [
                'title' => 'Unexpected Error',
                'message' => 'An error occurred. Please try again later.',
                'image' => 'assets/img/error/default.gif',
            ],
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
