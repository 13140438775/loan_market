<?php

namespace frontend\controllers;

use common\models\ChannelAccount;
use frontend\models\GetChannelDataSearch;
use Yii;
use common\models\ChannelData;
use frontend\models\ChannelDataSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ChannelDataController implements the CRUD actions for ChannelData model.
 */
class ChannelDataController extends Controller
{
    /**
     * {@inheritdoc}
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
    private function isIncludeToday($begin=null,$end=null){
        if($begin === null && $end === null){
            return false;
        }
        $today = strtotime(date('Y-m-d'));
        if(strtotime($begin) <= $today && strtotime($end) >= $today){
            return true;
        }
        return false;
    }
    /**
     * Lists all ChannelData models.
     * @return mixed
     */
    public function actionIndex()
    {
        $temp = \Yii::$app->request->queryParams;
        if(isset($temp['r'])) unset($temp['r']);
        $params = array_values($temp);
        $data = [];
        foreach ($params AS $val) {
            if(is_array($val)) $data = $val;
        }

        $res = empty($data) || $data['date_begin'] == date("Y-m-d") || $data['date_end'] == date("Y-m-d") || ($data['date_begin'] == "默认显示当天数据" && $data['date_end'] == "默认显示当天数据");

        if($this->isIncludeToday($data['date_begin']??null,$data['date_end']??null)){
            Yii::$app->session->setFlash ( 'warning' ,'按时间范围查询只能查今天以前的数据',true);
            return $this->redirect(['index']);
        }

        if($res) {
            $searchModel = new GetChannelDataSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else {
            $params = [
                'ChannelDataSearch' => $data
            ];
            $searchModel = new ChannelDataSearch();
            $dataProvider = $searchModel->search($params);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single ChannelData model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ChannelData model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ChannelData();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ChannelData model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ChannelData model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ChannelData model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ChannelData the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ChannelData::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
