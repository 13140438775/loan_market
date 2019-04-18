<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CreditProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '产品管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-product-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加产品', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'sort',
            'id',
            [
                'attribute' => 'logo_url',
                'format' => 'raw',
                'value' => function($model){
                    return Html::img(\Yii::$app->params['oss']['url_prefix'].$model->logo_url, ['height' =>50, 'width' => 50]);
                }
            ],
            'product_name',
            [
                'attribute' => 'creditLines',
                'value' => function($model){
                    return $model->min_credit.'-'.$model->max_credit;
                }
            ],
            [
                'attribute' => 'rate',
                'value' => function($model){
                    return \common\models\CreditProduct::$rate_type_set[$model->rate_type].'利率'.$model->rate_num;
                }
            ],
            [
                'attribute' => 'avg_credit_days',
                'value' => function($model){
                    return $model->avg_credit_days.\common\models\CreditProduct::$avg_credit_limit_type_set[$model->avg_credit_limit_type];
                }
            ],
            [
                'attribute' => 'product_status',
                'value' => function($model){
                    return \common\models\CreditProduct::$product_status_set[$model->product_status];
                }
            ],
            //'product_desc',
            //'product_type',
            //'up_time:datetime',
            //'product_status',
            //'apply_conditions',
            //'max_credit',
            //'rate_type',
            //'rate_num',
            //'min_credit_days',
            //'max_credit_days',
            //'credit_limit_type',
            //'avg_credit_days',
            //'avg_credit_limit_type',
            //'fast_loan',
            //'fast_loan_type',
            //'url:url',
            //'logo_url:url',
            //'apply_materia',
            //'credit_base',
            //'tag_ids',
            //'tag_id',
            //'uv_limit',
            //'sort',
            //'is_inner',
            //'is_valid',
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update}',
                'buttons' => [
                    'update' => function ($url) {
                        return Html::a('<span class="glyphicon">编辑</span>', $url);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
