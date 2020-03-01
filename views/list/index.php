<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use wdmg\widgets\SelectInput;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel wdmg\subscribers\models\SubscriberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app/modules/subscribers', 'Subscribers list');
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

            'title',
            'description',

            [
                'attribute' => 'count',
                'format' => 'html',
                'headerOptions' => [
                    'class' => 'text-center'
                ],
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'label' => Yii::t('app/modules/subscribers', 'Count'),
                'value' => function($data) {
                    if ($data->count > 0)
                        return Html::a($data->count, ['all/index', 'SubscribersSearch[list_id]' => $data->id]);
                    else
                        return 0;
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
                    if ($data->status == $data::SUBSCRIBERS_LIST_STATUS_ACTIVE)
                        return '<span class="label label-success">'.Yii::t('app/modules/subscribers','Active').'</span>';
                    elseif ($data->status == $data::SUBSCRIBERS_LIST_STATUS_DISABLED)
                        return '<span class="label label-default">'.Yii::t('app/modules/subscribers','Disabled').'</span>';
                    else
                        return $data->status;
                }
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
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app/modules/subscribers', 'Actions'),
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'urlCreator' => function ($action, $model, $key, $index) {

                    if ($action === 'view')
                        return \yii\helpers\Url::toRoute(['list/view', 'id' => $key]);

                    if ($action === 'update')
                        return \yii\helpers\Url::toRoute(['list/update', 'id' => $key]);

                    if ($action === 'delete')
                        return \yii\helpers\Url::toRoute(['list/delete', 'id' => $key]);

                }
            ],
        ],
        'pager' => [
            'options' => [
                'class' => 'pagination',
            ],
            'maxButtonCount' => 5,
            'activePageCssClass' => 'active',
            'prevPageCssClass' => '',
            'nextPageCssClass' => '',
            'firstPageCssClass' => 'previous',
            'lastPageCssClass' => 'next',
            'firstPageLabel' => Yii::t('app/modules/subscribers', 'First page'),
            'lastPageLabel'  => Yii::t('app/modules/subscribers', 'Last page'),
            'prevPageLabel'  => Yii::t('app/modules/subscribers', '&larr; Prev page'),
            'nextPageLabel'  => Yii::t('app/modules/subscribers', 'Next page &rarr;')
        ],
    ]); ?>
    <hr/>
    <div>
        <div class="btn-group">
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
        <?= Html::a(Yii::t('app/modules/subscribers', 'Add list'), ['list/create'], ['class' => 'btn btn-success pull-right']) ?>
    </div>
    <?php Pjax::end(); ?>
</div>

<?php Modal::begin([
    'id' => 'subscribersImport',
    'header' => '<h4 class="modal-title">'.Yii::t('app/modules/subscribers', 'Subscribers import').'</h4>',
]); ?>
<?php echo $this->render('../all/_import', ['model' => $importModel]); ?>
<?php Modal::end(); ?>

<?php Modal::begin([
    'id' => 'subscribersExport',
    'header' => '<h4 class="modal-title">'.Yii::t('app/modules/subscribers', 'Subscribers export').'</h4>',
]); ?>
<?php echo $this->render('../all/_export', ['model' => $exportModel]); ?>
<?php Modal::end(); ?>

<?php echo $this->render('../_debug'); ?>
