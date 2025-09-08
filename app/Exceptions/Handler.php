<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Log;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\Access\AuthorizationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
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
        // $this->reportable(function (Throwable $e) {
        //     Log::channel('slack')->error("🚨 *Exception Occurred!* 🚨", [
        //         'message' => $e->getMessage(),
        //         'file'    => $e->getFile(),
        //         'line'    => $e->getLine(),
        //         'code'    => $e->getCode(),
        //         'trace'   => array_slice($e->getTrace(), 0, 3), // Include first 3 trace entries
        //     ]);

        //     return false; // Prevent Laravel from logging the error again
        // });
    }
    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Convert AuthorizationException (403) into HttpException
        if ($exception instanceof AuthorizationException) {
            $exception = new HttpException(403, $exception->getMessage(), $exception);
        }

        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();

            $errorData = match ($statusCode) {
                403 => [
                    'title' => 'Access Denied',
                    'message' => 'Sorry, you don`t have permission to access this page.',
                    'image' => 'assets/img/error/403.gif',
                ],
                404 => [
                    'title' => 'Page Not Found',
                    'message' => 'We`re sorry, the page you requested could not be found.<br />
                                Please go back to the homepage.',
                    'image' => 'assets/img/error/error.gif',
                ],
                500 => [
                    'title' => 'Server Error',
                    'message' => 'Something went wrong on our end.',
                    'image' => 'assets/img/error/500.png',
                ],
                default => [
                    'title' => 'Unexpected Error',
                    'message' => 'An error occurred. Please try again later.',
                    'image' => 'assets/img/error/default.png',
                ],
            };

            return response()->view('errors.custom', [
                'statusCode' => $statusCode,
                'title'     => $errorData['title'],
                'message'   => $errorData['message'],
                'image'     => $errorData['image'],
                'exception' => $exception,
            ], $statusCode);
        }

        return parent::render($request, $exception);
    }

}
