<?php

namespace App\Models;

use App\Libraries\Utils\Err;
use Illuminate\Support\Collection;

class User extends BaseModel
{
    protected $table = 'user';

    protected $fillable = [
        'name', 'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $dateFormat = 'U';

    public function createUser($params)
    {
        $this->username = $params['username'];
        $this->password = $this->generatePassword($params['password']);
        $this->avatar = isset($params['avatar']) ? $params['avatar'] : '';
        $this->uid = $this->generateUid($params['ip']);
        if (checkModelResult($this->getUserByUsername($this->username))) {
            fAbort(403, Err::USER_CREATE_ERROR);
        }

        $id = 0;
        try {
            if ($this->save()) {
                $id = $this->id;
            }
        } catch (\Exception $exception) {
            app('log')->warning($exception->getMessage());
            fAbort(403, Err::USER_CREATE_ERROR);
        }

        $result = [
            'uid' => $this->uid,
            'id' => $id
        ];

        return $result;
    }

    /**
     * @param $uid
     * @return Collection
     */
    public function getUserByUid($uid)
    {
        return $this->remember(600)->getUserByUidFromDB($uid);
    }

    /**
     * @param $uid
     * @return Collection
     */
    public function getUserByUidFromDB($uid)
    {
        return $this->where('uid', $uid)->first();
    }

    /**
     * @param $username
     * @return Collection
     */
    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    protected function generatePassword($rawPassword)
    {
        $salt = config('app.salt');
        $body = $rawPassword . $salt;
        return md5($body);
    }

    protected function generateUid($ip)
    {
        $long = ip2long($ip);
        $now_time = time();
        $id = $long + $now_time;
        $sec = explode(" ", microtime());
        $id = $id + intval($sec[0] * 1000000);
        $id = $id + mt_rand(1000, 999999999);
        
        return $id;
    }

}
