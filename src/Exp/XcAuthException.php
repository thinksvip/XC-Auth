<?php

namespace Xc\Auth\Exp;

/**
 * Created by PhpStorm.
 * @Desc XcAuthException
 * @Author Smiler <smilerliu@sz2k.com>
 * @Date 2023/5/18
 */
class XcAuthException extends \Exception
{
    /**
     * @param array $codeMessage 错误码数组
     * @param string|array $tip 补充说明
     * @param \Throwable|null $previous
     */
    public function __construct(array $codeMessage, $tip = '', \Throwable $previous = null)
    {
        $code = $codeMessage[0] ?? 100;
        $message = $codeMessage[1] ?? '未知错误';

        $msg = is_array($tip) ? vsprintf('(%s:%s)', $tip) : $tip;
        $message = sprintf($message, $msg);
        parent::__construct($message, $code, $previous);
    }
}