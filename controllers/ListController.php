<?php

namespace wdmg\subscribers\controllers;

use Yii;
use wdmg\subscribers\models\SubscribersList;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ListController implements the CRUD actions for Subscribers model.
 */
class ListController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public $defaultAction = 'index';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'roles' => ['admin'],
                        'allow' => true
                    ],
                ],
            ]
        ];

        // If auth manager not configured use default access control
        if(!Yii::$app->authManager) {
            $behaviors['access'] = [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'roles' => ['@'],
                        'allow' => true
                    ],
                ]
            ];
        }

        return $behaviors;
    }

    /**
     * Lists all Subscribers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubscribersList();
        $dataProvider = new ActiveDataProvider([
            'query' => $searchModel::find(),
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView()
    {
        return $this->run('index');
    }

    public function actionUpdate()
    {
        return $this->run('index');
    }

    public function actionCreate()
    {
        return $this->run('index');
    }

    public function actionDelete()
    {
        return $this->run('index');
    }

    /**
     * Finds the Subscribers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActiveRecord model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Subscribers::findOne($id)) !== null)
            return $model;

        throw new NotFoundHttpException(Yii::t('app/modules/subscribers', 'The requested page does not exist.'));
    }
}
