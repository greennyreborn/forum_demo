<?php
/**
 * Created by IntelliJ IDEA.
 * User: michael
 * Date: 28/10/2016
 * Time: 5:38 PM
 */
namespace App;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Application extends \Illuminate\Foundation\Application
{
    public function __construct($basePath)
    {
        parent::__construct($basePath);
    }

    public function fAbort($statusCode, $code = 0, $message = '', array $headers = [])
    {
        if ($statusCode == 404) {
            throw new NotFoundHttpException($message);
        }

        throw new HttpException($statusCode, $message, null, $headers, $code);
    }
}