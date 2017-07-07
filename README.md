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

##### 4.格式化响应

本示例进行了格式化输出如下图

![image](https://raw.githubusercontent.com/chengxuzhang/apilogin/master/login.png)

包括三个部分 status 状态 ， message 提示 ， data 数据

在控制器中配置属性

```
public $serializer = [
        'class' => 'backend\components\BaseSerializer',
        'collectionEnvelope' => 'datas',
        'linksEnvelope'=>NULL,//设置不要links
        'metaEnvelope'=>NULL,//设置不要 metaEnvelope 
    ];
```
重写了 yii\rest\Serializer 类 使backend\components\BaseSerializer继承yii\rest\Serializer

##### 5.扩展字段使用方式

如下图所示

![image](https://raw.githubusercontent.com/chengxuzhang/apilogin/master/expand.png)

参数 expand 可以调用关联表的信息

Document 模型中的代码

```
/**
     * 获取用户信息
     * @return [type] [description]
     */
    public function getUserinfo()
    {
        return $this->hasOne(User::className(),['id'=>'uid']);
    }

    public function extraFields(){
        return ['userinfo'];
    }
```

###### 6.字段过滤

Document模型中加入

```
public function fields(){
        $fields = parent::fields();
        // 删除一些包含敏感信息的字段
        unset($fields['category_id'], $fields['description'], $fields['root'], $fields['pid'], $fields['model_id'], $fields['link_id'], $fields['cover_id'], $fields['display'], $fields['view'], $fields['create_time'], $fields['update_time']);
        return $fields;
    }
```


