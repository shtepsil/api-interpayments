<?php

namespace common\models;

use common\components\Debugger as d;
use kartik\select2\Select2;
use shadow\SActiveRecord;
use shadow\widgets\CKEditor;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\View;

/**
 * Clients model
 *
 * @property integer $id
 * @property string $name
 * @property string $description
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
 * @property string $check_ip
 */
class Clients extends SActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;
    const SCENARIO_NEW_CLIENT = 'new_client';


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
            'access_token' => 'Токен',
            'username' => 'Логин',
            'email' => 'Email',
            'name' => 'Имя клиента(аккаунта)',
            'description' => 'Описание',
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
            [['name', 'description', 'access_token'], 'trim'],
            [['name', 'description', 'access_token'], 'required', 'message' => 'Поле {attribute} не может быть пустым'],
            [['name', 'description', 'access_token'], 'default', 'value' => NULL],
            [['name', 'description'], 'string', 'max' => 255],
            [['access_token'], 'string', 'max' => 32],
            [['check_ip'], 'default', 'value' => 0],
        ];
    }

    public $password;
    public $ips;
    public function FormParams(): array
    {
        $data_ips = WhiteList::find()
            ->select(['ip', 'id'])
            ->where(['client_id' => $this->id])
            ->indexBy('id')->column();

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
//                        'username' => [],
//                        'email' => [],
                        'name' => [],
                        'description' => [
                            'type' => 'textArea',
                            'widget' => [
                                'class' => CKEditor::className()
                            ]
                        ],
                    ],
                ],
                'ips_list' => [
                    'title' => 'Разрешённые IP (<span class="tab-count-ips">' . count($data_ips) . '</span>)',
                    'icon' => 'suitcase',
                    'options' => [],
                    'fields' => [
                        'check_ip' => [
                            'title' => 'Проверка запросов на IP',
//                            'type' => 'render',
//                            'render' => [
//                                'view' => 'switcher_check_ip',
//                                'params' => [
//                                    'check_ip' => $this->check_ip,
//                                ],
//                            ],
                            'type' => 'checkbox',
                            'field_options' => [
                                'template' => Yii::$app->controller->renderPartial('switcher_check_ip'),
                            ],
                            'params' => [
                                'class' => 'js-switch js-switch-1',
                                'data-color' => '#469408',
                                'data-secondary-color' => '#dc4666','data-size' => 'small'
                            ],
                        ],
                        'ips' => [
                            'title' => 'Список разрешённых<br>IP адресов ',
                            'type' => 'render',
                            'render' => [
                                'view' => 'white_list_ips',
                                'params' => [
                                    'client_id' => $this->id,
                                    'data_ips' => $data_ips
                                ],
                            ],
//                            'template' => Yii::$app->controller->renderPartial('white_list_ips'),
                            'inputOptions' => [
//                                'placeholder' => 'Нажмите на кнопку "Создать новый токен"',
                            ]
                        ],
                    ],
                ],
            ]
        ];

        return $result;
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $time = time();
            $this->setUsername($time);
//            $this->setPassword($this->password);
            $this->setPassword($time);
            $this->generateAuthKey();
            $this->generateEmailVerificationToken();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function setUsername($time) {
        $this->username =  'username' . $time;
    }

    /**
     * @return bool
     */
    public function checkAccessIP()
    {
//        d::ajax(Yii::$app->request->userIP);
        $access = false;
        $ips = WhiteList::find()
            ->select('ip')
            ->where(['client_id' => $this->id])
            ->indexBy('ip')->asArray()->all();

//        d::ajax([
//            'id' => $identity->id,
//            'ip' => Yii::$app->request->userIP,
//            'ips' => array_keys($ips),
//            'token' => $identity->access_token
//        ]);

        if (count($ips) > 0 AND in_array(Yii::$app->request->userIP, array_keys($ips))) {
            $access = true;
        }
        return $access;
    }

    public static function getAll()
    {
        return static::find()->where(['status' => self::STATUS_ACTIVE])->all();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $identity = static::findOne(['access_token' => $token]);

        if ($identity) {
            if ($identity->check_ip == '1' AND !$identity->checkAccessIP()) {
                $identity = false;
            }
        }

        return $identity;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

}//Class
