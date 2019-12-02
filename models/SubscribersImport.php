<?php

namespace wdmg\subscribers\models;

use Yii;
use yii\base\Model;
use wdmg\subscribers\models\Subscribers;
use yii\validators\EmailValidator;

/**
 * SubscribersImport extends the model `wdmg\options\models\Subscribers`.
 */
class SubscribersImport extends Subscribers
{
    public $list_id;
    public $import;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            /*[['import'], 'required'],*/
            [['list_id'], 'match', 'pattern' => '/^(\d+|\*)$/', 'message' => Yii::t('app/modules/subscribers', 'Invalid field value.')],
            //[['import'], 'file', 'skipOnEmpty' => false, 'minSize' => 1, 'maxSize' => 312000],
            [['import'], 'file', 'skipOnEmpty' => true, 'minSize' => 1, 'maxSize' => 312000],
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
            'list_id' => Yii::t('app/modules/subscribers', 'Add to the list'),
            'import' => Yii::t('app/modules/subscribers', 'File import ({mimetype})', ['mimetype' => 'CSV']),
        ];
    }

    /**
     * Import subscribers
     *
     * @param array $subscribers
     * @param integer $list_id
     * @return integer, count of added emails
     */
    public function import($subscribers = null, $list_id = null)
    {
        $count = 0;
        if (!is_null($subscribers) && is_array($subscribers)) {
            $validator = new EmailValidator();
            foreach ($subscribers as $subscriber) {
                $name = trim($subscriber[0]);
                $email = trim($subscriber[1]);

                if ($validator->validate($email)) {
                    $model = new Subscribers();
                    $model->name = $name;
                    $model->email = $email;

                    if (!is_null($list_id) && !empty($list_id))
                        $model->list_id = intval($list_id);

                    if ($model->save())
                        $count++;
                }
            }
        }
        return $count;
    }

}
