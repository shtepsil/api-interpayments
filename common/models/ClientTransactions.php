<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "client_transactions".
 *
 * @property int $id
 * @property int|null $service_id ID сервиса
 * @property int|null $client_id ID таблицы clients
 * @property string|null $account Аккаунт пользователя, на который нужно отправить сумму.
 * @property string|null $agent_transaction_id ID транзакции для сервиса
 * @property float|null $amount Сумма транзакции
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Clients $client
 */
class ClientTransactions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client_transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_id', 'client_id', 'created_at', 'updated_at'], 'integer'],
            [['amount'], 'number'],
            [['account', 'agent_transaction_id'], 'string', 'max' => 255],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clients::class, 'targetAttribute' => ['client_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'service_id' => 'ID сервиса',
            'client_id' => 'ID таблицы clients',
            'account' => 'Аккаунт пользователя, на который нужно отправить сумму.',
            'agent_transaction_id' => 'ID транзакции для сервиса',
            'amount' => 'Сумма транзакции',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Client]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Clients::class, ['id' => 'client_id']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

}//Class
