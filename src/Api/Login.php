<?php

namespace Xc\Auth\Api;

use Xc\Auth\Exp\XcAuthErrorCode;
use Xc\Auth\Exp\XcAuthException;
use Xc\Auth\Units\ApiAbstract;
use Xc\Auth\Units\Redis;
use Yii;

/**
 * Created by PhpStorm.
 * @Desc 登录类
 * @Author Smiler <smilerliu@sz2k.com>
 * @Date 2023/5/18
 */
class Login extends ApiAbstract
{
    protected function init()
    {

    }

    /**
     * 检测是否登录
     * @return bool
     */
    public function isLogin(): bool
    {
        $ident = $this->ts->uid . '-' . md5(Yii::$app->request->getUserIP() . Yii::$app->request->getUserAgent());
        $key = $this->getCrKey('user:login_status:%s:%s', [$this->ts->tid, $ident]);

        $value = self::redisGz()->get($key);

        $bool = !empty($value) ? true : false;

        if (!$bool) {
            throw new XcAuthException(XcAuthErrorCode::NO_LOGIN);
        }

        return $bool ?? false;
    }

    /**
     * 获取登录用户信息
     * @return array
     */
    public function getLoginUserinfo()
    {
        $key = $this->getCrKey('user:access_token:%s', [md5($this->token)]);

        $userinfo = self::redisGz()->get($key);

        return $userinfo ?? [];
    }

    /**
     * 获取token中的附加参数
     * @return array
     */
    public function getTokenParams()
    {
        return $this->ts->getParams();
    }

    /**
     * 获取登录用户id
     * @return mixed|null
     */
    public function getUserId()
    {
        return $this->ts->uid;
    }

    /**
     * 获取登录租户id
     * @return mixed|null
     */
    public function getTenantId()
    {
        return $this->ts->tid;
    }

    /**
     * 获取登录类型
     * @return int|mixed
     */
    public function getLoginType()
    {
        return $this->ts->loginType;
    }
}