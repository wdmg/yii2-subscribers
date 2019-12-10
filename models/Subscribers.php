<?php

namespace wdmg\subscribers\models;

use Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use wdmg\subscribers\models\SubscribersList;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%subscribers}}".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property integer $list_id
 * @property integer $user_id
 * @property string $unique_token
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class Subscribers extends ActiveRecord
{
    const SUBSCRIBERS_STATUS_DISABLED = 0;
    const SUBSCRIBERS_STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%subscribers}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => new Expression('NOW()'),
            ]
        ];

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [
            [['email'], 'required'],
            [['name'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 255],
            [['list_id', 'user_id'], 'integer'],
            [['status'], 'boolean'],
            [['unique_token', 'created_at', 'updated_at'], 'safe'],
        ];

        if (class_exists('\wdmg\users\models\Users') && Yii::$app->has('users')) {
            $rules[] = [['user_id'], 'integer'];
        }

        return $rules;
    }


    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        $this->unique_token = Yii::$app->security->generateRandomString(32);
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->unique_token = Yii::$app->security->generateRandomString(32);
            }
            return true;
        }
        return false;

    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/modules/subscribers', 'ID'),
            'name' => Yii::t('app/modules/subscribers', 'Name'),
            'email' => Yii::t('app/modules/subscribers', 'E-mail'),
            'list_id' => Yii::t('app/modules/subscribers', 'List ID'),
            'user_id' => Yii::t('app/modules/subscribers', 'User ID'),
            'unique_token' => Yii::t('app/modules/subscribers', 'Unique token'),
            'status' => Yii::t('app/modules/subscribers', 'Status'),
            'created_at' => Yii::t('app/modules/subscribers', 'Created at'),
            'updated_at' => Yii::t('app/modules/subscribers', 'Updated at'),
        ];
    }

    /**
     * @return array of list
     */
    public function getStatusesList($allStatuses = false)
    {
        if ($allStatuses)
            $list[] = [
                '*' => Yii::t('app/modules/subscribers', 'All statuses')
            ];

        $list[] = [
            self::SUBSCRIBERS_STATUS_DISABLED => Yii::t('app/modules/subscribers', 'Disabled'),
            self::SUBSCRIBERS_STATUS_ACTIVE => Yii::t('app/modules/subscribers', 'Active')
        ];

        return $list;
    }

    /**
     * @return array of list
     */
    public function getSubscribersList($allList = false)
    {
        $list = [];
        if ($allList)
            $list = [
                '*' => Yii::t('app/modules/subscribers', 'All lists')
            ];

        $result = SubscribersList::find()->select('id, title')->asArray()->all();
        return ArrayHelper::merge($list, ArrayHelper::map($result, 'id', 'title'));
    }

    /**
     * @return object of \yii\db\ActiveQuery
     */
    public function getList()
    {
        if($list = $this->hasOne(SubscribersList::class, ['id' => 'list_id']))
            return $list;
        else
            return null;
    }

    /**
     * @return object of \yii\db\ActiveQuery
     */
    public function getUser()
    {
        if(class_exists('\wdmg\users\models\Users'))
            return $this->hasOne(\wdmg\users\models\Users::class, ['id' => 'user_id']);
        else
            return null;
    }

    /**
     * @return object of \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        if(class_exists('\wdmg\users\models\Users'))
            return $this->hasMany(\wdmg\users\models\Users::class, ['id' => 'user_id']);
        else
            return null;
    }
}
