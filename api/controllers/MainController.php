<?php

namespace api\controllers;

use common\components\Debugger as d;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;

class MainController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::className(),
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ];
//        $behaviors['authenticator'] = [
//            'class' => HttpBearerAuth::class,
//        ];
        return $behaviors;
    }

}//Class
