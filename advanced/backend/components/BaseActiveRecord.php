<?php

namespace backend\components;

use Yii;

class BaseActiveRecord extends \yii\db\ActiveRecord {
    
    public static function classNameWithoutNamespace(){
        $className = self::className();
        $className = substr(strrchr($className,'\\'), 1);
        return $className;
    }
    
}
