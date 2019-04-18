<?php

namespace backend\controllers;

use Yii;
use common\models\HandFillTerm;
use backend\models\HandFillTermSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HandFillTermController implements the CRUD actions for HandFillTerm model.
 */
class HandFillTermController extends Controller
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
     * Lists all HandFillTerm models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HandFillTermSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HandFillTerm model.
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
     * Creates a new HandFillTerm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new HandFillTerm();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'jsonString' => ''
            ]);
        }
    }

    public function actionTrans(){
        try{
            $text = Yii::$app->request->post('text');
            $s1 = str_replace([':','：','。'],'.',$text);
            $s2 = str_replace([',','，',';'],',',$s1);
            $arr = explode(',',$s2);
            $result = [];
            foreach ($arr as $item){
                $arr = explode('.',$item);
                $result[$arr[0]] = $arr[1];
            }
            $result = json_encode($result,JSON_UNESCAPED_UNICODE);
        }catch (\Exception $e){
            $result = '数据格式异常'.$e->getMessage();
        }


        return $this->render('trans', [
            'jsonString' => $result
        ]);
    }

    /**
     * Updates an existing HandFillTerm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['hand-fill-term/index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'jsonString' => ''

            ]);
        }
    }


    /**
     * Deletes an existing HandFillTerm model.
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
     * Finds the HandFillTerm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HandFillTerm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HandFillTerm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
