<?php

namespace backend\actions\debug;

use backend\components\log\LogUserId;
use common\components\Debugger as d;
use common\models\User as ModelUser;
use InvalidArgumentException;
use Yii;
use yii\helpers\Url;
use backend\components\migration\m240708_171626_create_white_list_table;

class Logs extends Main
{

    public function run()
    {
        $this->post = d::post();
//        d::debugAjax($this->post);
        $response = 'Logs->run() ничего не произошло.';
        switch($this->post['type']){
            case 'test_logs':
                $response = $this->test();
                break;
            default:
                $response = 'Logs->run()->switch:default';
        }
        return $response;
    }

    /*
     * Кнопка "Нажать"
     */
    public function test()
    {
//        d::ajax(__METHOD__);
        LogUserId::info([
            'user_id' => 41,
            'message' => 'Тест лога warning',
            'request' => 'send/pay'
        ], __METHOD__, 41);
//        Yii::info('Текст лога', __METHOD__);
        return 'Logs->test()';
    }

}//Class
