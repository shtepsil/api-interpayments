<?php

namespace api\controllers;

class UserController extends MainController
{

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'create' => ['POST'],
        ];
    }

    public function actionIndex()
    {
        return ['UserController' => 'actionIndex()'];
    }

    public function actionCreate()
    {
        return ['UserController' => 'create()'];
    }
}

