<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use wdmg\widgets\SelectInput;

/* @var $this yii\web\View */
/* @var $model wdmg\subscribers\models\Subscribers */

\yii\web\YiiAsset::register($this);

?>
<div class="subscribers-import">
    <?php $form = ActiveForm::begin([
        'id' => "importSubscribersForm",
        'action' => Url::to(['all/import']),
        'method' => 'post',
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>
    <?= $form->field($model, 'list_id')->widget(SelectInput::class, [
        'model' => $model,
        'attribute' => 'list_id',
        'items' => \yii\helpers\ArrayHelper::merge([
            null => Yii::t('app/modules/subscribers', 'Not in listed')
        ], $model->getSubscribersList()),
        'options' => [
            'class' => 'form-control'
        ]
    ]); ?>
    <?= $form->field($model, 'import')->fileInput(['accept' => 'text/csv']) ?>
    <div class="row">
        <div class="modal-footer" style="clear:both;display:inline-block;width:100%;padding-bottom:0;">
            <?= Html::a(Yii::t('app/modules/subscribers', 'Close'), "#", [
                'class' => 'btn btn-default pull-left',
                'data-dismiss' => 'modal'
            ]) ?>
            <?= Html::submitButton(Yii::t('app/modules/subscribers', 'Import'), ['class' => 'btn btn-success pull-right']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
