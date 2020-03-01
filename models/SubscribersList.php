<?php

namespace wdmg\subscribers\models;

use Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%subscribers_list}}".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property integer $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class SubscribersList extends ActiveRecord
{
    const SUBSCRIBERS_LIST_STATUS_DISABLED = 0;
    const SUBSCRIBERS_LIST_STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%subscribers_list}}';
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
            ],
            'blameable' =>  [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['status'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
        ];

        if (class_exists('\wdmg\users\models\Users') && isset(Yii::$app->modules['users'])) {
            $rules[] = [['created_by', 'updated_by'], 'required'];
        }

        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/modules/subscribers', 'ID'),
            'title' => Yii::t('app/modules/subscribers', 'Title'),
            'description' => Yii::t('app/modules/subscribers', 'Description'),
            'status' => Yii::t('app/modules/subscribers', 'Status'),
            'created_at' => Yii::t('app/modules/subscribers', 'Created at'),
            'created_by' => Yii::t('app/modules/subscribers', 'Created by'),
            'updated_at' => Yii::t('app/modules/subscribers', 'Updated at'),
            'updated_by' => Yii::t('app/modules/subscribers', 'Updated by')
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
            self::SUBSCRIBERS_LIST_STATUS_DISABLED => Yii::t('app/modules/subscribers', 'Disabled'),
            self::SUBSCRIBERS_LIST_STATUS_ACTIVE => Yii::t('app/modules/subscribers', 'Active')
        ];

        return $list;
    }

    /**
     * @return integer
     */
    public function getCount()
    {
        return $this->hasOne(\wdmg\subscribers\models\Subscribers::class, ['list_id' => 'id'])->count();
    }

    /**
     * @return object of \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        if (class_exists('\wdmg\users\models\Users'))
            return $this->hasOne(\wdmg\users\models\Users::class, ['id' => 'created_by']);
        else
            return $this->created_by;
    }

    /**
     * @return object of \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        if (class_exists('\wdmg\users\models\Users'))
            return $this->hasOne(\wdmg\users\models\Users::class, ['id' => 'updated_by']);
        else
            return $this->updated_by;
    }
}
