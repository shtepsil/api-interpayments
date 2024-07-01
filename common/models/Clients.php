<?php

namespace common\models;

use common\components\Debugger as d;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Clients model
 *
 * @property integer $id
 * @property string $username
 * @property string $access_token
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class Clients extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%clients}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'access_token' => 'Токен'
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['access_token', 'required', 'message' => 'Поле {attribute} не может быть пустым'],
            ['access_token', 'default', 'value' => NULL],
        ];
    }

    public function FormParams(): array
    {
        $result = [
            'form_action' => ['clients/save'],
            'cancel' => ['category/index'],
            'groups' => [
//                'main' => [
//                    'render' => [
//                        'view' => 'test'
//                    ]
//                ],
                'main' => [
                    'title' => 'Основное',
                    'icon' => 'suitcase',
                    'options' => [],
                    'fields' => [
                        'access_token' => [
                            'field_options' => [
                                'template' => Yii::$app->controller->renderPartial('field_access_token'),
                                'inputOptions' => [
                                    'placeholder' => 'Нажмите на кнопку "Создать новый токен"',
                                ]
                            ],
                        ],

                    ],
                ],
            ]
        ];

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

}//Class
