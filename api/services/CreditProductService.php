<?php

namespace api\services;

use common\models\CreditProduct;
use common\models\HotProduct;
use common\models\ProductDailyData;
use common\models\ProductTag;
use \yii\helpers\Url;

/**
 * Class CreditProductService
 * @package common\services
 */
class CreditProductService extends BaseService
{

    /**
     * 文件描述 热门产品
     * Created On 2019-02-19 11:27
     * Created By heyafei
     * @param $tag_id
     * @param $page
     * @param $page_num
     * @return array
     */
    public static function hotProductList($tag_id, $page, $page_num)
    {
        $where = [
            'credit_product.is_valid' => self::IS_VALID,
            'hot_product.is_enable' => self::IS_VALID,
            'credit_product.product_status' => CreditProduct::PRODUCT_STATUS_UP
        ];
        if ($tag_id) $where['product_assoc_tag.tag_id'] = $tag_id;

        $select = [
            'product_id' => 'credit_product.id',
            'logo_url' => 'credit_product.logo_url',
            'product_name' => 'credit_product.product_name',
            'tag_name' => 'product_tag.tag_name',
            'tag_img' => 'product_tag.tag_img',
            'min_credit' => 'credit_product.min_credit',
            'max_credit' => 'credit_product.max_credit',
            'rate_type' => 'credit_product.rate_type',
            'rate_num' => 'credit_product.rate_num',
            'avg_credit_days' => 'credit_product.avg_credit_days',
            'avg_credit_limit_type' => 'credit_product.avg_credit_limit_type',
            'product_desc' => 'credit_product.product_desc',
        ];

        $model = HotProduct::find()
            ->select($select)
            ->leftJoin('product_assoc_tag', 'product_assoc_tag.product_id = hot_product.product_id')
            ->leftJoin('credit_product', 'credit_product.id = hot_product.product_id')
            ->leftJoin('product_tag', 'product_tag.id = credit_product.tag_id')
            ->where($where)
            ->distinct(true);
        $total_count = $model->count();

        $total_page = ceil($total_count / $page_num);
        $hot_product_list = $model->limit($page_num)
            ->offset(($page - 1) * $page_num)
            ->orderBy("hot_product.sort, hot_product.id DESC, hot_product.updated_at DESC")
            ->asArray()
            ->all();
        array_walk($hot_product_list, function (&$item) {
            if($item['logo_url']) $item['logo_url'] = \Yii::$app->params['oss']['url_prefix'] . $item['logo_url'];
            if($item['tag_img']) $item['tag_img'] = \Yii::$app->params['oss']['url_prefix'] . $item['tag_img'];
            $url = Url::to([Utils::getSuiteRoute('go/to')], true);
            $item['apply_url'] = $url."?id={$item['product_id']}";
        });
        return [
            'total_count' => $total_count,
            'total_page' => $total_page,
            'staff_list' => $hot_product_list,
        ];
    }

    /**
     * 文件描述 产品列表
     * Created On 2019-02-19 11:27
     * Created By heyafei
     * @param $tag_id
     * @param $page
     * @param $page_num
     * @return array
     */
    public static function productList($tag_id, $page, $page_num)
    {
        $where = [
            'credit_product.is_valid' => self::IS_VALID,
            'credit_product.product_status' => CreditProduct::PRODUCT_STATUS_UP
        ];
        if ($tag_id) $where['product_assoc_tag.tag_id'] = $tag_id;

        $select = [
            'product_id' => 'credit_product.id',
            'logo_url' => 'credit_product.logo_url',
            'product_name' => 'credit_product.product_name',
            'tag_name' => 'product_tag.tag_name',
            'tag_img' => 'product_tag.tag_img',
            'min_credit' => 'credit_product.min_credit',
            'max_credit' => 'credit_product.max_credit',
            'rate_type' => 'credit_product.rate_type',
            'rate_num' => 'credit_product.rate_num',
            'avg_credit_days' => 'credit_product.avg_credit_days',
            'avg_credit_limit_type' => 'credit_product.avg_credit_limit_type',
            'product_desc' => 'credit_product.product_desc',
        ];

        $model = CreditProduct::find()
            ->select($select)
            ->leftJoin('product_assoc_tag', 'product_assoc_tag.product_id = credit_product.id')
            ->leftJoin('product_tag', 'product_tag.id = credit_product.tag_id')
            ->where($where)
            ->distinct(true);
        $total_count = $model->count();

        $total_page = ceil($total_count / $page_num);
        $product_list = $model->limit($page_num)
            ->offset(($page - 1) * $page_num)
            ->orderBy('credit_product.sort, credit_product.id DESC, credit_product.updated_at DESC')
            ->asArray()
            ->all();
        array_walk($product_list, function (&$item) {
            if($item['logo_url']) $item['logo_url'] = \Yii::$app->params['oss']['url_prefix'] . $item['logo_url'];
            if($item['tag_img']) $item['tag_img'] = \Yii::$app->params['oss']['url_prefix'] . $item['tag_img'];
            $url = Url::to([Utils::getSuiteRoute('go/to')], true);
            $item['apply_url'] = $url."?id={$item['product_id']}";
        });
        return [
            'total_count' => $total_count,
            'total_page' => $total_page,
            'staff_list' => $product_list,
        ];
    }

    /**
     * 文件描述 产品详情
     * Created On 2019-01-28 20:21
     * Created By heyafei
     * * @param $product_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function productView($product_id)
    {
        $select = ["credit_base", "fast_loan", "fast_loan_type", "logo_url", "product_name", "min_credit", "max_credit", "rate_type", "rate_num", "min_credit_days", "max_credit_days", "credit_limit_type", "product_features", "apply_conditions"];
        $product_detail = CreditProduct::find()->select($select)->where(["id" => $product_id])->asArray()->one();
        if (!$product_detail) return [];
        $uv_sum = ProductDailyData::find()->where(['product_id' => $product_id])->sum('uv');

        if($product_detail['logo_url']) $product_detail['logo_url'] = \Yii::$app->params['oss']['url_prefix'].$product_detail['logo_url'];
        $product_detail['count_people'] = $product_detail["credit_base"] + $uv_sum;
        $product_detail['credit_limit_type'] = CreditProduct::$credit_limit_type_set[$product_detail['credit_limit_type']];
        $product_detail['fast_loan_type'] = CreditProduct::$fast_loan_type_set[$product_detail['fast_loan_type']];

        $url = Url::to([Utils::getSuiteRoute('go/to')], true);
        $product_detail['apply_url'] = $url."?id={$product_id}";
        return $product_detail;
    }

    /**
     * 文件描述 标签列表
     * Created On 2019-01-28 20:23
     * Created By heyafei
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function tagList()
    {
        $where = [
            'is_enable' => self::IS_VALID,
            'is_valid' => self::IS_VALID
        ];
        $tags = ProductTag::find()->where($where)->limit(4)->orderBy("sort")->asArray()->all();
        array_walk($tags, function (&$item) {
            $item['tag_icon'] = $item['tag_icon'] ? \Yii::$app->params['oss']['url_prefix'] . $item['tag_icon'] : '';
            $item['tag_img'] = $item['tag_icon'] ? \Yii::$app->params['oss']['url_prefix'] . $item['tag_img'] : '';
        });
        return $tags;
    }


}