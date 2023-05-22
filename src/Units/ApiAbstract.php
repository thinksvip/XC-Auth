<?php

namespace Xc\Auth\Units;

use Xc\Auth\Exp\XcAuthErrorCode;
use Xc\Auth\Exp\XcAuthException;
use Xc\Auth\Units\Struct\TokenStruct;

/**
 * Created by PhpStorm.
 * @Desc ApiAbstract
 * @Author Smiler <smilerliu@sz2k.com>
 * @Date 2023/5/18
 */
abstract class ApiAbstract
{
    protected string $token;

    protected string $tokenType = 'Bearer';

    protected TokenStruct $ts;// token数据结构

    public function __construct()
    {
        $this->setToken();

        $params = JwtAuth::getInstance()->decode($this->token)->getParams();
        $this->ts = new TokenStruct($params);
    }

    /**
     * 各功能初始化
     * @return mixed
     */
    abstract protected function init();

    /**
     * 获取指定key
     * @param string $key
     * @param array $params
     * @return string
     */
    protected function getCrKey(string $key, array $params = [])
    {
        return vsprintf('auth:' . $key, $params);
    }

    /**
     * 设置token
     * @return false|string
     * @throws XcAuthException
     */
    protected function setToken(string $token = '')
    {
        if (empty($token)) {
            $header = \Yii::$app->request->getHeaders();
            $token = $header['Authorization'] ?? '';
            if (empty($token) || $token === null) {
                throw new XcAuthException(XcAuthErrorCode::NO_LOGIN);
            }

            $tokenTypeLength = strlen($this->tokenType);

            if (strcasecmp(substr($token, 0, $tokenTypeLength), $this->tokenType) !== 0) {
                throw new XcAuthException(XcAuthErrorCode::OAUTH_TOKEN_TYPE_ERROR);
            }
            $this->token = substr($token, $tokenTypeLength + 1);
        } else {
            $this->token = $token;
        }
    }

    /**
     * 获取token
     * @return false|string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * 返回redis对象
     * @return Redis
     */
    protected static function redis()
    {
        return Redis::getInstance()->setIsEncryption(true)->setIsGz(false);
    }

    /**
     * 返回redis对象-json数据
     * @return Redis
     */
    protected static function redisEncry()
    {
        return self::redis()->setIsEncryption(true)->setIsGz(false);
    }

    /**
     * 返回redis对象-压缩数据
     * @return Redis
     */
    protected static function redisGz()
    {
        return self::redisEncry()->setIsEncryption(true)->setIsGz(true);
    }
}