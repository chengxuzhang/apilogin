<?php 

namespace backend\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;
use backend\models\LoginForm;
use yii\filters\AccessControl;

class UserController extends ActiveController
{
    public $modelClass = 'backend\models\User';

    public function behaviors() {
        $rules  = [
            'authenticator' => [
                'class' => QueryParamAuth::className(),
                'except'=> ['login'],
            ],
        ];
        $rules = \yii\helpers\ArrayHelper::merge(parent::behaviors(),$rules);
        return $rules;
    }

    /**
     * 登录既是授权
     * @return [type] [description]
     */
    public function actionLogin(){
    	//  如果应用有状态的情况下会起作用例如使用postman第一次登录成功后，再次登录就会执行这边，但是前提是配置中要做如下配置
    	//  'user' => [
        //     'identityClass' => 'backend\models\User',
        //     'enableAutoLogin' => true,
        //     'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        // ],
        // 在有状态的情况下，它会通过api传一个cookie信息，name值为_identity-backend。后台获取到这个cookie就认为处于登录状态所以就访问这里了。
    	if (!Yii::$app->user->isGuest) {
            return 'yes';
        }

        $model = new LoginForm();
        $postData = Yii::$app->request->post();
        if ($model->load(['LoginForm'=>$postData]) && $model->login()) {
        	$token = md5(Yii::$app->security->generateRandomString());
            Yii::$app->cache->set($token, $postData['username'], 60); // 设置token值为username
            return ['token'=>$token];
        }

        return 'login no';
    }
}