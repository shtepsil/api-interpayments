<?php

namespace backend\controllers;

use common\components\Debugger as d;
use common\models\Clients;
use shadow\helpers\CryptoHelper;
use yii\helpers\Inflector;

class ClientsController extends MainController
{
    public function init()
    {
//        d::pre(CryptoHelper::createBearerToken());
        $this->model = new Clients();
        $controller_name = Inflector::camel2id($this->id);
        $this->url = [
            'back' => [$controller_name . '/index'],
            'control' => [$controller_name . '/control']
        ];
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function actionIndex()
    {
        return $this->render('index', ['clients' => Clients::getAll()]);
    }

}//Class
