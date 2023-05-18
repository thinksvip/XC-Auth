<?php

namespace Xc\Auth\Units;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Xc\Auth\Exp\XcAuthException;
use Xc\Auth\Units\Instance;

/**
 * Created by PhpStorm.
 * @Desc JwtAuth
 * @Author Smiler <smilerliu@sz2k.com>
 * @Date 2023/5/18
 */
class JwtAuth extends Instance
{
    /**
     * @var array 配置
     */
    protected array $config = [];
    protected $exp = 7200;// 有效期
    private string $_key = '';// 密钥
    private string $_alg = '';// 加密方式
    private string $_iss = '';// 签发者，可选
    private string $_aud = '';// 接收方，可选
    private int $_leeway = 30;// 留余时间
    private int $_type = 1;// 类型
    private string $_token;// token数据
    private object $_decode;// 解密后的参数

    /**
     * 初始化
     * @return void
     */
    protected function init()
    {
        $this->config = \Yii::$app->params['jwt'] ?? [];
        $this->_key = $this->config['key'] ?? '';
        $this->_alg = $this->config['alg'] ?? 'HS256';
        $this->_iss = $this->config['iss'] ?? '';
        $this->_aud = $this->config['aud'] ?? '';
        $this->_leeway = $this->config['leeway'] ?? 30;
        $this->_type = $this->config['type'] ?? 1;
    }

    /**
     * 设置有效期（单位：s）
     * @param int $exp
     * @return $this
     */
    public function setExp(int $exp)
    {
        $this->exp = $exp ?: 7200;
        return $this;
    }

    /**
     * 设置token类型
     * @param int $type
     * @return $this
     */
    public function setType(int $type)
    {
        $this->_type = $type;
        return $this;
    }

    /**
     * 生成token
     * @param array $params 附加参数
     * @return $this
     */
    public function encode(array $params = [])
    {
        $payload = $this->payload($params);
        $token = JWT::encode($payload, $this->_key, $this->_alg);
        $this->_decode = (object)$payload;

        $this->_token = $token;

        return $this;
    }

    /**
     * @Desc 解析token
     * @Author Smiler <smilerliu@sz2k.com>
     * @Date 2023/4/7
     * @param string $token
     * @return $this
     * @throws \Exception
     */
    public function decode(string $token)
    {
        JWT::$leeway = $this->_leeway;
        try {
            $decode = JWT::decode($token, new Key($this->_key, $this->_alg));
        } catch (SignatureInvalidException $e) {
            throw new XcAuthException('token验证失败');
        } catch (ExpiredException $e) {
            throw new XcAuthException('token已过期');
        } catch (\Exception $e) {
            throw new XcAuthException('token无效');
        }

        if ($decode->type != $this->_type) {
            throw new XcAuthException('token无效');
        }

        $this->_decode = $decode;

        return $this;
    }

    /**
     * 获取token数据
     * @return array
     */
    public function getTokenData()
    {
        return [
            'token' => $this->_token,
            'expires_in' => $this->_decode->exp,
        ];
    }

    /**
     * 获取附加参数
     * @param object $payload 解密数据
     * @return array
     */
    public function getParams()
    {
        $data = $this->_decode->data;
        return (array)$data;
    }

    /**
     * 组装请求参数
     * @param array $param
     * @return array
     */
    private function payload(array $param = [])
    {
        $time = time();
        return [
            'iss' => $this->_iss,// 签发者，可选
            'aud' => $this->_aud,// 接收方，可选
            'iat' => $time,// 签发时间
            'nbf' => $time,// 某一时间后才可以访问
            'exp' => $time + $this->exp,// 过期时间
            'type' => $this->_type,// 类型
            'data' => $param,// 参数
        ];
    }
}