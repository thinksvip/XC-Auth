
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