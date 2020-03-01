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
<div class="subscribers-list-view">

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

            [
                'attribute' => 'created',
                'label' => Yii::t('app/modules/subscribers','Created'),
                'format' => 'html',
                'value' => function($data) {

                    $output = "";
                    if ($user = $data->createdBy) {
                        $output = Html::a($user->username, ['../admin/users/view/?id='.$user->id], [
                            'target' => '_blank',
                            'data-pjax' => 0
                        ]);
                    } else if ($data->created_by) {
                        $output = $data->created_by;
                    }

                    if (!empty($output))
                        $output .= ", ";

                    $output .= Yii::$app->formatter->format($data->updated_at, 'datetime');
                    return $output;
                }
            ],
            [
                'attribute' => 'updated',
                'label' => Yii::t('app/modules/subscribers','Updated'),
                'format' => 'html',
                'value' => function($data) {

                    $output = "";
                    if ($user = $data->updatedBy) {
                        $output = Html::a($user->username, ['../admin/users/view/?id='.$user->id], [
                            'target' => '_blank',
                            'data-pjax' => 0
                        ]);
                    } else if ($data->updated_by) {
                        $output = $data->updated_by;
                    }

                    if (!empty($output))
                        $output .= ", ";

                    $output .= Yii::$app->formatter->format($data->updated_at, 'datetime');
                    return $output;
                }
            ],

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
