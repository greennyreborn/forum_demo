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
    const TOPIC_CREATE_ERROR = 50002;
    const POST_CREATE_ERROR = 50003;

    const TOPIC_NOT_EXIST = 60001;
    const POST_NOT_EXIST = 60002;
    const USER_NOT_EXIST = 60003;

    protected static $msg = [
        '40000' => '错误的请求地址或方法',
        '40003' => '请求参数错误',

        '50000' => '内部服务器错误',
        '50001' => '用户创建失败',
        '50002' => '主题创建失败',
        '50003' => '回复创建失败',

        '60001' => '主题不存在',
        '60002' => '回复不存在',
        '60003' => '用户不存在',
    ];

    public static function getMsg($code)
    {
        $strCode = (string)$code;
        return isset(self::$msg[$strCode]) ? self::$msg[$strCode] : '';
    }
}
