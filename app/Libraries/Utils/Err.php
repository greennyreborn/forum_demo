<?php
/**
 * Created by IntelliJ IDEA.
 * User: michael
 * Date: 28/10/2016
 * Time: 5:21 PM
 */
namespace App\Libraries\Utils;

class Err
{
    const REQUEST_ERROR = 40000;
    const REQUEST_PARAMS_ERROR = 40003;

    const SERVER_INTERNAL_ERROR = 50000;
    const USER_CREATE_ERROR = 50001;

    protected static $msg = [
        '40000' => '错误的请求地址或方法',
        '40003' => '请求参数错误',
        '50000' => '内部服务器错误',
        '50001' => '用户创建失败',
    ];

    public static function getMsg($code)
    {
        $strCode = (string)$code;
        return isset(self::$msg[$strCode]) ? self::$msg[$strCode] : '';
    }
}
