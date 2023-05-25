# xc-auth授权

## 调用示例

```php
// 检测是否登陆
\Xc\Auth\XcAuth::login()->isLogin();
// 获取所有用户
\Xc\Auth\XcAuth::user()->getAllUsers();
// 获取数据权限
\Xc\Auth\XcAuth::perm()->data->get();
```

## jwt配置文件参考

```php
return [
    'jwt' => [
        'key' => '',// 密钥
        'alg' => '',// 加密方式
        'iss' => '',// 签发者，可选
        'aud' => '',// 接收方，可选
        'leeway' => 'int类型(单位:s)',// 留余时间
        'type' => 'int类型',// 类型,固定值
    ],
];
```