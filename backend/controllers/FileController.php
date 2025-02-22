<?php
/**
 * Created by PhpStorm.
 * User: suns
 * Date: 2019/1/30
 * Time: 4:39 PM
 */

namespace backend\controllers;


use backend\models\form\UpLoadImageForm;
use yii\web\Response;
use yii\web\Controller;
use yii\web\UploadedFile;
use Yii;

class FileController extends Controller
{
    public function beforeAction($action)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }
    public function actionUploadImage(){
        $model = new UpLoadImageForm();
        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($file = $model->upload()) {
                return [
                    'url'=> Yii::$app->params['oss']['url_prefix'].$file,
                    'path' => $file
                ];
            }
        }
        return ['error' => 'server error'];
    }
}