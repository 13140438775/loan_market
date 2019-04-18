<?php
namespace api\controllers;

use common\services\DataCollectService;
use common\models\Apps;
use common\models\CreditProduct;
use function Sodium\crypto_box_publickey_from_secretkey;

/**
 * Site controller
 */
class GoController extends BaseController
{
    public function actionTo($id, $user_id,$app_code ){
        if($app_code === 'xiajie'){
            $app_code = 'loanmarket';
        }
        if($app_code === 'dddai'){
            $app_code = 'dididai';
        }
        $product = CreditProduct::findOne($id);
        $app = Apps::findOne(['app_code' => $app_code]);
        /**
         * @var $service DataCollectService
         */
        $service = \Yii::$container->get('DataCollectService');
        $user_id = intval($user_id);
        if($product && $user_id && $app){
            $result = $service->collect($product,$user_id, $app);
            if($product->uv_limit > 0 && $product->uv_limit <= $service->getTodayProductUv($product->id)){
                $product->product_status = CreditProduct::PRODUCT_STATUS_TEMP_DOWN;
                $product->save();
            }
            if($app_code === 'loanmarket'){
                $service->doLog($user_id,$product->id);
            }
        }else{
            \Yii::error('异常数据'.$this->format());
        }

        return $this->redirect($product->url);
    }
    private function format(){
        return json_encode($_GET);
    }
}
