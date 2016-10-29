<?php

namespace App\Exceptions;

use App\Libraries\Utils\Err;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // HttpException
        if (method_exists($exception, 'getStatusCode')) {
            return $this->renderJson($request, $exception);
        }

        if (!env('APP_DEBUG')) {
            $headers = $this->getAllowOriginHeaders($request);
            $headers = $headers ?: [];
            $result['error_code'] = Err::SERVER_INTERNAL_ERROR;
            $result['error_msg'] = Err::getMsg(Err::SERVER_INTERNAL_ERROR);
            return response($result, 500, $headers);
        }

        return parent::render($request, $exception);
    }

    protected function getAllowOriginHeaders(Request $request)
    {
        $origin = $request->header('origin');
        $headers = get_cross_domain_headers($origin);
        return $headers;
    }

    protected function renderJson($request, Exception $exception)
    {
        $status_code = intval($exception->getStatusCode());
        $result = [
            'error_code' => $exception->getCode(),
            'error_msg' => $exception->getMessage() ?: Err::getMsg($exception->getCode()),
        ];

        if ($status_code == 404 || $status_code == 405) {
            $result['error_code'] = Err::REQUEST_ERROR;
            $result['error_msg'] = Err::getMsg(Err::REQUEST_ERROR);
        }

        $headers = $this->getAllowOriginHeaders($request);
        $headers = $headers ?: [];

        return response($result, $status_code, $headers);
    }

}
