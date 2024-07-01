<?php
/*
 * Ссылка на документацию Kaspi API
 * https://kaspi.kz/merchantcabinet/support/pages/viewpage.action?pageId=22645486
 */

namespace api\components\api;

use common\components\Debugger as d;

class Api extends ApiBase
{

    public function __construct($token = '')
    {
        if ($token == '') {
            throw new CException('Токен обязателен');
        }
        parent::__construct($token);
    }

    // SiteController =========================================

    /**
     * @param array $params
     * @return mixed
     */
    public function agentDeposit(array $params = [])
    {
        return $this->request('agent/deposit', $params);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function agentTransactionList(array $params = [])
    {
        return $this->request('agent/transaction/list', $params);
    }

    // PaymentsController =====================================

    /**
     * @param array $params
     * @return mixed
     */
    public function paymentCheck(array $params = [])
    {
        return $this->request('payment/check', $params, 'POST');
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function paymentPay(array $params)
    {
        return $this->request('payment/pay', $params, 'POST');
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function checkStatus(array $params = [])
    {
        return $this->request('payment/check_status', $params);
    }

    // DEBUG ==================================================
    // ========================================================
    // ========================================================
    // ========================================================

    public function testRequest()
    {
        $params = [
            'service_id' => 92,
            'account' =>  'igromen4g',
            'agent_transaction_id' => '54046441',
            'amount' => 10.00,
        ];
        return $this->request('payment/check', $params, 'POST');
    }

} //Class
