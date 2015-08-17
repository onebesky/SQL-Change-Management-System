<?php

namespace backend\controllers;

use Yii;
use common\models\Command;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\TaskExecution;

/**
 * CommandController implements the CRUD actions for Command model.
 */
class CommandController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Command models.
     * @return mixed
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => Command::find(),
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Command model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        $resultDataProvider = new ActiveDataProvider([
            'query' => TaskExecution::find()->where(['command_id' => $id]),
            'sort' => [
                'defaultOrder' => [
                    'execution_start' => SORT_DESC
                ]
            ],
        ]);
        return $this->render('view', [
                    'model' => $model,
                    'results' => $resultDataProvider
        ]);
    }

    /**
     * Creates a new Command model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Command();
        $model->author = \Yii::$app->user->identity->id;

        // check that at least one server connection exists
        $connection = \common\models\ServerConnection::find()->asArray()->one();
        if (!$connection) {
            \Yii::$app->getSession()->setFlash('error', 'Command requires functional server connection.');
            $this->redirect(['/server-connection']);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Command model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            d(Yii::$app->request->post());
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Command model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Command model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Command the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Command::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionExecute($id) {
        $command = $this->findModel($id);

        $userId = Yii::$app->user->identity->id;
        \d("user", $userId);
        
        if (!$command->canExecute()) {
            throw new \yii\web\HttpException("The command requires approval before execution.");
        }

        $task = $command->execute($userId);
        \d($task->attributes);
        if (Yii::$app->request->isAjax) {
            $this->render('task_view', ['model' => $task]);
        } else {
            echo "1";
        }
    }
    
    /**
     * View task execution result
     * @param string $id
     */
    public function actionTask($id) {
        if (($model = TaskExecution::findOne($id)) == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $this->render('task_view', ['model' => $model]);
    }

}
