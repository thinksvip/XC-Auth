<?php

namespace Xc\Auth\Units\Struct;

/**
 * Created by PhpStorm.
 * @Desc UserStruct
 * @Author Smiler <smilerliu@sz2k.com>
 * @Date 2023/5/18
 */
class UserStruct
{
    public $uid;
    public $cnName;
    public $enName;
    public $showName;

    public function __construct(array $user)
    {
        $this->uid = $user['id'] ?? $user['user_id'];
        $this->cnName = $user['realname'] ?? $user['cn_name'] ?? '';
        $this->enName = $user['en_name'] ?? '';
        $this->showName = $user['show_name'] ?? (sprintf('%s(%s)', $this->cnName, $this->enName));
    }
}