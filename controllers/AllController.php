<?php

namespace wdmg\subscribers\controllers;

use Yii;
use wdmg\subscribers\models\Subscribers;
use wdmg\subscribers\models\SubscribersSearch;
use wdmg\subscribers\models\SubscribersImport;
use wdmg\subscribers\models\SubscribersExport;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

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
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'clear' => ['POST'],
                    'import' => ['POST'],
                    'export' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
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
                'class' => AccessControl::class,
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
        $importModel = new SubscribersImport();
        $exportModel = new SubscribersExport();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'importModel' => $importModel,
            'exportModel' => $exportModel,
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

    public function actionCreate()
    {
        $model = new Subscribers();

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                // Log activity
                if (
                    class_exists('\wdmg\activity\models\Activity') &&
                    $this->module->moduleLoaded('activity') &&
                    isset(Yii::$app->activity)
                ) {
                    Yii::$app->activity->set(
                        'New subscriber `' . $model->email . '` with ID `' . $model->id . '` has been successfully added.',
                        $this->uniqueId . ":" . $this->action->id,
                        'success',
                        1
                    );
                }

                Yii::$app->getSession()->setFlash(
                    'success',
                    Yii::t('app/modules/subscribers', 'Subscriber has been successfully added!')
                );
            } else {
                // Log activity
                if (
                    class_exists('\wdmg\activity\models\Activity') &&
                    $this->module->moduleLoaded('activity') &&
                    isset(Yii::$app->activity)
                ) {
                    Yii::$app->activity->set(
                        'An error occurred while add the new subscriber: ' . $model->email,
                        $this->uniqueId . ":" . $this->action->id,
                        'danger',
                        1
                    );
                }

                Yii::$app->getSession()->setFlash(
                    'danger',
                    Yii::t('app/modules/subscribers', 'An error occurred while add the subscriber.')
                );
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                // Log activity
                if (
                    class_exists('\wdmg\activity\models\Activity') &&
                    $this->module->moduleLoaded('activity') &&
                    isset(Yii::$app->activity)
                ) {
                    Yii::$app->activity->set(
                        'Subscriber `' . $model->email . '` with ID `' . $model->id . '` has been successfully updated.',
                        $this->uniqueId . ":" . $this->action->id,
                        'success',
                        1
                    );
                }

                Yii::$app->getSession()->setFlash(
                    'success',
                    Yii::t('app/modules/subscribers', 'Subscriber has been successfully updated!')
                );
            } else {
                // Log activity
                if (
                    class_exists('\wdmg\activity\models\Activity') &&
                    $this->module->moduleLoaded('activity') &&
                    isset(Yii::$app->activity)
                ) {
                    Yii::$app->activity->set(
                        'An error occurred while update the subscriber `' . $model->email . '` with ID `' . $model->id . '`.',
                        $this->uniqueId . ":" . $this->action->id,
                        'danger',
                        1
                    );
                }

                Yii::$app->getSession()->setFlash(
                    'danger',
                    Yii::t('app/modules/subscribers', 'An error occurred while updating the subscriber.')
                );
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            // Log activity
            if (
                class_exists('\wdmg\activity\models\Activity') &&
                $this->module->moduleLoaded('activity') &&
                isset(Yii::$app->activity)
            ) {
                Yii::$app->activity->set(
                    'Subscriber `' . $model->name . '` with ID `' . $model->id . '` has been successfully deleted.',
                    $this->uniqueId . ":" . $this->action->id,
                    'success',
                    1
                );
            }

            Yii::$app->getSession()->setFlash(
                'success',
                Yii::t('app/modules/subscribers', 'Subscriber has been successfully deleted!')
            );
        } else {
            // Log activity
            if (
                class_exists('\wdmg\activity\models\Activity') &&
                $this->module->moduleLoaded('activity') &&
                isset(Yii::$app->activity)
            ) {
                Yii::$app->activity->set(
                    'An error occurred while deleting the subscriber `' . $model->name . '` with ID `' . $model->id . '`.',
                    $this->uniqueId . ":" . $this->action->id,
                    'danger',
                    1
                );
            }

            Yii::$app->getSession()->setFlash(
                'danger',
                Yii::t('app/modules/subscribers', 'An error occurred while deleting the subscriber.')
            );
        }

        return $this->redirect(['index']);
    }

    public function actionImport()
    {
        if (Yii::$app->request->isPost) {
            $model = new SubscribersImport();
            $import = UploadedFile::getInstance($model, 'import');
            if (!is_null($import)) {
                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    if (($handle = fopen($import->tempName, 'r')) !== false) {

                        $subscribers = \wdmg\helpers\ArrayHelper::importCSV($handle, ';', true);
                        fclose($handle);

                        if ($count = $model->import($subscribers, $model->list_id)) {
                            // Log activity
                            if (
                                class_exists('\wdmg\activity\models\Activity') &&
                                $this->module->moduleLoaded('activity') &&
                                isset(Yii::$app->activity)
                            ) {
                                if (isset($model->list_id)) {
                                    Yii::$app->activity->set(
                                        $count . ' subscribers has been successfully imported to list ID `' . $model->list_id . '`.',
                                        $this->uniqueId . ":" . $this->action->id,
                                        'success',
                                        1
                                    );
                                } else {
                                    Yii::$app->activity->set(
                                        $count . ' subscribers has been successfully imported.',
                                        $this->uniqueId . ":" . $this->action->id,
                                        'success',
                                        1
                                    );
                                }
                            }

                            Yii::$app->getSession()->setFlash(
                                'success',
                                Yii::t('app/modules/subscribers', 'Ok! {count, plural, one{# subscriber} few{# subscribers} many{# subscribers} other{# subscribers}} have been successfully imported.', ['count' => $count])
                            );
                        } else {
                            // Log activity
                            if (
                                class_exists('\wdmg\activity\models\Activity') &&
                                $this->module->moduleLoaded('activity') &&
                                isset(Yii::$app->activity)
                            ) {
                                if (isset($model->list_id)) {
                                    Yii::$app->activity->set(
                                        'An error occurred while importing the subscribers.',
                                        $this->uniqueId . ":" . $this->action->id,
                                        'danger',
                                        1
                                    );
                                } else {
                                    Yii::$app->activity->set(
                                        'An error occurred while importing the subscribers to list ID `' . $model->list_id . '`.',
                                        $this->uniqueId . ":" . $this->action->id,
                                        'danger',
                                        1
                                    );
                                }
                            }

                            Yii::$app->getSession()->setFlash(
                                'danger',
                                Yii::t('app/modules/subscribers', 'An error occurred while importing subscribers.')
                            );
                        }
                    }
                }
            }
        }

        $this->redirect(['all/index']);
    }

    public function actionExport()
    {
        if (Yii::$app->request->isPost) {
            $model = new SubscribersExport();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($subscribers = $model->export($model->list_id)) {
                    $filename = 'subscribers_' . date('dmY_His') . '.csv';
                    if ($output = \wdmg\helpers\ArrayHelper::exportCSV($subscribers, ['name', 'email'], ";", true)) {
                        // Log activity
                        if (
                            class_exists('\wdmg\activity\models\Activity') &&
                            $this->module->moduleLoaded('activity') &&
                            isset(Yii::$app->activity)
                        ) {
                            if (isset($model->list_id)) {
                                Yii::$app->activity->set(
                                    'Subscribers from list ID `' . $model->list_id . '` has been successfully exported.',
                                    $this->uniqueId . ":" . $this->action->id,
                                    'success',
                                    1
                                );
                            } else {
                                Yii::$app->activity->set(
                                    'All subscribers has been successfully exported.',
                                    $this->uniqueId . ":" . $this->action->id,
                                    'success',
                                    1
                                );
                            }
                        }

                        Yii::$app->response->sendContentAsFile($output, $filename, [
                            'mimeType' => 'text/csv',
                            'inline' => false
                        ])->send();
                    }
                }
            } else {
                // Log activity
                if (
                    class_exists('\wdmg\activity\models\Activity') &&
                    $this->module->moduleLoaded('activity') &&
                    isset(Yii::$app->activity)
                ) {
                    Yii::$app->activity->set(
                        'An error occurred while export the subscribers.',
                        $this->uniqueId . ":" . $this->action->id,
                        'danger',
                        1
                    );
                }
            }
        }
        $this->redirect(['all/index']);
    }

    public function actionClear()
    {
        if (Yii::$app->request->isPost) {
            $model = new Subscribers();
            if ($model->deleteAll()) {
                // Log activity
                if (
                    class_exists('\wdmg\activity\models\Activity') &&
                    $this->module->moduleLoaded('activity') &&
                    isset(Yii::$app->activity)
                ) {
                    Yii::$app->activity->set(
                        'All subscribers has been successfully deleted.',
                        $this->uniqueId . ":" . $this->action->id,
                        'success',
                        1
                    );
                }

                Yii::$app->getSession()->setFlash(
                    'success',
                    Yii::t('app/modules/subscribers', 'All subscribers have been successfully deleted!')
                );
            } else {
                // Log activity
                if (
                    class_exists('\wdmg\activity\models\Activity') &&
                    $this->module->moduleLoaded('activity') &&
                    isset(Yii::$app->activity)
                ) {
                    Yii::$app->activity->set(
                        'An error occurred while deleting subscribers.',
                        $this->uniqueId . ":" . $this->action->id,
                        'danger',
                        1
                    );
                }

                Yii::$app->getSession()->setFlash(
                    'danger',
                    Yii::t('app/modules/subscribers', 'An error occurred while deleting subscribers.')
                );
            }
        }
        $this->redirect(['all/index']);
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
