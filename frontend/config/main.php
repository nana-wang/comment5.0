<?php

$params = array_merge(
    require(__DIR__.'/../../common/config/params.php'),
    require(__DIR__.'/params.php'),
    require(__DIR__.'/login.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        \common\components\LoadPlugins::className(),
    ],
    'controllerNamespace' => 'frontend\controllers',
    'controllerMap' => [
        'upload' => \common\actions\UploadController::className()
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'on afterLogin' => function($event) {
                $event->identity->touch('login_at');
            }
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => '@frontend/components/bootstrap/dist'
                ],
            ],
        ],
        'view' => [
            'on beginPage' => function($event){
                if ($event->sender->title) {
                    $event->sender->title .= ' - ' . \Yii::$app->config->get('SITE_NAME');
                } else {
                    $event->sender->title = \Yii::$app->config->get('SITE_NAME');
                }
            }
        ],
        'pluginManager' => [
            'class' => 'common\components\PluginManager',
        ],
        'notify' => \frontend\components\notify\Handler::className(),
        'search' => [
            'class' => 'frontend\\components\\Search',
            'engine' => env('SEARCH_ENGINE', 'local')
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\GoogleOAuth',
                    'clientId' => '879321656698-nm2octb1fvs10vhsdtf8f6pl05jmb71k.apps.googleusercontent.com',
                    'clientSecret' => '6NtvwwI5s0DZZ-oxIuSaWKXq',
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '1732622793687865',
                    'clientSecret' => 'f1f0ff67e47b0e3bb378ac6041f1ef1e',
                ],
                'twitter' => [
                    'class' => 'yii\authclient\clients\Twitter',
                    'consumerKey' => '7rl8EYrKucbH97eVOmQ5iUqNG',
                    'consumerSecret' => 'oQKPl7aiDjRsKBcOerjr7nuHEvMi24XvZpNLxYhxDORDOHecRL'
                ],
            ],
        ]
    ],
    'as ThemeBehavior' => \frontend\behaviors\ThemeBehavior::className(),
    'as RouteBehavior' => \frontend\behaviors\RouteBehavior::className(),
    'params' => $params,
];
