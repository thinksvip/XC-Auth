<?php

namespace Xc\Auth\Units;

/**
 * Created by PhpStorm.
 * @Desc Instance
 * @Author Smiler <smilerliu@sz2k.com>
 * @Date 2023/5/18
 */
abstract class Instance
{
    /**
     * @var static
     */
    private static $instance;

    abstract protected function init();


    /**
     * 获取实例唯一的入口
     * @return static
     */
    public static function getInstance()
    {
        $key = md5(static::class);
        if (empty(self::$instance[$key])) {
            static::$instance[$key] = new static();
        }

        return static::$instance[$key];
    }


    private function __construct()
    {
        $this->init();
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
}