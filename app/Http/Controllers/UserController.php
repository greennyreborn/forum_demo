<?php
/**
 * Created by IntelliJ IDEA.
 * User: michael
 * Date: 29/10/2016
 * Time: 1:02 PM
 */
namespace App\Http\Controllers;

use App\Libraries\Utils\Err;
use App\Models\User;
use Validator;

class UserController extends Controller
{
    public function create()
    {
        $requestParams = $this->request->all();
        $v = Validator::make($requestParams, [
            'username' => 'required|string',
            'password' => 'required|string|min:8|max:20',
        ]);

        if ($v->fails()) {
            fAbort(403, Err::REQUEST_PARAMS_ERROR);
        }

        $params = [
            'username' => $requestParams['username'],
            'password' => $requestParams['password'],
            'avatar' => $requestParams['avatar'] ?: '',
            'ip' => $this->request->ip(),
        ];

        $userDao = new User();
        $res = $userDao->createUser($params);

        return [
            'uid' => $res['uid'],
        ];
    }
}