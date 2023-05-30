<?php

namespace Xc\Auth\Api\Perm;

use Xc\Auth\Units\ApiAbstract;

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
        $key = $this->getCrKey('permission:data:%s:%s', $this->ts->arrayRedisKeyTenantIdUserIdToken());

        $data = self::redis()->get($key);
        return $data;
    }
}