<?php

namespace backend\controllers;

use Yii;
use yii\web\Response;
use yii\web\HttpException;
use yii\filters\RateLimiter;
use yii\filters\auth\QueryParamAuth;

class DocumentController extends \backend\components\BaseActiveController
{
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

    public $modelClass = 'backend\models\Document';
}
