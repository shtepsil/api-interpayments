<?php

namespace common\models;

use common\components\Debugger as d;
use shadow\SActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;

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
class WhiteList extends SActiveRecord
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
//            [['ip'], 'unique', 'message' => 'Такой IP адрес уже существует'],
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
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

//    public function save($runValidation = true, $attributeNames = null)
//    {
////        d::ajax('save');
//        return parent::save($runValidation, $attributeNames); // TODO: Change the autogenerated stub
//    }


}//Class
