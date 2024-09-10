<?php

namespace backend\actions\debug;

use backend\components\migration\m240909_224722_add_check_ip_column_to_clients_table;
use common\components\Debugger as d;
use common\models\User as ModelUser;
use InvalidArgumentException;
use Yii;
use yii\helpers\Url;
use backend\components\migration\m240708_171626_create_white_list_table;
use backend\components\migration\m240807_132415_create_client_transactions_table;

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
        $this->createTableClientTransactions();
        $this->addCheckIpColumnToClientsTable();
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

    /**
     * @return false
     */
    public function createTableClientTransactions()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%client_transactions}}');
        $migration = new m240807_132415_create_client_transactions_table();
        if (!$table) {
            $migration->safeUp();
        } else {
//            $migration->safeDown();
        }
        return false;
    }

    public function addCheckIpColumnToClientsTable()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%clients}}');
        $migration = new m240909_224722_add_check_ip_column_to_clients_table();
        if (!$table->getColumn('check_ip')) {
            $migration->safeUp();
        } else {
//            $migration->safeDown();
        }
        return false;
    }

}//Class
