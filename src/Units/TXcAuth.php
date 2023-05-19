<?php

namespace Xc\Auth\Units;

use Xc\Auth\Api\Login;
use Xc\Auth\Api\Perm;
use Xc\Auth\Api\User;
use Xc\Auth\Exp\XcAuthErrorCode;
use Xc\Auth\Exp\XcAuthException;

/**
 * Created by PhpStorm.
 * @Desc TXcAuth
 * @Author Smiler <smilerliu@sz2k.com>
 * @Date 2023/5/18
 * @method static Login login() 登录
 * @method static User user() 用户信息
 * @method static Perm perm() 权限
 */
trait TXcAuth
{
    protected static $objects = [];

    final public static function __callStatic($name, $arguments)
    {
        $class = '\Xc\Auth\Api\\' . ucfirst($name);
        try {
            $key = md5($class . json_encode($arguments));
            if (empty(self::$objects[$key])) {
                self::$objects[$key] = new $class($arguments);
            }

            return self::$objects[$key];
        } catch (\Exception $e) {
            throw new XcAuthException(XcAuthErrorCode::ERROR_VERIFY_TIP, "未定义类($class)");
        }
    }
}