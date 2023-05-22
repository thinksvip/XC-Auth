<?php

namespace Xc\Auth\Api\Perm;

use Xc\Auth\Units\ApiAbstract;
use Xc\Auth\Units\Redis;

/**
 * Created by PhpStorm.
 * @Desc 数据权限
 * @Author Smiler <smilerliu@sz2k.com>
 * @Date 2023/5/18
 */
class Data extends ApiAbstract implements IPerm
{
    protected function init()
    {
        // TODO: Implement init() method.
    }

    /**
     * 获取数据
     * @return mixed
     */
    public function get()
    {
        $key = $this->getCrKey('permission:data:%s:%s', $this->ts->listTenantIdAndUserId());

        $data = self::redisGz()->get($key);
        return $data;
    }
}