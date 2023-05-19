<?php

namespace Xc\Auth\Units\Struct;

use Xc\Auth\Exp\XcAuthErrorCode;
use Xc\Auth\Exp\XcAuthException;

/**
 * Created by PhpStorm.
 * @Desc TokenStruct
 * @Author Smiler <smilerliu@sz2k.com>
 * @Date 2023/5/18
 */
class TokenStruct
{
    /**
     * 租户id
     * @var mixed|null
     */
    public $tid;
    /**
     * 用户id
     * @var mixed|null
     */
    public $uid;

    public function __construct(array $params)
    {
        $this->tid = $params['tenant_id'] ?? null;
        $this->uid = $params['user_id'] ?? null;

        if (empty($this->tid) || empty($this->uid)) {
            throw new XcAuthException(XcAuthErrorCode::NO_LOGIN, XcAuthErrorCode::OAUTH_TOKEN_PARSE_ERROR);
        }
    }
}