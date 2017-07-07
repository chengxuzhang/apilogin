<?php

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'user',
        'extraPatterns' => [
            'GET userInfo' => 'user-info',
            'POST login' => 'login',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'document',
        'extraPatterns' => [
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['v1/documents'=>'v1/document'],
        'extraPatterns' => [
        ],
    ],
];