<?php

namespace console\controllers;
use common\models\CreditProduct;
use common\models\ProductDailyData;
use yii\console\Controller;

class UpdateProductStatusController extends Controller
{

    /**
     * 文件描述 待上架产品到上线时间更改产品状态
     * Created On 2019-02-14 18:54
     * Created By heyafei
     */
    public function actionUpdateProductStatus()
    {
        $where = [
            'and',
            ['product_status' => CreditProduct::PRODUCT_STATUS_TEMP_UP],
            ['>=', 'up_time', time()]
        ];
        $product_list = CreditProduct::find()->select('id')
            ->where($where)
            ->andWhere(['!=', 'up_time', 0])
            ->column();
        if($product_list) {
            CreditProduct::updateAll(['product_status' => CreditProduct::PRODUCT_STATUS_UP], ['id' => $product_list]);
        }
    }

    /**
     * 文件描述 临时下架产品次日凌晨上架
     * Created On 2019-02-15 22:38
     * Created By heyafei
     */
    public function actionProductStatusTempUp()
    {
        $where = ['product_status' => CreditProduct::PRODUCT_STATUS_TEMP_DOWN];
        $product_list = CreditProduct::find()->select('id')
            ->where($where)
            ->column();
        if($product_list) {
            CreditProduct::updateAll(['product_status' => CreditProduct::PRODUCT_STATUS_UP], ['id' => $product_list]);
        }
    }

    /**
     * 文件描述 上架产品当日UV控量临时下架
     * Created On 2019-02-15 23:02
     * Created By heyafei
     */
    public function actionProductStatusTempDown()
    {
        $product_nu_num = $product_ids = $p_ds = [];
        $where = ['product_status' => CreditProduct::PRODUCT_STATUS_UP];
        $product_list = CreditProduct::find()
            ->select(['id', 'uv_limit'])
            ->where($where)
            ->asArray()
            ->all();
        foreach($product_list AS $val) {
            $product_ids[] = $val['id'];
            $product_nu_num[$val['id']] = $val['uv_limit'];
        }
        if($product_ids) {
            $where =[
                'and',
                ['date' => "20190214"],
                ['in', 'product_id', $product_ids]
            ];
            $product_uv_sum = ProductDailyData::find()->select(['product_id', 'uv'])->where($where)->asArray()->all();
            foreach ($product_uv_sum AS $value) {
                if(isset($product_nu_num[$value['product_id']])) {
                    if($value['uv'] > $product_nu_num[$value['product_id']]) $p_ds[] = $value['product_id'];
                }
            }
        }

        if($p_ds) {
            CreditProduct::updateAll(['product_status' => CreditProduct::PRODUCT_STATUS_TEMP_DOWN], ['id' => $p_ds]);
        }
    }

}
