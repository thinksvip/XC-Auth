<?php

namespace Xc\Auth\Api;

use Xc\Auth\Exp\XcAuthErrorCode;
use Xc\Auth\Exp\XcAuthException;
use Xc\Auth\Units\ApiAbstract;
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
        $key = $this->getCrKey('user:login_status:%s:%s', $this->ts->arrayRedisKeyTenantIdUserIdToken(true));

        $value = self::redis()->get($key);

        $bool = !empty($value) ? true : false;

        if (!$bool) {
            throw new XcAuthException(XcAuthErrorCode::NO_LOGIN);
        }

        return $bool ?? false;
    }

    /**
     * 获取登录用户信息，包含基础信息、角色、部门、企业等 信息
     * @return array
     */
    public function getLoginUserinfo()
    {
        if (empty($this->loginUser)) {
            $key = $this->getCrKey('tenant:userinfo:%s:%s', $this->ts->arrayRedisKeyTenantIdUserIdToken());
            $this->loginUser = self::redis()->get($key);
        }

        return $this->loginUser ?? [];
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

    /**
     * 获取登录用户企业信息
     * @return array|mixed
     */
    public function getLoginTenantInfo()
    {
        $userinfo = $this->getLoginUserinfo();

        return $userinfo['tenant'] ?? [];
    }

    /**
     * 获取登录用户基础信息
     * @return array|mixed
     */
    public function getLoginUserBaseInfo()
    {
        $userinfo = $this->getLoginUserinfo();

        return $userinfo['userinfo'] ?? [];
    }

    /**
     * 获取登录用户角色列表
     * @return mixed
     */
    public function getLoginUserRoles()
    {
        $userinfo = $this->getLoginUserinfo();

        return $userinfo['role'] ?? [];
    }

    /**
     * 获取登录用户角色id
     * @return mixed
     */
    public function getLoginUserRoleIds()
    {
        $userinfo = $this->getLoginUserinfo();

        return $userinfo['role_ids'] ?? [];
    }

    /**
     * 获取登录用户部门列表
     * @return array|mixed
     */
    public function getLoginUserDepartment()
    {
        $userinfo = $this->getLoginUserinfo();

        return $userinfo['department'] ?? [];
    }
}