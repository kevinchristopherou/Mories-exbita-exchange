<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Exceptions\MissingScopeException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \League\OAuth2\Server\Exception\OAuthServerException::class,
        \Denpa\Bitcoin\Exceptions\ClientException::class,
        //\InvalidArgumentException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($request->is('api/*')) {
            if ($e instanceof AuthenticationException) {
                $message = $e->getMessage();
                $code = 401;
            } elseif ($e instanceof MethodNotAllowedHttpException) {
                $code = 405;
                $message = __('Method Not Allowed');
            } elseif ($e instanceof MissingScopeException) {
                $code = 405;
                $message = __('Method Not Allowed');
            } //Don't touch laravel's default validation exception response format. It breaks vform error rendering on vue.
            elseif ($e instanceof ServiceUnavailableHttpException) {
                $code = 503;
                $message = __('Service Unavailable');
            } else {
                return parent::render($request, $e);
            }

            return response()->json([
                    'error' => [
                        'code' => $code,
                        'message' => $message, ],
                ]
            )->setStatusCode($code);
        }

        return parent::render($request, $e);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $response = parent::convertValidationExceptionToResponse($e, $request);
        if ($e instanceof ValidationException && $request->expectsJson()) {
            $original = $response->getOriginalContent();
            $original['message'] = 'validation_failed';
            $response->setContent(json_encode($original));
        }

        return $response;
    }
}
