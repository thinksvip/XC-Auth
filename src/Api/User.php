<?php

namespace Xc\Auth\Api;

use Xc\Auth\Units\ApiAbstract;

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

    }

    /**
     * 获取所有用户信息
     * @return array|mixed
     */
    public function getAllUsers()
    {
        $key = $this->getCrKey('h_users:%s', $this->ts->tid);
        $allUsers = self::redis()->hgetall($key);
        return $allUsers;
    }

    /**
     * 获取单个用户信息
     * @param $uid
     * @return mixed
     */
    public function getSingleUser($uid)
    {
        $key = $this->getCrKey('h_users:%s', $this->ts->tid);
        $user = self::redis()->hget($key, $uid);
        return $user;
    }
}