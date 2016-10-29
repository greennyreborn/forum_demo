<?php
/**
 * Created by IntelliJ IDEA.
 * User: michael
 * Date: 28/10/2016
 * Time: 5:27 PM
 */
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProcessResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $this->setAllowOrigin($request, $response);
        return $response;
    }

    protected function setAllowOrigin(Request $request, Response $response)
    {
        $origin = $request->header('origin');
        $headers = get_cross_domain_headers($origin);
        if ($headers) {
            foreach ($headers as $k => $v) {
                $response->header($k, $v);
            }
        }
    }

}