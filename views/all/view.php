<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model wdmg\subscribers\models\Subscribers */

$this->title = $model->email;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/modules/subscribers', 'All subscribers'), 'url' => ['all/index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="page-header">
    <h1><?= Html::encode($this->title) ?> <small class="text-muted pull-right">[v.<?= $this->context->module->version ?>]</small></h1>
</div>
<div class="subscribers-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'email',
                'label' => Yii::t('app/modules/subscribers','Subscriber'),
                'format' => 'text',
                'value' => function($data) {
                    if ($data->name && $data->email)
                        return $data->name . ' <' . $data->email . '>';
                    else if ($data->email)
                        return '<' . $data->email . '>';
                    else
                        return null;
                }
            ],
            [
                'attribute' => 'list_id',
                'label' => Yii::t('app/modules/subscribers','Subscriber list'),
                'format' => 'text',
                'value' => function($data) {
                    if ($list = $data->list)
                        return $list->title;
                    else
                        return $data->list_id;
                }
            ],
            [
                'attribute' => 'user_id',
                'label' => Yii::t('app/modules/subscribers','User'),
                'format' => 'html',
                'value' => function($data) {
                    if ($user = $data->user)
                        return $user->username;
                    else
                        return $data->user_id;
                }
            ],

            [
                'attribute' => 'unique_token',
                'format' => 'html',
                'value' => function($data) {
                    $string = $data->unique_token;
                    $length = strlen($string);
                    $sub_len = abs($length / 5);
                    if($string && $length > 6)
                        return substr($string, 0, $sub_len) . '…' . substr($string, -$sub_len, $sub_len) . ' <span class="text-muted pull-right">[length: '.$length.']</span>';
                    else
                        return $string;
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->status == $data::SUBSCRIBERS_STATUS_DISABLED)
                        return '<span class="label label-danger">'.Yii::t('app/modules/subscribers','Disabled').'</span>';
                    elseif ($data->status == $data::SUBSCRIBERS_STATUS_ACTIVE)
                        return '<span class="label label-success">'.Yii::t('app/modules/subscribers','Active').'</span>';
                    else
                        return false;
                },
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::a(Yii::t('app/modules/subscribers', '&larr; Back to list'), ['all/index'], ['class' => 'btn btn-default pull-left']) ?>&nbsp;
        <?= Html::a(Yii::t('app/modules/subscribers', 'Edit'), ['all/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app/modules/subscribers', 'Delete'), ['all/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger pull-right',
            'data' => [
                'confirm' => Yii::t('app/modules/subscribers', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </div>

</div>
