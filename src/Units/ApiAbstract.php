<?php

namespace Xc\Auth\Units;

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

    protected array $crKey;

    protected string $tokenType = 'Bearer';

    protected TokenStruct $ts;// token数据结构

    public function __construct()
    {
        $this->setToken();

        $params = JwtAuth::getInstance()->decode($this->token)->getParams();
        $this->ts = new TokenStruct($params);
        
        $this->setCrKey();
    }

    /**
     * 各功能初始化
     * @return mixed
     */
    abstract protected function init();

    /**
     * 设置key
     * @return void
     */
    private function setCrKey()
    {
        $this->crKey = \Yii::$app->params['redis_key'] ?? [];
    }

    /**
     * 获取指定key
     * @param string $key
     * @param array $params
     * @return string
     */
    protected function getCrKey(string $key, array $params = [])
    {
        $key = 'auth' . ucfirst($key);
        return vsprintf($this->crKey[$key] ?? '', $params);
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
                throw new XcAuthException('获取token失败');
            }

            $tokenTypeLength = strlen($this->tokenType);

            if (strcasecmp(substr($token, 0, $tokenTypeLength), $this->tokenType) !== 0) {
                throw new XcAuthException('token类型无效');
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
}