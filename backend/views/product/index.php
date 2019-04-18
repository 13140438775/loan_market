<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '产品列标配';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mk-product-index box box-primary">
    <div class="box-header with-border">
        <?= Html::button('添加产品', ['id' => 'add-product', 'class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'name',
                'merchant_id',
                'show_name',
//                'logo_url:url',
                // 'description',
                // 'sort_min_loan_time:datetime',
                // 'sort_min_loan_time_type:datetime',
                // 'show_min_loan_time',
                // 'show_interest_desc',
                // 'show_amount_range',
                // 'max_amount',
                // 'min_amount',
                // 'interest_day',
                // 'show_avg_term',
                // 'interest_pay_type',
                // 'interest_pay_type_desc',
                // 'show_tag_id',
                // 'created_at',
                // 'updated_at',
                // 'call_type',
                // 'product_type',
                // 'is_fixed_step',
                // 'incr_step',
                // 'is_same_interest',
                // 'term_type',
                // 'min_term',
                // 'max_term',
                // 'single_interest',
                // 'single_fee',
                // 'last_operator_id',
                // 'weight',
                // 'filter_user_enable',
                // 'enable_mobile_black',
                // 'min_age',
                // 'max_age',
                // 'area_filter',
                // 'filter_net_time:datetime',
                // 'online_scenario',
                // 'visible',
                // 'visible_mobile',
                // 'enable_count_limit',
                // 'is_time_sharing:datetime',
                // 'limit_begin_time:datetime',
                // 'limit_end_time:datetime',
                // 'uv_day_limit',
                // 'is_diff_first',
                // 'is_diff_plat',
                // 'first_loan_one_push_limit',
                // 'first_loan_approval_limit',
                // 'second_loan_one_push_limit',
                // 'second_loan_approval_limit',
                // 'config_status',
                // 'display_status',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{fee-config}  {api-config}  {auth} {hand-fill-config} {api-property}',
                    'buttons' => [
                        'fee-config' => function ($url, $model) {
                            return Html::a('期限费率配置',\yii\helpers\Url::toRoute(['/product/update-fee','id'=>$model->id]));
                        },
                        'api-config' => function ($url, $model) {
                            return Html::a('接口配置',\yii\helpers\Url::toRoute(['/product/api-config','id'=>$model->id]));
                        },
                        'api-property' => function ($url, $model) {
                            return Html::a('API产品属性',\yii\helpers\Url::toRoute(['/product/api-property','id'=>$model->id]));
                        },
                        'auth' => function ($url, $model) {
                            return Html::a('认证项配置',\yii\helpers\Url::toRoute(['/product/update-auth','id'=>$model->id]));
                        },
                        'hand-fill-config' => function ($url, $model) {
                            return Html::a('手填项',\yii\helpers\Url::toRoute(['/product/hand-fill-config','id'=>$model->id]));
                        },

                    ],

                ],
            ],
        ]); ?>
    </div>
</div>
<?php
Modal::begin([
    'header' => '<h4>选择对接方式</h4>',
    'id' => 'model',
    'size' => 'model-lg',
]);

echo "<div id='modelContent' style='display: flex;justify-content: center;'>";
echo Html::a('Api对接', ['create-api'], ['class' => 'btn btn-success btn-flat', 'style' => 'margin-right:50px']);
echo Html::a('H5对接', ['create-h5'], ['class' => 'btn btn-success btn-flat']);

echo "</div>";

Modal::end();

$js = <<<JS
$(function(){
    $('#add-product').click(function(){
        $('.modal').modal('show')
            .find('#modelContent');
    });
});
JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>

