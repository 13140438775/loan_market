<?php

namespace backend\controllers;

use common\models\HandFillTerm;
use common\models\ProductApiConfig;
use common\models\ProductAssocTag;
use common\models\ProductAuthConfig;
use common\models\ProductHandFillConfig;
use common\models\ProductPlatLimit;
use common\models\ProductProperty;
use common\models\ProductTag;
use common\models\ProductTermDetail;
use common\services\HelpService;
use Yii;
use common\models\Product;
use backend\models\ProductSearch;
use yii\bootstrap\Modal;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * MkProductController implements the CRUD actions for MkProduct model.
 */
class ProductController extends Controller
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
     * Lists all MkProduct models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Lists all MkProduct models.
     * @return mixed
     */
    public function actionOperation()
    {
        $model = new Product();
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('operation', [
//            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }


    /**
     * Displays a single MkProduct model.
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
     * Creates a new MkProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateH5()
    {
        $model = new Product();
        $model->setScenario(Product::SCENARIO_API_EDIT);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create-h5', [
                'model' => $model,
            ]);
        }
    }


    public function actionUpdateFee($id)
    {
        $model = $this->findModel($id);
        if($model->call_type == 1){
            Yii::$app->session->setFlash('H5不能配置费率');
            return $this->redirect(['product/index']);
        }
        $productTermDetailModels = ProductTermDetail::find()->where(['product_id' => $model->id])->asArray()->all();
        $model->setScenario(Product::SCENARIO_FEE_CONFIG_EDIT);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $trans = Yii::$app->db->beginTransaction();
            try{

                if($model->save() === false){
                    throw new Exception('更新product失败');
                }
                ProductTermDetail::deleteAll(['product_id' =>$model->id]);
                foreach (Yii::$app->request->post()['ProductTermDetail'] as $item){
                    $temp = new ProductTermDetail();
                    $temp->attributes = $item;
                    $temp->product_id = $model->id;
                    if($temp->save() === false){
                        throw new Exception('期限费率详情保存失败'.print_r($temp->getErrors(),1));
                    }
                }
                $trans->commit();
                Yii::$app->session->setFlash('success','保存成功');
            }catch (\Exception $e){
                $trans->rollBack();
                Yii::$app->session->setFlash('error',$e->getMessage());
            }
            return $this->redirect(['update-fee', 'id' => $model->id]);
        } else {

            return $this->render('update-fee', [
                'model' => $model,
                'productTermDetailModels' => json_encode($productTermDetailModels)
            ]);
        }
    }

    public function actionUpdateFeeValidate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario(Product::SCENARIO_FEE_CONFIG_EDIT);
        $model->load(Yii::$app->request->post());
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($model);
    }

    /**
     *认证项配置
     */
    public function actionUpdateAuth($id)
    {
        $model = $this->findModel($id);
        if($model->call_type == 1){
            Yii::$app->session->setFlash('error','H5不能配置认证项');
            return $this->redirect(['product/index']);
        }

        if (Yii::$app->request->post()) {
            $trans = Yii::$app->db->beginTransaction();
            try{
                foreach (Yii::$app->request->post()['ProductAuthConfig'] as $index =>  $item){
                    if(isset($item['id']) && $item['id'] !== ''){
                        $one = ProductAuthConfig::findOne($item['id']);
                        $one->attributes = $item;
                    }else{
                        unset($item['id']);
                        $one = new ProductAuthConfig();
                        $one->load($item,'');
                        $one->product_id = $model->id;
                    }
                    if($one->save()===false){
                        throw new Exception('保存'.print_r($item,1).'失败');
                    }
                }
                $trans->commit();
            }catch (\Exception $e){
                $trans->rollBack();
                Yii::$app->session->setFlash('error',$e->getMessage());
                return $this->redirect(['product/update-auth', 'id' => $model->id]);
            }
            Yii::$app->session->setFlash('success','编辑成功');
            return $this->redirect(['product/update-auth', 'id' => $model->id]);
        } else {
            return $this->render('update-auth', [
                'model' => $model,
                'authConfig' => json_encode(ProductAuthConfig::getProductAuthConfig($id)),
            ]);
        }
    }

    /**
     *认证项配置
     */
    public function actionHandFillConfig($id)
    {
        $model = $this->findModel($id);
        if($model->call_type == 1){
            Yii::$app->session->setFlash('error','H5没有手填项');
            return $this->redirect(['product/index']);
        }

        if (Yii::$app->request->post()) {
            $model->setScenario(Product::SCENARIO_HAND_FILL_CONFIG);
            $model->load(Yii::$app->request->post());
            $trans = Yii::$app->db->beginTransaction();
            try{
                if($model->save() === false){
                    throw new Exception('保存Product失败');
                }
                ProductHandFillConfig::deleteAll(['product_id' => $model]);
                foreach (Yii::$app->request->post()['ProductHandFillConfig'] as $item){
                    $line = new ProductHandFillConfig();
                    $line->term_id = $item['id'];
                    $line->options = isset($item['options'])? json_encode($item['options']) : '';
                    $line->product_id = $model->id;
                    if($line->save() === false){
                        throw new Exception('保存失败'.print_r($line->getErrors(),1));
                    }
                }
                $trans->commit();
            }catch (\Exception $e){
                $trans->rollBack();
                Yii::$app->session->setFlash('error',$e->getMessage());
                return $this->redirect(['product/hand-fill-config', 'id' => $model->id]);
            }
            Yii::$app->session->setFlash('success','编辑成功');
            return $this->redirect(['product/hand-fill-config', 'id' => $model->id]);
        } else {
            return $this->render('update-hand-fill-config', [
                'model' => $model,
                'allTerms' => json_encode(HandFillTerm::getAllHandTerms()),
                'selectedTerms' => json_encode(ProductHandFillConfig::getSelectedTerms($id)),
                'allCareerTerms' =>  json_encode(HandFillTerm::getCareerTerms()),
                'selectedCareer' => $model->is_career_auto ? json_encode(ProductHandFillConfig::getSelectedCareer($id)) : json_encode([])//获取选中的职业类型
            ]);
        }
    }

    /**
     * Creates a new MkProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateApi()
    {
        $model = new Product();
        $model->setScenario(Product::SCENARIO_API_EDIT);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create-api', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing MkProduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->del_cache($id);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MkProduct model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $this->del_cache($id);
        return $this->redirect(['index']);
    }

    /**
     * actionApiConfig
     * @date     2019/3/21 14:02
     * @author   jixiaoyong<810964998@qq.com>
     * @param $id
     * @return string|Response
     */
    public function actionApiConfig($id){
        $model = $this->getProductApi($id);
        $model->setScenario(ProductApiConfig::SCENARIO_API_CONFIG);
        if ($model->load(Yii::$app->request->post()) ) {
            $model->product_id = $id;
            $res = $model->save();
            if(!$res){
                Yii::$app->session->setFlash ( 'error' , $model->getErrors());
            }
            $this->del_cache($id);
            return $this->redirect(['index', 'id' => $id]);
        } else {
            return $this->render('api_config', [
                'model' => $model,
            ]);
        }
    }

    /**
     * getProductApi   获取产品API配置
     * @date     2019/3/18 11:26
     * @author   jixiaoyong<810964998@qq.com>
     * @param $id
     * @return array|ProductApiConfig|\yii\db\ActiveRecord|null
     */
    public function getProductApi($id){
        if (($model = ProductApiConfig::find()->where(['product_id'=>$id])->one()) !== null) {
            return $model;
        } else {
            $model = New ProductApiConfig();
            return $model;
        }
    }

    /**
     * getProperty     获取产品属性
     * @date     2019/3/18 11:49
     * @author   jixiaoyong<810964998@qq.com>
     * @param $id
     * @return array|ProductProperty|\yii\db\ActiveRecord|null
     */
    public function getProperty($id){
        if (($model = ProductProperty::find()->where(['product_id'=>$id])->one()) !== null) {
            return $model;
        } else {
            $model = New ProductProperty();
            return $model;
        }
    }

    /**
     * Finds the MkProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findTagModel()
    {
        if (($model = ProductTag::find()->all()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * actionUpdateWeight
     * @date     2019/3/18 11:26
     * @author   jixiaoyong<810964998@qq.com>
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdateWeight($id){
        $model = $this->findModel($id);
        $model->setScenario(Product::SCENARIO_SET_WEIGHT);
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save()) {
            $this->del_cache($id);
            return $this->redirect(['product/operation']);
        } else {
            return $this->renderAjax('update_weight', [
                'model' => $model,
            ]);
        }
    }

    /**
     * actionSetOnlineConfig
     * @date     2019/3/18 11:28
     * @author   jixiaoyong<810964998@qq.com>
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionSetOnlineConfig($id){
        $model = $this->findModel($id);
        $model->setScenario(Product::SCENARIO_SET_ONLINE_CONFIG);
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save()) {
            $this->del_cache($id);
            return $this->redirect(['product/operation']);
        } else {
            return $this->renderAjax('set_online_config', [
                'model' => $model,
            ]);
        }
    }

    /**
     * actionSetTagConfig
     * @date     2019/3/18 11:28
     * @author   jixiaoyong<810964998@qq.com>
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionSetTagConfig($id){
        $model = $this->findModel($id);
        $model->setScenario(Product::SCENARIO_SET_ONLINE_CONFIG);
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $transaction = $model::getDb()->beginTransaction();
            try {
                $model->show_tag_id = Yii::$app->request->post()['Product']['show_tag_id'];
                $model->save();
                if ($model->save() === false) {
                    throw new \Exception('save show_tag_id fail');
                }
                $item = Yii::$app->request->post()['Product']['tagIds'];
                if(empty($item)){
                    throw  new \Exception('请选择筛选标签');
                }
                $tag_model = ProductAssocTag::find()->where(['product_id'=>$model->id])->all();
                if(!is_null($tag_model)) ProductAssocTag::deleteAll();
                $key = ['product_id','tag_id'];
                $data = [];
                for(reset($item);$tag_id = current($item);next($item)){
                    $data[] = ['product_id'=>$model->id,'tag_id'=>$tag_id];
                }
                Yii::$app->db->createCommand()->batchInsert(ProductAssocTag::tableName(), $key, $data)->execute();
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash ( 'error' , $e->getMessage());
            }
            $this->del_cache($id);
            return $this->redirect(['product/operation']);
        } else {
            return $this->renderAjax('set_tag_config', [
                'model' => $model,
            ]);
        }
    }

    /**
     * actionCustomerScreen
     * @date     2019/3/18 11:28
     * @author   jixiaoyong<810964998@qq.com>
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionCustomerScreen($id){
        $model = $this->findModel($id);
        $model->setScenario(Product::SCENARIO_CUSTOMER_SCREEN);
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->filter_net_time = $this->bit_grinding(Yii::$app->request->post()['Product']['filter_net_time']);
            $res = $model->save();
            if(!$res) Yii::$app->session->setFlash ( 'error' , $model->getErrors());
            $this->del_cache($id);
            return $this->redirect(['product/operation']);
        } else {
            return $this->renderAjax('customer_screen', [
                'model' => $model,
            ]);
        }
    }

    /**
     * actionSetSizeConfig
     * @date     2019/3/18 11:28
     * @author   jixiaoyong<810964998@qq.com>
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionSetSizeConfig($id){
        $model = $this->findModel($id);
        $model->setScenario(Product::SCENARIO_SET_SIZE_CONFIG);
        if (Yii::$app->request->isPost) {
            $transaction = $model::getDb()->beginTransaction();
            try {
                $model->load(Yii::$app->request->post());
                $model->limit_begin_time = strtotime(Yii::$app->request->post()['Product']['limit_begin_time']);
                $model->limit_end_time = strtotime(Yii::$app->request->post()['Product']['limit_end_time']);
                $model->save();
                if ($model->save() === false) {
                    throw new \Exception('save product fail');
                }
                $item = Yii::$app->request->post()['Product']['appIds'];
                if(empty($item)){
                    throw  new \Exception('请选择筛选标签');
                }
                $product_limit_model = ProductPlatLimit::find()->where(['product_id'=>$model->id])->all();
                if(!is_null($product_limit_model)) ProductPlatLimit::deleteAll();
                $key = ['product_id','app_id'];
                $data = [];
                for(reset($item);$tag_id = current($item);next($item)){
                    $data[] = ['product_id'=>$model->id,'app_id'=>$tag_id];
                }
                Yii::$app->db->createCommand()->batchInsert(ProductPlatLimit::tableName(), $key, $data)->execute();
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash ( 'error' , $e->getMessage());
            }
            $this->del_cache($id);
            return $this->redirect(['product/operation']);
        } else {
            return $this->renderAjax('set_size_config', [
                'model' => $model,
            ]);
        }
    }

    /**
     * actionSetSceneConfig
     * @date     2019/3/18 11:28
     * @author   jixiaoyong<810964998@qq.com>
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionSetSceneConfig($id){
        $model = $this->findModel($id);
        $model->setScenario(Product::SCENARIO_SET_SCENE_CONFIG);
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->online_scenario = $this->bit_grinding(Yii::$app->request->post()['Product']['online_scenario']);
            $model->visible = $this->bit_grinding(Yii::$app->request->post()['Product']['visible']);
            $model->visible_mobile = $this->bit_grinding(Yii::$app->request->post()['Product']['visible_mobile']);
            $res = $model->save();
            if(!$res) Yii::$app->session->setFlash ( 'error' , $model->getErrors());
            $this->del_cache($id);
            return $this->redirect(['product/operation']);
        } else {
            return $this->renderAjax('set_scene_config', [
                'model' => $model,
            ]);
        }
    }

    public function actionApiProperty($id){
        $model = $this->getProperty($id);
        $offline_repay_detail = $offline_repay_type = $manual_repay_detail = [];
        $single = $multi = $combine = 0;
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->post()['ProductProperty']['can_manual_repay']){
                if(!empty(Yii::$app->request->post()['single'])) $single = Yii::$app->request->post()['single'];
                if(!empty(Yii::$app->request->post()['multi']))  $multi = Yii::$app->request->post()['multi'];
                if(!empty(Yii::$app->request->post()['combine'])) $combine = Yii::$app->request->post()['combine'];
                if($single == 0 && $multi == 0 && $combine == 0) throw  new \Exception('请选择主动还款模式');
                if(($multi > 0 || $combine > 0) || $multi == 0 || $combine ==0) throw  new \Exception('请选择多期还款');
            }
            $manual_repay_detail['one'] = ['repayment_mode' => $single];
            $manual_repay_detail['more']['repayment_mode'] = $multi;
            $manual_repay_detail['more']['overdue_need_combine'] = $combine;
            $model->manual_repay_detail = json_encode($manual_repay_detail);
            if(Yii::$app->request->post()['ProductProperty']['can_offline_repay']){
                if(!empty(Yii::$app->request->post()['offline_repay_type'])) $offline_repay_type = Yii::$app->request->post()['offline_repay_type'];
                if(empty($offline_repay_type)) throw  new \Exception('请选择还款类型');
                if (in_array(1, $offline_repay_type)){
                    if(empty(Yii::$app->request->post()['wx_account']) || empty(Yii::$app->request->post()['wx_account_name']) || empty(Yii::$app->request->post()['wx_remark'])) throw  new \Exception('请填写微信还款配置');
                    $offline_repay_detail['wx']['wx_account'] = Yii::$app->request->post()['wx_account'];
                    $offline_repay_detail['wx']['wx_account_name'] = Yii::$app->request->post()['wx_account_name'];
                    $offline_repay_detail['wx']['wx_remark'] = Yii::$app->request->post()['wx_remark'];
                }
                if (in_array(1, $offline_repay_type)){
                    if(empty(Yii::$app->request->post()['ali_account']) || empty(Yii::$app->request->post()['ali_account_name']) || empty(Yii::$app->request->post()['ali_remark'])) throw  new \Exception('请填写支付宝还款配置');
                    $offline_repay_detail['ali']['ali_account'] = Yii::$app->request->post()['ali_account'];
                    $offline_repay_detail['ali']['ali_account_name'] = Yii::$app->request->post()['ali_account_name'];
                    $offline_repay_detail['ali']['ali_remark'] = Yii::$app->request->post()['ali_remark'];
                }
                if (in_array(1, $offline_repay_type)){
                    if(empty(Yii::$app->request->post()['other_account']) || empty(Yii::$app->request->post()['other_account_name']) || empty(Yii::$app->request->post()['other_remark'])) throw  new \Exception('请填写其他还款配置');
                    $offline_repay_detail['other']['other_account'] = Yii::$app->request->post()['other_account'];
                    $offline_repay_detail['other']['other_account_name'] = Yii::$app->request->post()['other_account_name'];
                    $offline_repay_detail['other']['other_remark'] = Yii::$app->request->post()['other_remark'];
                }
            }
            $offline_repay_detail['offline_repay_type'] = $offline_repay_type;
            $model->offline_repay_detail = json_encode($offline_repay_detail);
            $res = $model->save();
            if(!$res) Yii::$app->session->setFlash ( 'error' , $model->getErrors());
            $this->del_cache($id);
            return $this->redirect(['product/index']);
        } else {
            return $this->render('api_property', [
                'model' => $model,
            ]);
        }
    }

    public function actionValidateWeightForm($id){
        $model = $this->findModel($id);
        $model->setScenario(Product::SCENARIO_SET_WEIGHT);
        $model->load(Yii::$app->request->post());
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($model);
    }

    public function actionValidateSetOnlineConfigForm($id){
        $model = $this->findModel($id);
        $model->setScenario(Product::SCENARIO_SET_WEIGHT);
        $model->load(Yii::$app->request->post());
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($model);
    }

    public function actionValidateSetTagConfigForm($id){
        $model = $this->findModel($id);
        $model->setScenario(Product::SCENARIO_SET_TAG_CONFIG);
        $model->load(Yii::$app->request->post());
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($model);
    }

    public function actionValidateCustomerScreenForm($id){
        $model = $this->findModel($id);
        $model->setScenario(Product::SCENARIO_CUSTOMER_SCREEN);
        $model->load(Yii::$app->request->post());
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($model);
    }

    public function actionValidateSetSizeConfigForm($id){
        $model = $this->findModel($id);
        $model->setScenario(Product::SCENARIO_SET_SIZE_CONFIG);
        $model->load(Yii::$app->request->post());
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($model);
    }


    public function actionOffline($id){
        $model = $this->findModel($id);
        $model->online_scenario = 1000000000;
        $model->save(false);
        $this->del_cache($id);
        return $this->redirect(['product/operation']);
    }

    protected function bit_grinding($item){
        $str = '1000000000';
        if(!empty($item)){
            for(reset($item);$val = current($item);next($item)){
                $str = $str | $val;
            }
        }
        return intval($str);
    }

    public function del_cache($id){
        $service = \Yii::$container->get("RedisPrefixService");
        $key = $service::PRODUCT_DETAIL.$id;

        $service::delRedisByKey($key);
    }
}
