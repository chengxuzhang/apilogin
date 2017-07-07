## yii2 restful web服务

##### 1.授权验证基础配置

基础配置教程地址 [http://www.codegong.com/document/api-token.html](http://www.codegong.com/document/api-token.html)

##### 2.版本化

教程地址 [http://www.codegong.com/document/yii2-restful-web-api.html](http://www.codegong.com/document/yii2-restful-web-api.html)

##### 3.速率限制

要启用速率限制, user identity class 应该实现 yii\filters\RateLimitInterface

```
use yii\filters\RateLimitInterface;

class User extends ActiveRecord implements IdentityInterface, RateLimitInterface
```

控制器中进行速率检查

```
public function behaviors(){
	    $behaviors = parent::behaviors();
        
        $behaviors['rateLimiter'] = [
            'class' => RateLimiter::className(),
            'enableRateLimitHeaders' => true,
        ];

        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
        ];

        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        return $behaviors;
	}
```

backend/models/User.php 中加入如下代码

```
// 返回在单位时间内允许的请求的最大数目，例如，[10, 60] 表示在60秒内最多请求10次。
    public function getRateLimit($request, $action)
    {
        return [5, 10];
    }

    // 返回剩余的允许的请求数。
    public function loadAllowance($request, $action)
    {
        return [$this->allowance, $this->allowance_updated_at];
    }

    // 保存请求时的UNIX时间戳。
    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $this->allowance = $allowance;
        $this->allowance_updated_at = $timestamp;
        $this->save();
    }
```

