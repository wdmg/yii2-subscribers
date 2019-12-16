<?php

namespace wdmg\subscribers\models;

use Yii;
use yii\base\Model;
use wdmg\subscribers\models\Subscribers;

/**
 * SubscribersExport extends the model `wdmg\options\models\Subscribers`.
 */
class SubscribersExport extends Subscribers
{
    public $list_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['list_id'], 'match', 'pattern' => '/^(\d+|\*)$/', 'message' => Yii::t('app/modules/subscribers', 'Invalid field value.')]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'list_id' => Yii::t('app/modules/subscribers', 'Subscriber list'),
        ];
    }

    /**
     * Export subscribers
     *
     * @param integer $list_id
     * @return array
     */
    public function export($list_id = null)
    {
        $subscribers = [];
        if (empty($list_id)) { // subscribers not in list
            $subscribers = self::find()->select('name, email')->where(['list_id' => null])->asArray()->all();
        } else if ($list_id === "*") { // all subscribers
            $subscribers = self::find()->select('name, email')->asArray()->all();
        } else if (!is_null($list_id)) { // subscribers from current list
            $subscribers = self::find()->select('name, email')->where(['list_id' => intval($list_id)])->asArray()->all();
        }

        return array_unique($subscribers, SORT_REGULAR);
    }

}
