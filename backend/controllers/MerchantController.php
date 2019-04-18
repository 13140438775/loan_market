<?php

namespace backend\controllers;

use common\models\MerchantContacts;
use common\models\mk\MkMerchantContacts;
use yii\web\Response;
use Yii;
use common\models\Merchant;
use backend\models\MerchantSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MerchantController implements the CRUD actions for Merchant model.
 */
class MerchantController extends Controller
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
     * Lists all Merchant models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MerchantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Merchant model.
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
     * Creates a new Merchant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Merchant();
        $contactModels = [];
        $contactModels[]= new MerchantContacts();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save()) {
            $contactModels = [];
            $metaDataContacts['MerchantContacts'] = [];
            foreach (Yii::$app->request->post()['MerchantContacts'] as $data) {
                $metaDataContacts['MerchantContacts'][] = $data;
                $contactModels[] = new MerchantContacts([],['merchant_id'=>$model->id]);
            }
            if(MerchantContacts::loadMultiple($contactModels,$metaDataContacts) && MerchantContacts::validateMultiple($contactModels)){
                foreach ($contactModels as $contactModel){
                    $contactModel->save();
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'contactModels' => $contactModels
            ]);
        }
    }

    /**
     * Updates an existing Merchant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $contactModels= $model->getMerchantContacts();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save()) {
            $contactModels = [];
            $metaDataContacts['MerchantContacts'] = [];
            foreach (Yii::$app->request->post()['MerchantContacts'] as $data) {
                $metaDataContacts['MerchantContacts'][] = $data;
                $contactModels[] = new MerchantContacts([],['merchant_id'=>$model->id]);
            }
            if(MerchantContacts::loadMultiple($contactModels,$metaDataContacts) && MerchantContacts::validateMultiple($contactModels)){
                MerchantContacts::deleteAll(['merchant_id'=>$model->id]);
                foreach ($contactModels as $contactModel){
                    $contactModel->save();
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'contactModels' => $contactModels
            ]);
        }

//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            return $this->render('update', [
//                'model' => $model,
//            ]);
//        }
    }

    /**
     * Deletes an existing Merchant model.
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
     * Finds the Merchant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Merchant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Merchant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionList($q)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Merchant::find()->select(['id', 'text'=>'company_name'])->andFilterWhere(['company_name' => $q])->asArray()->all();
        return [
            'results' => $data
        ];
    }
}
