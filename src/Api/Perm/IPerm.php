<?php

namespace Xc\Auth\Api\Perm;

/**
 * Created by PhpStorm.
 * @Desc IPerm
 * @Author Smiler <smilerliu@sz2k.com>
 * @Date 2023/5/18
 */
interface IPerm
{
    /**
     * 设置key
     * @return string
     */
    public function setKey(): string;

    /**
     * 获取
     * @return mixed
     */
    public function get();
}