<?php

namespace wdmg\subscribers\models;

use Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%subscribers}}".
 *
 * @property int $id
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
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => new Expression('NOW()'),
            ]
        ];

        if (class_exists('\wdmg\users\models\Users') && isset(Yii::$app->modules['users'])) {
            $behaviors['blameable'] = [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id'
            ];
        }

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [
            [['email'], 'required'],
            [['email'], 'string', 'max' => 255],
            [['list_id', 'user_id'], 'integer'],
            [['status'], 'boolean'],
            [['unique_token', 'created_at', 'updated_at'], 'safe'],
        ];

        if (class_exists('\wdmg\users\models\Users') && isset(Yii::$app->modules['users'])) {
            $rules[] = [['user_id'], 'integer'];
        }

        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/modules/newsletters', 'ID'),
            'email' => Yii::t('app/modules/newsletters', 'E-mail'),
            'list_id' => Yii::t('app/modules/newsletters', 'List ID'),
            'user_id' => Yii::t('app/modules/newsletters', 'User ID'),
            'unique_token' => Yii::t('app/modules/newsletters', 'Unique token'),
            'status' => Yii::t('app/modules/newsletters', 'Status'),
            'created_at' => Yii::t('app/modules/newsletters', 'Created at'),
            'updated_at' => Yii::t('app/modules/newsletters', 'Updated_at'),
        ];
    }

    /**
     * @return array of list
     */
    public function getStatusesList($allStatuses = false)
    {
        if($allStatuses)
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
     * @return object of \yii\db\ActiveQuery
     */
    public function getUser()
    {
        if(class_exists('\wdmg\users\models\Users'))
            return $this->hasOne(\wdmg\users\models\Users::className(), ['id' => 'user_id']);
        else
            return null;
    }

    /**
     * @return object of \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        if(class_exists('\wdmg\users\models\Users'))
            return $this->hasMany(\wdmg\users\models\Users::className(), ['id' => 'user_id']);
        else
            return null;
    }
}
