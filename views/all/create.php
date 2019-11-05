<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wdmg\subscribers\models\Subscribers */

$this->title = Yii::t('app/modules/subscribers', 'Add new subscriber');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/modules/subscribers', 'All subscribers'), 'url' => ['all/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header">
    <h1><?= Html::encode($this->title) ?> <small class="text-muted pull-right">[v.<?= $this->context->module->version ?>]</small></h1>
</div>
<div class="subscribers-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
