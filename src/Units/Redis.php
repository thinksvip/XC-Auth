<?php

namespace Xc\Auth\Units;

/**
 * Created by PhpStorm.
 * @Desc Redis
 * @Author Smiler <smilerliu@sz2k.com>
 * @Date 2023/5/18
 */
class Redis extends Instance
{
    /**
     * @var mixed|object|null|\yii\redis\Connection
     */
    protected $redis;

    /**
     * @return mixed|object|null|\yii\redis\Connection
     */
    protected function init()
    {
        $this->redis = \Yii::$app->redis;
    }

    /**
     * 是否数据加密，默认 true
     * @var bool
     */
    protected $isEncryption = true;

    protected $isGz = false;

    /**
     * @param bool $isEncryption
     * @return Redis
     */
    public function setIsEncryption(bool $isEncryption): Redis
    {
        $this->isEncryption = $isEncryption;
        return $this;
    }

    /**
     * 设置是否压缩数据
     * @param bool $isGz
     * @return Redis
     */
    public function setIsGz(bool $isGz): Redis
    {
        $this->isGz = $isGz;
        return $this;
    }

    /**
     * 获取key-value
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        $value = $this->redis->get($key);
        return $this->decodeValue($value);
    }

    /**
     * 设置key-value
     * @param string $key
     * @param $value
     * @param int $expire
     * @return bool
     */
    public function set(string $key, $value, $expire = 0)
    {
        $value = $this->encodeValue($value);
        $r[] = $this->redis->set($key, $value);
        if ($expire) {
            $r[] = $this->redis->expire($key, $expire);
        }
        return $this->checkBack($r);
    }

    /**
     * 设置key-value(不存在即设置)
     * @param string $key
     * @param $value
     * @param int $expire
     * @return bool
     */
    public function setnx(string $key, $value, $expire = 0)
    {
        $value = $this->encodeValue($value);
        $r[] = $this->redis->setnx($key, $value);
        if ($expire) {
            $r[] = $this->redis->expire($key, $expire);
        }
        return $this->checkBack($r);
    }

    /**
     * 设置key-value(覆盖数据)
     * @param string $key
     * @param $value
     * @param int $expire
     * @return bool
     */
    public function setex(string $key, $value, $expire = 0)
    {
        $value = $this->encodeValue($value);
        $r[] = $this->redis->setex($key, $expire, $value);
        return $this->checkBack($r);
    }

    /**
     * 设置hash key-value
     * @param string $hKey
     * @param $key
     * @param $value
     * @param int $expire
     * @return bool
     */
    public function hset(string $hKey, $key, $value, $expire = 0)
    {
        $value = $this->encodeValue($value);
        $r[] = $this->redis->hset($hKey, $key, $value);
        if ($expire) {
            $r[] = $this->redis->expire($hKey, $expire);
        }
        return $this->checkBack($r);
    }

    /**
     * 获取hash key-value
     * @param string $hKey
     * @param $key
     * @return mixed
     */
    public function hget(string $hKey, $key)
    {
        $value = $this->redis->hget($hKey, $key);
        return $this->decodeValue($value);
    }

    /**
     * 设置hash key-value(不存在即设置)
     * @param string $hKey
     * @param $key
     * @param $value
     * @param $expire
     * @return bool
     */
    public function hsetnx(string $hKey, $key, $value, $expire = 0)
    {
        $value = $this->encodeValue($value);
        $r[] = $this->redis->hsetnx($hKey, $key, $value);
        if ($expire) {
            $r[] = $this->redis->expire($hKey, $expire);
        }
        return self::checkBack($r);
    }

    /**
     * 获取all hash key-value
     * @param $hKey
     * @param bool $is_encryption 是否加密
     * @return mixed
     */
    public function hgetall($hKey)
    {
        $list = $this->redis->hgetall($hKey);
        if (empty($list)) {
            return [];
        }

        $data = [];
        foreach ($list as $k => $item) {
            if ($k % 2 != 0) {
                $data[] = $item;
            }
        }
        array_walk($data, function ($value) {
            return $this->decodeValue($value);
        });

        return $data;
    }

    /**
     * 删除 key
     * @param string $key
     * @return mixed
     */
    public function del(string $key)
    {
        return $this->redis->del($key);
    }

    /**
     * 删除 hash key
     * @param string $hKey
     * @param ...$key
     * @return mixed
     */
    public function hdel(string $hKey, ...$key)
    {
        return $this->redis->hdel($hKey, ...$key);
    }


    /**
     * 加密value
     * @param $value
     * @return false|mixed|string
     */
    private function encodeValue($value)
    {
        if ($this->isEncryption) {
            $value = json_encode($value);
            $this->isGz && $value = gzcompress($value);
        }
        return $value;
    }

    /**
     * 解密value
     * @param $value
     * @return mixed
     */
    private function decodeValue($value)
    {
        if ($this->isEncryption && $value) {
            $this->isGz && $value = gzuncompress($value);
            $value = json_decode($value, true);
        }
        return $value;
    }

    /**
     * @desc 返回结果
     * @param $rs
     * @return bool
     * @author 1
     * @version v2.1
     * @date: 2020/11/03
     */
    private function checkBack($rs)
    {
        if ($rs) {
            foreach ($rs as $va) {
                if (!$va) {
                    return false;
                }
            }
            return true;
        }
        return true;
    }
}