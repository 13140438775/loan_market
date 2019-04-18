<?php

namespace backend\controllers;

use Yii;
use common\models\ChannelConfig;
use backend\models\ChannelConfigSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ChannelConfigController implements the CRUD actions for ChannelConfig model.
 */
class ChannelConfigController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ChannelConfig models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ChannelConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ChannelConfig model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ChannelConfig model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ChannelConfig();

        if ($model->load(Yii::$app->request->post())) {
            $model->delivery_terminal = 1000000000;
            if(empty(Yii::$app->request->post()['android']) || empty(Yii::$app->request->post()['ios'])) throw  new \Exception('请选择投放端');
            if(Yii::$app->request->post()['android'][0]) $model->delivery_terminal = $this->bit_grinding($model->delivery_terminal,Yii::$app->request->post()['android'][0]);
            if(Yii::$app->request->post()['ios']) $model->delivery_terminal = $this->bit_grinding($model->delivery_terminal,Yii::$app->request->post()['ios']);
            $res = $model->save();
            if(!$res) Yii::$app->session->setFlash ( 'error' , $model->getErrors());
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ChannelConfig model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->delivery_terminal = 1000000000;
            if(empty(Yii::$app->request->post()['android']) || empty(Yii::$app->request->post()['ios'])) throw  new \Exception('请选择投放端');
            if(Yii::$app->request->post()['android'][0]) $model->delivery_terminal = $this->bit_grinding($model->delivery_terminal,Yii::$app->request->post()['android'][0]);
            if(Yii::$app->request->post()['ios']) $model->delivery_terminal = $this->bit_grinding($model->delivery_terminal,Yii::$app->request->post()['ios']);
            $res = $model->save();
            if(!$res) Yii::$app->session->setFlash ( 'error' , $model->getErrors());
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ChannelConfig model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ChannelConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ChannelConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ChannelConfig::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function bit_grinding($str,$val){
        $str = strval($str) | strval($val);
        return intval($str);
    }

    public function actionOffline($id){
        $model = $this->findModel($id);
        switch ($model->status){
            case 1;
                $model->status = 2;
                break;
            case 2;
                $model->status = 3;
                break;
            default:
                throw  new \Exception('无效操作');
        }
        $res = $model->save(false);
        if(!$res) Yii::$app->session->setFlash ( 'error' , $model->getErrors());
        return $this->redirect(['index', 'id' => $model->id]);
    }
}
