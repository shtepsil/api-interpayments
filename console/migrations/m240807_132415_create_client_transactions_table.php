<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client_transactions}}`.
 */
class m240807_132415_create_client_transactions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('{{%client_transactions}}', [
            'id' => $this->primaryKey(),
            'service_id' => $this->integer(11)->null()->defaultValue(NULL)->comment('ID сервиса'),
            'client_id' => $this->integer(11)->null()->defaultValue(NULL)->comment('ID таблицы clients'),
            'account' => $this->string()->null()->defaultValue(NULL)->comment('Аккаунт пользователя, на который нужно отправить сумму.'),
            'agent_transaction_id' => $this->string()->null()->defaultValue(NULL)->comment('ID транзакции для сервиса'),
            'amount' => $this->float(2)->null()->defaultValue(NULL)->comment('Сумма транзакции'),
            'created_at' => $this->integer(11)->null()->defaultValue(NULL),
            'updated_at' => $this->integer(11)->null()->defaultValue(NULL),
        ]);

        // add foreign key for table `client_transactions`
        $this->addForeignKey(
            'fk-client_transactions-client_id',
            'client_transactions',
            'client_id',
            'clients',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%client_transactions}}');
    }
}
