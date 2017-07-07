<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class Document extends \backend\components\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'category_id', 'root', 'pid', 'model_id', 'type', 'position', 'link_id', 'cover_id', 'display', 'deadline', 'attach', 'view', 'comment', 'extend', 'level', 'create_time', 'update_time', 'status'], 'integer'],
            [['category_id'], 'required'],
            [['name'], 'string', 'max' => 40],
            [['title'], 'string', 'max' => 80],
            [['description'], 'string', 'max' => 140],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'name' => 'Name',
            'title' => 'Title',
            'category_id' => 'Category ID',
            'description' => 'Description',
            'root' => 'Root',
            'pid' => 'Pid',
            'model_id' => 'Model ID',
            'type' => 'Type',
            'position' => 'Position',
            'link_id' => 'Link ID',
            'cover_id' => 'Cover ID',
            'display' => 'Display',
            'deadline' => 'Deadline',
            'attach' => 'Attach',
            'view' => 'View',
            'comment' => 'Comment',
            'extend' => 'Extend',
            'level' => 'Level',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'status' => 'Status',
        ];
    }

    public function fields(){
        $fields = parent::fields();
        // 删除一些包含敏感信息的字段
        unset($fields['category_id'], $fields['description'], $fields['root'], $fields['pid'], $fields['model_id'], $fields['link_id'], $fields['cover_id'], $fields['display'], $fields['view'], $fields['create_time'], $fields['update_time']);
        return $fields;
    }

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
}
