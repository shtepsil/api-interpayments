<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vite_list}}`.
 */
class m240708_171626_create_white_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('{{%white_list}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer(11)->null()->defaultValue(NULL)->comment('ID таблицы clients'),
            'ip' => $this->string()->null()->defaultValue(NULL)->comment('Разрешённый IP адрес'),
            'created_at' => $this->integer(11)->null()->defaultValue(NULL),
            'updated_at' => $this->integer(11)->null()->defaultValue(NULL),
        ]);

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-white_list-client_id',
            'white_list',
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
        $this->dropTable('{{%white_list}}');
    }
}
