<?php

use yii\bootstrap\BootstrapAsset;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log', 'debugger'],
//    'defaultRoute' => 'clients/index',// Не правильно срабатывает
//    'catchAll' => ['site/offline'],// Сработал, только page 404, надо разбираться.
    'modules' => [],
    'aliases' => [
        '@logs' => '@runtime/logs',
    ],
    'components' => [
        'debugger' => [
            'class' => \common\components\Debugger::class,
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '/admin',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
//                'file' => [
//                    'class' => \yii\log\FileTarget::class,
//                    'levels' => ['error', 'warning'],
//                    'except' => [
//                        'backend\actions\debug\Logs*',
//                    ],
//                ],
                'requests' => [
                    'class' => \backend\components\log\CustomFileTarget::class,
//                    'class' => \backend\components\log\CustomDbTarget::class,
                    'levels' => ['info', 'error'],
                    'categories' => [
                        'backend\actions\debug\Logs*',
                    ],
                    'logVars' => ['_GET', '_POST', '_FILES', '_COOKIE'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
            'linkAssets' => true,
            'bundles' => [
//                JqueryAsset::class => false,
//                YiiAsset::class => false,
//                ActiveFormAsset::class => false,
                BootstrapAsset::class => false,
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
                'tab-debug-ajax' => 'debug/tab-debug-ajax',
                '<action>' => 'site/<action>',
                '<controller>/<action>' => '<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];
