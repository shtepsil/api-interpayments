<?php

namespace api\controllers;

use api\components\api\Api;
use api\components\Response;
use Yii;

class PaymentController extends MainController
{

    private string $agent_transaction_id = '600000001';

    /**
     * @inheritdoc
     */
    protected function verbs(): array
    {
        return [
            'index' => ['POST'],
//            'pay' => ['POST'],
            'check-status' => ['POST'],
            'check-and-pay' => ['POST'],
            'check-and-payy' => ['GET'],
        ];
    }

    public function actionIndex(): array
    {
        return ['message' => $this->notFound404];
    }

    /**
     * Для запроса check:
     * service_id - тип Int, Идентификационный номер Сервиса (Передается со стороны InterHub)
     * account - тип String, Аккаунт или номер телефона
     * amount - тип Double, Сумма, отправленная агентом
     * agent_transaction_id - тип String, Идентификационный номер транзакции на стороне Агента.
     *   Этот id не повторяется, и каждая новая операция должна сопровождаться новым id.
     * params - тип Object, Если необходимо ввести дополнительную информацию помимо account и amount,
     *   то добавляется в этот параметр (не обязателен).
     *
     * Для запроса pay:
     * agent_transaction_id - тип String, Идентификационный номер транзакции на стороне Агента.
     * Этот id не повторяется, и каждая новая операция должна сопровождаться новым id.
     *
     * @return string[]
     */
    public function actionCheckAndPay()
    {
        $post = Yii::$app->request->post();
//        return $post;

        if (
            !isset($post['agent_transaction_id'])
            OR !isset($post['account'])
            OR !isset($post['amount'])
        ) {
            return Response::error('Запрос должен содержать обязательные параметры: agent_transaction_id, account, amount.');
        }

        $checkResult = $this->api->paymentCheck([
            'service_id' => Yii::$app->params['service_id'],
            'account' =>  $post['account'],
//            'agent_transaction_id' => $this->agent_transaction_id,
            'agent_transaction_id' => $post['agent_transaction_id'],
//            'amount' => 0.30,
            'amount' => $post['amount'],
        ]);

//        return $checkResult;

        // Если check успешен
        if (is_array($checkResult) AND isset($checkResult['success']) AND $checkResult['success']) {
            $payResult = $this->api->paymentPay([
//                'agent_transaction_id' => $this->agent_transaction_id
                'agent_transaction_id' => $post['agent_transaction_id'],
            ]);

            // Если pay успешен
            if ($payResult['success']) {
                return Response::success($payResult);
            } else {
                //# если pay вернул ошибку
                return Response::error($payResult);
            }

        } else {
            //# если check вернул ошибку
            return Response::error($checkResult);
        }
    }

    public function actionCheckAndPayy()
    {
        $post = Yii::$app->request->post();
//        return $post;
        $get = Yii::$app->request->get();
//        return $get;

//        if (
//            !isset($post['agent_transaction_id'])
//            OR !isset($post['account'])
//            OR !isset($post['amount'])
//        ) {
//            return Response::error('Запрос должен содержать обязательные параметры: agent_transaction_id, account, amount.');
//        }

        $params = [
            'service_id' => Yii::$app->params['service_id'],
            'account' =>  'bowfnadenoc1972',
            'agent_transaction_id' => $this->agent_transaction_id,
            'amount' => 0,
        ];
//        $params = [
//            'service_id' => Yii::$app->params['service_id'],
//            'account' =>  $post['account'],
//            'agent_transaction_id' => $post['agent_transaction_id'],
//            'amount' => $post['amount'],
//        ];
//        $params = [
//            'service_id' => Yii::$app->params['service_id'],
//            'account' =>  $get['account'],
//            'agent_transaction_id' => $get['agent_transaction_id'],
//            'amount' => $get['amount'],
//        ];
//        d::ajax($params);

        $checkResult = $this->api->paymentCheck($params);
//        return $checkResult;

//        $payResult = $this->api->paymentPay([
//            'agent_transaction_id' => $this->agent_transaction_id
//        ]);
//        return $payResult;

        // Если check успешен
        if (is_array($checkResult) AND isset($checkResult['success']) AND $checkResult['success']) {
            $payResult = $this->api->paymentPay([
                'agent_transaction_id' => $this->agent_transaction_id
//                'agent_transaction_id' => $post['agent_transaction_id'],
            ]);

            // Если pay успешен
            if ($payResult['success']) {
                return Response::success($payResult);
            } else {
                //# если pay вернул ошибку
                return Response::error($payResult);
            }

        } else {
            //# если check вернул ошибку
            return Response::error($checkResult);
        }
    }

    /**
     * agent_transaction_id - тип String, Идентификационный номер транзакции на стороне Агента.
     *   Этот id не повторяется, и каждая новая операция должна сопровождаться новым id.
     * @return mixed
     */
    public function actionCheckStatus()
    {
        return $this->api->checkStatus([
            'agent_transaction_id' => $this->agent_transaction_id,
        ]);
    }

}//Class

