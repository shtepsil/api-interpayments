<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@shadow' => '@app/../shadow',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
//        'log' => [
//            'traceLevel' => YII_DEBUG ? 3 : 0,
//            'targets' => [
//                'file' => [
//                    'class' => \yii\log\FileTarget::class,
//                    'levels' => ['error', 'warning'],
//                    'except' => [
//                        'backend\actions\debug\Logs*',
//                    ],
//                ],
//                'requests' => [
////                    'class' => \backend\components\log\CustomFileTarget::class,
//                    'class' => \yii\log\DbTarget::class,
//                    'levels' => ['info'],
//                    'categories' => [
//                        'backend\actions\debug\Logs*',
//                    ],
//                    'logVars' => ['_GET', '_POST', '_FILES', '_COOKIE'],
//                ],
//            ],
//        ],
    ],
];
