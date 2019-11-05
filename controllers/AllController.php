<?php

namespace wdmg\subscribers\controllers;

use Yii;
use wdmg\subscribers\models\Subscribers;
use wdmg\subscribers\models\SubscribersSearch;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AllController implements the CRUD actions for Subscribers model.
 */
class AllController extends Controller
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
        $searchModel = new SubscribersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save())
                Yii::$app->getSession()->setFlash(
                    'success',
                    Yii::t('app/modules/subscribers', 'Subscriber has been successfully updated!')
                );
            else
                Yii::$app->getSession()->setFlash(
                    'danger',
                    Yii::t('app/modules/subscribers', 'An error occurred while updating the subscriber.')
                );

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionCreate()
    {
        $model = new Subscribers();

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save())
                Yii::$app->getSession()->setFlash(
                    'success',
                    Yii::t('app/modules/subscribers', 'Subscriber has been successfully added!')
                );
            else
                Yii::$app->getSession()->setFlash(
                    'danger',
                    Yii::t('app/modules/subscribers', 'An error occurred while add the subscriber.')
                );

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete())
            Yii::$app->getSession()->setFlash(
                'success',
                Yii::t('app/modules/subscribers', 'Subscriber has been successfully deleted!')
            );
        else
            Yii::$app->getSession()->setFlash(
                'danger',
                Yii::t('app/modules/subscribers', 'An error occurred while deleting the subscriber.')
            );

        return $this->redirect(['index']);
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
