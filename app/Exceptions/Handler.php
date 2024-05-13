<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if (str_starts_with($request->getRequestUri(), '/api/')) {
            if ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
                return response()->json([
                    'message' => __('Not Found'),
                    'error' => $e->getMessage()
                ], Response::HTTP_NOT_FOUND);
            } elseif ($e instanceof HttpResponseException) {
                return response()->json([
                    'message' => __('Internal Server Error'),
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            } elseif ($e instanceof MethodNotAllowedHttpException || $e instanceof MethodNotAllowedException) {
                return response()->json([
                    'message' => __('Method not allowed'),
                    'error' => $e->getMessage()
                ], Response::HTTP_METHOD_NOT_ALLOWED);
            } elseif ($e instanceof AuthorizationException) {
                return response()->json([
                    'message' => __('Forbidden'),
                    'error' => $e->getMessage()
                ], Response::HTTP_FORBIDDEN);
            } elseif ($e instanceof UnauthorizedException) {
                return response()->json([
                    'message' => __('Unauthorized'),
                    'error' => $e->getMessage()
                ], Response::HTTP_UNAUTHORIZED);
            } elseif ($e instanceof ValidationException) {
                $errors = $e->validator->getMessageBag()->toArray();
                $errorArray = [];

                foreach ($errors as $key => $value) {
                    $errorArray[$key] = Arr::first($value);
                }

                return response()->json([
                    'message' => __('Bad Request'),
                    'error' => $errorArray
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                return response()->json([
                    'message' => __('Internal Server Error'),
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return parent::render($request, $e);
    }
}
