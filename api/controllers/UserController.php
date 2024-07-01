<?php

namespace api\controllers;

use api\components\api\Api;

class UserController extends MainController
{

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'get-balance' => ['GET'],
            'get-payments' => ['GET'],
        ];
    }

    public function actionIndex()
    {
        return ['message' => $this->notFound404];
    }

    /**
     * Запрос только к нам
     * @return mixed
     */
    public function actionGetBalance()
    {
        return ['message' => 'Запрос ( GetBalance ) только в нашу БД'];
    }

    /**
     * Запрос только к нам
     * @return mixed
     */
    public function actionGetPayments()
    {
        return ['message' => 'Запрос ( GetPayments ) только в нашу БД'];
    }

}//Class

