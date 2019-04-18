<?php

namespace backend\controllers;

use Yii;
use common\models\ChannelAccount;
use backend\models\ChannelAccountSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ChannelAccountController implements the CRUD actions for ChannelAccount model.
 */
class ChannelAccountController extends Controller
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
     * Lists all ChannelAccount models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ChannelAccountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ChannelAccount model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /*
     * ajax获取渠道名称
     */
    public function actionChannelName(){
        $model = new ChannelAccount();
        $post = Yii::$app->request->post();

        if($post){
            if($post['channel_id']){
                return $model->getChannel($post['channel_id']);
            }
        }
    }

    /*
     * 创建渠道账户
     */
    public function actionChannelAccountCreate(){
        $model = new ChannelAccount();
        $post = Yii::$app->request->post();

        //验证渠道id唯一性
        if(!$model->isUnique($post)){
            echo "<script>alert('有重复提交的渠道ID');history.go(-1);</script>";
            exit;
        }

        $model->setChannelAccount($post);

        return $this->redirect(['channel-account/index']);
    }

    /*
     * 修改账户状态
     */
    public function actionChangeStatus(){
        $model = new ChannelAccount();
        $post = Yii::$app->request->post();

        $model->setStatus($post);
    }

    /*
     * 修改渠道状态
     */
    public function actionChangeAssocStatus(){
        $model = new ChannelAccount();
        $post = Yii::$app->request->post();

        $model->setAssocStatus($post);

        return true;
    }

    /*
     * 新增账户渠道
     */
    public function actionAddChannelAssocAccount($id){
        $model = new ChannelAccount();

        return $this->render('AddChannelAssocAccount', [
            'model' => $this->findModel($id),
        ]);
    }

    /*
     * 新增账户渠道
     */
    public function actionDoChannelAssocAccount(){
        $model = new ChannelAccount();
        $post = Yii::$app->request->post();

        //验证渠道id唯一性
        if(!$model->isUnique($post)){
            echo "<script>alert('有重复提交的渠道ID');history.go(-1);</script>";
            exit;
        }

        $model->changeChannelAccount($post);

        return $this->redirect(['channel-account/update', 'id' => $post['id']]);
    }

    /**
     * Creates a new ChannelAccount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ChannelAccount();
        $post = Yii::$app->request->post();

        if ($model->load($post) && $model->save() && $model->doPassword($post['ChannelAccount']['password'])) {
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ChannelAccount model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $post = Yii::$app->request->post();

        if($post){
            if($post['username']){
                $model->username = $post['username'];
                $model->save();
            }

            if($post['password']){
                $model->password = $post['password'];
                $model->doPassword($model->password);
            }

            if($post['mobile']){
                $model->mobile = $post['mobile'];
                $model->save();
            }

            if(isset($post['uv_show'])){
                foreach($post['uv_show'] as $key => $item){
                    $model->setUVShow($key, $item);
                }
            }
            if(isset($post['uv_coefficient'])) {
                foreach ($post['uv_coefficient'] as $key => $item) {
                    $model->setUVCoefficient($key, $item);
                }
            }

            if(isset($post['register_show'])) {
                foreach ($post['register_show'] as $key => $item) {
                    $model->setRegisterShow($key, $item);
                }
            }

            if(isset($post['register_coefficient'])) {
                foreach ($post['register_coefficient'] as $key => $item) {
                    $model->setRegisterCoefficient($key, $item);
                }
            }

            if(isset($post['login_show'])) {
                foreach ($post['login_show'] as $key => $item) {
                    $model->setLoginShow($key, $item);
                }
            }

            if(isset($post['login_coefficient'])) {
                foreach ($post['login_coefficient'] as $key => $item) {
                    $model->setLoginCoefficient($key, $item);
                }
            }

            return $this->render('index', [
                'model' => $model,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ChannelAccount model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ChannelAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ChannelAccount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ChannelAccount::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
