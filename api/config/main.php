<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'debugger'],
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'debugger' => [
            'class' => \common\components\Debugger::class,
        ],
        'response' => [
            // ...
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // используем "pretty" в режиме отладки
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                    // ...
                ],
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-api',
            'baseUrl' => '/api',
//            'parsers' => [
//                'application/json' => 'yii\web\JsonParser',
//            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the api
            'name' => 'advanced-api',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
                '<action>' => 'site/<action>',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'site',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET <action>' => '<action>',
                        'POST <action>' => '<action>',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'user',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET <action>' => '<action>',
                        'POST <action>' => '<action>',
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
];
