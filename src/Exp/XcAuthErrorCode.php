<?php

namespace Xc\Auth\Exp;

/**
 * Created by PhpStorm.
 * @Desc XcAuthErrorCode
 * @Author Smiler <smilerliu@sz2k.com>
 * @Date 2023/5/19
 */
class XcAuthErrorCode
{
    // 公共
    const ERROR_VERIFY_TIP = [100, '提示: '];// 提示

    // 登录 10xxx
    const NO_LOGIN = [10000, '未登录%s'];

    // 验证 40xxx
    const OAUTH_SIGNATURE_INVALID = [40100, 'token验证失败'];
    const OAUTH_TOKEN_EXPIRED = [40101, 'token已过期'];
    const OAUTH_TOKEN_NULL = [40102, 'token无效'];
    const OAUTH_TOKEN_TYPE_ERROR = [40103, 'token类型无效'];
    const OAUTH_TOKEN_PARSE_ERROR = [40104, 'token解析失败'];
}