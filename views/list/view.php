<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model wdmg\subscribers\models\SubscribersList */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/modules/subscribers', 'Subscribers list'), 'url' => ['list/index']];
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
            'title',
            'description',
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->status == $data::SUBSCRIBERS_LIST_STATUS_DISABLED)
                        return '<span class="label label-danger">'.Yii::t('app/modules/subscribers','Disabled').'</span>';
                    elseif ($data->status == $data::SUBSCRIBERS_LIST_STATUS_ACTIVE)
                        return '<span class="label label-success">'.Yii::t('app/modules/subscribers','Active').'</span>';
                    else
                        return false;
                },
            ],
            'created_at:datetime',
            'created_by',
            'updated_at:datetime',
            'updated_by',

        ],
    ]) ?>

    <div class="form-group">
        <?= Html::a(Yii::t('app/modules/subscribers', '&larr; Back to list'), ['list/index'], ['class' => 'btn btn-default pull-left']) ?>&nbsp;
        <?= Html::a(Yii::t('app/modules/subscribers', 'Edit'), ['list/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app/modules/subscribers', 'Delete'), ['list/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger pull-right',
            'data' => [
                'confirm' => Yii::t('app/modules/subscribers', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </div>

</div>
