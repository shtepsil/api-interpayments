<?php

namespace backend\actions\debug;

use common\components\Debugger as d;
use common\models\User as ModelUser;
use InvalidArgumentException;
use Yii;
use yii\helpers\Url;
use backend\components\migration\m240708_171626_create_white_list_table;

class Migrations extends Main
{

    public function run()
    {
        $this->post = d::post();
//        d::debugAjax($this->post);
        $response = 'Debug->run() ничего не произошло.';
        switch($this->post['type']){
            case 'btn_push':
                $response = $this->test();
                break;
            case 'migrations_run':
                $response = $this->migrationsRun();
                break;
            default:
                $response = 'Debug->run()->switch:default';
        }
        return $response;
    }

    /*
     * Кнопка "Нажать"
     */
    public function test()
    {
        return 'Migrations->test()';
    }

    /**
     * Запуск миграций
     * @return false
     */
    public function migrationsRun()
    {
        $this->createTableWhiteList();
        return false;
    }

    /**
     * @return false
     */
    public function createTableWhiteList()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%white_list}}');
        $migration = new m240708_171626_create_white_list_table();
        if (!$table) {
            $migration->safeUp();
        } else {
//            $migration->safeDown();
        }
        return false;
    }

}//Class
