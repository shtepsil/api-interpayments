<?php

namespace backend\controllers;

use common\components\Debugger as d;

/**
 * Site controller
 */
class DebugController extends MainController
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'tab-debug-ajax' => [
                'class' => 'backend\actions\TabsAjaxActions',
                'actions' => [
                    'debug' => 'debug\\Debug',
                    'migrations' => 'debug\\Migrations',
                ],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

}//Class
