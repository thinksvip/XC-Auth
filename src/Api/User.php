<?php

namespace Xc\Auth\Api;

use Xc\Auth\Units\ApiAbstract;
use Xc\Auth\Units\Redis;

/**
 * Created by PhpStorm.
 * @Desc 用户信息类
 * @Author Smiler <smilerliu@sz2k.com>
 * @Date 2023/5/18
 */
class User extends ApiAbstract
{
    protected function init()
    {
        // TODO: Implement init() method.
    }

    /**
     * 获取所有用户信息
     * @return array|mixed
     */
    public function getAllUsers()
    {
        $key = $this->getCrKey(__FUNCTION__);
        $allUsers = Redis::getInstance()->hgetall($key);
        return $allUsers;
    }

    /**
     * 获取单个用户信息
     * @param $uid
     * @return mixed
     */
    public function getSingleUser($uid)
    {
        $key = $this->getCrKey('getAllUsers');
        $user = Redis::getInstance()->hget($key, $uid);
        return $user;
    }
}