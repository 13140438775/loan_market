<?php

namespace backend\controllers;

use backend\models\DataStatsSearch;
use common\models\ProductDailyData;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductDailyDataController implements the CRUD actions for ProductDailyData model.
 */
class DataViewController extends Controller
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
     * Lists all ProductDailyData models.
     * @return mixed
     */
    public function actionIndex()
    {
        $data_stats = new DataStatsSearch();

        $data_view = $data_stats->getSearchResult(\Yii::$app->request->queryParams);
        return $this->render('index', [
            'data_view' => $data_view
        ]);
    }

    /**
     * Finds the ProductDailyData model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductDailyData the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductDailyData::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
