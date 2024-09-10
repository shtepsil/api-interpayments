<?php

namespace backend\components\migration;

use Yii;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%clients}}`.
 */
class m240909_224722_add_check_ip_column_to_clients_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('clients');
        if(!$table->getColumn('check_ip')){
            $this->addColumn('clients', 'check_ip', $this->integer(1)->null()->defaultValue(1)->comment('Проверка запросов аккаунта на IP'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('clients');
        if ($table->getColumn('check_ip')) {
            $this->dropColumn('clients', 'check_ip');
        }
    }
}
