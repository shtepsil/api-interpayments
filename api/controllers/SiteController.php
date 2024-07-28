<?php

namespace api\controllers;

use api\components\api\Api;
use common\components\Debugger as d;
use shadow\helpers\Json;
use Yii;

/**
 * Site controller
 */
class SiteController extends MainController
{
//    public $modelClass = 'common\models\User';

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'get-ip' => ['GET'],
            'gross-balance' => ['GET'],
            'gross-payments' => ['GET'],
        ];
    }

    public function actionIndex()
    {
        return ['message' => $this->notFound404];
    }

    public function actionGetIp()
    {
        return Yii::$app->request->userIP;
    }

    /**
     * @return mixed
     */
    public function actionGrossBalance()
    {
        return $this->api->agentDeposit();
    }

    /**
     * date - тип date, формат дд.мм.гггг
     * @return mixed
     */
    public function actionGrossPayments($date)
    {
        return $this->api->agentTransactionList([
            'date' => $date,
        ]);
    }

}
