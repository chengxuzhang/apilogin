<?php
namespace backend\components;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\caching\Cache;

class BaseActiveController extends ActiveController{

    public $serializer = [
        'class' => 'backend\components\BaseSerializer',
        'collectionEnvelope' => 'datas',
        'linksEnvelope'=>NULL,//设置不要links
        'metaEnvelope'=>NULL,//设置不要 metaEnvelope 
    ];

    public function setResponseInfo($status,$message)
    {
         $this->serializer['responseStatus']=$status;
         $this->serializer['responseMessage']=$message;
    }
}
