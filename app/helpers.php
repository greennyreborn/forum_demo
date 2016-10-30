<?php
/**
 * Created by IntelliJ IDEA.
 * User: michael
 * Date: 28/10/2016
 * Time: 5:31 PM
 */

if (!function_exists('fAbort')) {
    function fAbort($statusCode, $code, $message = '', array $headers = [])
    {
        return app()->fAbort($statusCode, $code, $message, $headers);
    }
}

if (!function_exists('get_cross_domain_headers')) {
    function get_cross_domain_headers($origin)
    {
        $whiteDomains = config('app.white_domains');

        $allow_headers = 'Origin, X-Requested-With, Content-Type, Accept, X-HTTP-Method-Override, Cookie, AccessToken';

        if ($origin) {
            $compo = parse_url($origin);
            if (isset($compo['host']) && in_array($compo['host'], $whiteDomains)) {
                $header = [
                    'Access-Control-Allow-Origin' => $origin,
                    'Access-Control-Allow-Credentials' => 'true',
                    'Access-Control-Allow-Headers' => $allow_headers,
                    'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, DELETE, PUT'
                ];
                return $header;
            } else {
                app('log')->warning('origin error ' . $origin);
            }
        }

        return [];
    }
}

/**
 * 用户检查Laravel 框架中Eloquent 返回的值是否为空
 */
if (!function_exists('checkModelResult')) {
    function checkModelResult($item)
    {
        if (!$item) {
            return false;
        } elseif ($item instanceof \Illuminate\Support\Collection) {
            return !$item->isEmpty();
        } else {
            return true;
        }
    }
}

