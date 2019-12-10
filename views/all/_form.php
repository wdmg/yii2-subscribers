<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wdmg\widgets\SelectInput;

/* @var $this yii\web\View */
/* @var $model wdmg\subscribers\models\Subscribers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subscribers-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'email')->textInput() ?>

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

    <?= $form->field($model, 'status')->widget(SelectInput::class, [
        'items' => $model->getStatusesList(),
        'options' => [
            'class' => 'form-control'
        ]
    ]); ?>
    <hr/>
    <div class="form-group">
        <?= Html::a(Yii::t('app/modules/subscribers', '&larr; Back to list'), ['all/index'], ['class' => 'btn btn-default pull-left']) ?>&nbsp;
        <?= Html::submitButton(Yii::t('app/modules/subscribers', 'Save'), ['class' => 'btn btn-success pull-right']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
