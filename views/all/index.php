<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use wdmg\widgets\SelectInput;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel wdmg\subscribers\models\SubscriberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app/modules/subscribers', 'All subscribers');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="page-header">
    <h1><?= Html::encode($this->title) ?> <small class="text-muted pull-right">[v.<?= $this->context->module->version ?>]</small></h1>
</div>
<div class="subscribers-index">

    <?php Pjax::begin(); ?>
    <?php /*echo $this->render('_search', ['model' => $searchModel]);*/ ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => '{summary}<br\/>{items}<br\/>{summary}<br\/><div class="text-center">{pager}</div>',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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
                'filter' => SelectInput::widget([
                    'model' => $searchModel,
                    'attribute' => 'list_id',
                    'items' => $searchModel->getSubscribersList(true),
                    'options' => [
                        'class' => 'form-control'
                    ]
                ]),
                'format' => 'html',
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
                'attribute' => 'status',
                'format' => 'html',
                'filter' => SelectInput::widget([
                    'model' => $searchModel,
                    'attribute' => 'status',
                    'items' => $searchModel->getStatusesList(true),
                    'options' => [
                        'class' => 'form-control'
                    ]
                ]),
                'headerOptions' => [
                    'class' => 'text-center'
                ],
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'value' => function($data) {
                    if ($data->status == $data::SUBSCRIBERS_STATUS_ACTIVE)
                        return '<span class="label label-success">'.Yii::t('app/modules/subscribers','Active').'</span>';
                    elseif ($data->status == $data::SUBSCRIBERS_STATUS_DISABLED)
                        return '<span class="label label-default">'.Yii::t('app/modules/subscribers','Disabled').'</span>';
                    else
                        return $data->status;
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app/modules/subscribers', 'Actions'),
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'urlCreator' => function ($action, $model, $key, $index) {

                    if ($action === 'view')
                        return \yii\helpers\Url::toRoute(['all/view', 'id' => $key]);

                    if ($action === 'update')
                        return \yii\helpers\Url::toRoute(['all/update', 'id' => $key]);

                    if ($action === 'delete')
                        return \yii\helpers\Url::toRoute(['all/delete', 'id' => $key]);

                }
            ],
        ],
        'pager' => [
            'options' => [
                'class' => 'pagination',
            ],
            'maxButtonCount' => 5,
            'activePageCssClass' => 'active',
            'prevPageCssClass' => 'prev',
            'nextPageCssClass' => 'next',
            'firstPageCssClass' => 'first',
            'lastPageCssClass' => 'last',
            'firstPageLabel' => Yii::t('app/modules/subscribers', 'First page'),
            'lastPageLabel'  => Yii::t('app/modules/subscribers', 'Last page'),
            'prevPageLabel'  => Yii::t('app/modules/subscribers', '&larr; Prev page'),
            'nextPageLabel'  => Yii::t('app/modules/subscribers', 'Next page &rarr;')
        ],
    ]); ?>
    <hr/>
    <div>
        <div class="btn-group">
            <?= Html::a(Yii::t('app/modules/subscribers', 'Delete all'), ['all/clear'], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app/modules/subscribers', 'Are you sure you want to delete all subscribers? This action will not be undone.'),
                    'method' => 'POST'
                ]
            ]) ?>
            <?= Html::a(Yii::t('app/modules/subscribers', 'Import subscribers'), ['all/import'], [
                'class' => 'btn btn-warning',
                'data-toggle' => 'modal',
                'data-target' => '#subscribersImport',
                'data-pjax' => '1'
            ]) ?>
            <?= Html::a(Yii::t('app/modules/subscribers', 'Export subscribers'), ['all/export'], [
                'class' => 'btn btn-info',
                'data-toggle' => 'modal',
                'data-target' => '#subscribersExport',
                'data-pjax' => '1'
            ]) ?>
        </div>
        <?= Html::a(Yii::t('app/modules/subscribers', 'Add subscriber'), ['all/create'], ['class' => 'btn btn-success pull-right']) ?>
    </div>
    <?php Pjax::end(); ?>
</div>


<?php Modal::begin([
    'id' => 'subscribersImport',
    'header' => '<h4 class="modal-title">'.Yii::t('app/modules/subscribers', 'Subscribers import').'</h4>',
]); ?>
<?php echo $this->render('_import', ['model' => $importModel]); ?>
<?php Modal::end(); ?>

<?php Modal::begin([
    'id' => 'subscribersExport',
    'header' => '<h4 class="modal-title">'.Yii::t('app/modules/subscribers', 'Subscribers export').'</h4>',
]); ?>
<?php echo $this->render('_export', ['model' => $exportModel]); ?>
<?php Modal::end(); ?>

<?php echo $this->render('../_debug'); ?>
