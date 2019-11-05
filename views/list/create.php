<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wdmg\subscribers\models\SubscribersList */

$this->title = Yii::t('app/modules/subscribers', 'Add new list');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/modules/subscribers', 'Subscribers list'), 'url' => ['list/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header">
    <h1><?= Html::encode($this->title) ?> <small class="text-muted pull-right">[v.<?= $this->context->module->version ?>]</small></h1>
</div>
<div class="subscribers-list-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
