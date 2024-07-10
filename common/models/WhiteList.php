<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "white_list".
 *
 * @property int $id
 * @property int|null $user_id ID таблицы clients
 * @property string|null $ip Разрешённый IP адрес
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Clients $user
 */
class WhiteList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'white_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'created_at', 'updated_at'], 'integer'],
            [['ip'], 'string', 'max' => 255],
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
            'client_id' => 'ID таблицы clients',
            'ip' => 'Разрешённый IP адрес',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Clients]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Clients::class, ['id' => 'client_id']);
    }
}
