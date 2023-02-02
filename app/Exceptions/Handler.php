<?php

namespace App\Exceptions;

use App\Http\Responses\ErrorValidationResponse;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Exception|Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return ErrorValidationResponse::send($exception->getMessage());
        }

        if ($exception instanceof DomainException) {
            return new JsonResponse([
                'error' => [
                    'message' => $exception->getDescription(),
                    'code' => $exception->getCode()
                ]
            ], 500);
        }

        if ($exception instanceof NotFoundHttpException) {
            return new JsonResponse([
                'error' => [
                    'code' => 'not_found',
                    'message' => 'Страница не найдена',
                ]
            ], 404);
        }

        if (env('APP_DEBUG') !== true || (env('APP_ENV') !== 'local' && env('APP_ENV') !== 'testing') ) {
            return new JsonResponse([
                'error' => [
                    'message' => 'Неизвестная ошибка',
                    'code' => 'unknown_error'
                ]
            ], 500);
        } else {
            return new JsonResponse([
                'error' => [
                    'message' => $exception->getMessage(),
                    'code' => 'unknown_error'
                ]
            ], 500);
        }
    }

}
