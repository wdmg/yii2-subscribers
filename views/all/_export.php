<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use wdmg\widgets\SelectInput;

/* @var $this yii\web\View */
/* @var $model wdmg\subscribers\models\Subscribers */

\yii\web\YiiAsset::register($this);

?>
<div class="subscribers-export">
    <?php $form = ActiveForm::begin([
        'id' => "exportSubscribersForm",
        'action' => Url::to(['all/export']),
        'method' => 'post',
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>
    <?= $form->field($model, 'list_id')->widget(SelectInput::class, [
        'model' => $model,
        'attribute' => 'list_id',
        'items' => \yii\helpers\ArrayHelper::merge([
            null => Yii::t('app/modules/subscribers', 'Not in listed'),
            '*' => Yii::t('app/modules/subscribers', 'All subscribers')
        ], $model->getSubscribersList()),
        'options' => [
            'class' => 'form-control'
        ]
    ]); ?>
    <div class="row">
        <div class="modal-footer" style="clear:both;display:inline-block;width:100%;padding-bottom:0;">
            <?= Html::a(Yii::t('app/modules/subscribers', 'Close'), "#", [
                'class' => 'btn btn-default pull-left',
                'data-dismiss' => 'modal'
            ]) ?>
            <?= Html::submitButton(Yii::t('app/modules/subscribers', 'Export'), ['class' => 'btn btn-success pull-right']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php $this->registerJs(
'$(document).ready(function() {
        $(\'#exportSubscribersForm\').on(\'submit\', function(event) {
            if (event.result) {
                $(\'#subscribersExport\').modal(\'hide\');
            }
        });
    });'
); ?>
