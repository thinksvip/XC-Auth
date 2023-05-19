<?php

namespace Xc\Auth\Api;

use Xc\Auth\Api\Perm\Data;
use Xc\Auth\Exp\XcAuthErrorCode;
use Xc\Auth\Exp\XcAuthException;
use Xc\Auth\Units\ApiAbstract;

/**
 * Created by PhpStorm.
 * @Desc 权限类
 * @Author Smiler <smilerliu@sz2k.com>
 * @Date 2023/5/18
 * @property Data $data 数据权限
 */
class Perm extends ApiAbstract
{
    protected function init()
    {
        // TODO: Implement init() method.
    }

    protected static $perms = [];

    final public function __get($name)
    {
        $class = '\Xc\Auth\Api\Perm\\' . ucfirst($name);
        try {
            $key = md5($class);
            if (empty(self::$perms[$key])) {
                self::$perms[$key] = new $class();
            }

            return self::$perms[$key];
        } catch (\Exception $e) {
            throw new XcAuthException(XcAuthErrorCode::ERROR_VERIFY_TIP, "未定义类($class)");
        }
    }

}