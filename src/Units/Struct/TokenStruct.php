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

    public $token;

    /**
     * 登录类型
     * @var int|mixed
     */
    public $loginType;

    protected array $params = [];

    public function __construct(array $params, string $token = '')
    {
        $this->tid = $params['tenant_id'] ?? null;
        $this->uid = $params['user_id'] ?? null;
        $this->loginType = $params['login_type'] ?? 1;
        $this->token = $token;

        if (empty($this->tid) || empty($this->uid)) {
            throw new XcAuthException(XcAuthErrorCode::NO_LOGIN, XcAuthErrorCode::OAUTH_TOKEN_PARSE_ERROR);
        }

        $this->params = $params;
    }

    /**
     * 获取token中的附加参数
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * 获取用户id Token key
     * @param bool $incToken
     * @return string
     */
    protected function getUserIdTokenKey(bool $incToken = false)
    {
        if (!$incToken) {
            return sprintf('%s', $this->uid);
        }
        return sprintf('%s-%s', $this->uid, md5($this->token));
    }

    /**
     * 获取租户id和用户id
     * @param bool $incToken
     * @return array
     */
    public function arrayRedisKeyTenantIdUserIdToken(bool $incToken = false)
    {
        return [$this->tid, $this->getUserIdTokenKey($incToken)];
    }
}