<?php 

namespace backend\controllers;

use Yii;
use yii\filters\auth\QueryParamAuth;
use backend\models\LoginForm;
use yii\filters\AccessControl;

class UserController extends \backend\components\BaseActiveController
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

    public function actionUserInfo(){
        if (!Yii::$app->user->isGuest) {
            return 'yes';
        }

        return 'no';
    }

    /**
     * 登录既是授权
     * @return [type] [description]
     */
    public function actionLogin(){
        $model = new LoginForm();
        $postData = Yii::$app->request->post();
        if ($model->load(['LoginForm'=>$postData]) && $model->login()) {
        	$token = md5(Yii::$app->security->generateRandomString());
            Yii::$app->cache->set($token, $postData['username'], 3600); // 设置token值为username
            return ['status'=>200,'message'=>'ok','data'=>['token'=>$token]];
        }

        return 'login no';
    }
}