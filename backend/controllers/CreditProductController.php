<?php

namespace backend\controllers;

use common\models\ProductAsocTag;
use common\models\ProductTag;
use Yii;
use common\models\CreditProduct;
use backend\models\CreditProductSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * CreditProductController implements the CRUD actions for CreditProduct model.
 */
class CreditProductController extends Controller
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

    /**
     * Lists all CreditProduct models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CreditProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CreditProduct model.
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
     * Creates a new CreditProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CreditProduct();
        if ($model->load(Yii::$app->request->post())) {
            $transaction = CreditProduct::getDb()->beginTransaction();
            try {
                $model->up_time = strtotime($model->up_time) ? strtotime($model->up_time) : 0;
                $model->logo_url = $model->logo_url ? $model->logo_url : '';
                $model->product_type = $model->product_type ? $model->product_type : 0;
                $model->is_valid = $model->is_valid ? $model->is_valid : 1;
                $model->tag_id = $model->tag_id ? $model->tag_id : 0;
                $model->uv_limit = $model->uv_limit ? $model->uv_limit : 0;
                $model->sort = $model->sort ? $model->sort : 0;
                $model->apply_materia = json_encode($model->apply_materia, true);
                if ($model->save() === false) {
                    throw new \Exception('save credit_product fail'.print_r($model->getErrors(), 1));
                }
                foreach (ProductTag::findAll(['id' => Yii::$app->request->post()['CreditProduct']['tagIds']]) as $tag) {
                    $model->link('tags',$tag);
                }
                $transaction->commit();
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                $transaction->rollBack();
//                throw $e;
                Yii::$app->session->setFlash ( 'error' , $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CreditProduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $transaction = CreditProduct::getDb()->beginTransaction();
            try {
                $model->up_time = strtotime($model->up_time);
                if ($model->save() === false) {
                    throw new \Exception('save credit_product fail');
                }
                if(empty(Yii::$app->request->post()['CreditProduct']['tagIds'])){
                    throw  new \Exception('请选择筛选标签');
                }
                if (array_diff($model->tagIds, Yii::$app->request->post()['CreditProduct']['tagIds']) || array_diff(Yii::$app->request->post()['CreditProduct']['tagIds'],$model->tagIds)) {
                    foreach ($model->tags as $tag) {
                        $model->unlink('tags', $tag, true);
                    }
                    foreach (ProductTag::findAll(['id' => Yii::$app->request->post()['CreditProduct']['tagIds']]) as $tag) {
                        $model->link('tags',$tag);
                    }
                }
                $transaction->commit();
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash ( 'error' , $e->getMessage());
            }
        }


        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionList($q)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = CreditProduct::find()->select(['id', 'text'=>'product_name'])->andFilterWhere(['id' => $q])->asArray()->all();
        return [
            'results' => $data
        ];
    }

    /**
     * Deletes an existing CreditProduct model.
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
     * Finds the CreditProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CreditProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CreditProduct::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
