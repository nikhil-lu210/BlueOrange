<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\Access\AuthorizationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     */
    protected $levels = [];

    /**
     * A list of the exception types that are not reported.
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            ExceptionLogger::logIfNeeded($e);
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception): Response
    {
        $exception = $this->normalizeException($exception);

        if ($exception instanceof HttpExceptionInterface) {
            return $this->renderHttpException($exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Render HTTP exceptions with custom error pages.
     */
    protected function renderHttpException(HttpExceptionInterface $exception)
    {
        $statusCode = $exception->getStatusCode();
        $errorData = ErrorConfiguration::getErrorData($statusCode);

        return response()->view(ErrorConfiguration::getErrorView(), [
            'statusCode' => $statusCode,
            'title' => $errorData['title'],
            'message' => $errorData['message'],
            'image' => $errorData['image'],
            'exception' => $exception,
        ], $statusCode);
    }

    /**
     * Normalize exceptions for consistent handling.
     */
    private function normalizeException(Throwable $exception): Throwable
    {
        if ($exception instanceof AuthorizationException) {
            return new HttpException(403, $exception->getMessage(), $exception);
        }

        return $exception;
    }
}
