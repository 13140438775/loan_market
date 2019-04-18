<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view box box-primary">
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'merchant_id',
                'show_name',
                'logo_url:url',
                'description',
                'sort_min_loan_time:datetime',
                'sort_min_loan_time_type:datetime',
                'show_min_loan_time',
                'show_interest_desc',
                'show_amount_range',
                'max_amount',
                'min_amount',
                'interest_day',
                'show_avg_term',
                'interest_pay_type',
                'interest_pay_type_desc',
                'show_tag_id',
                'created_at:datetime',
                'updated_at:datetime',
                'call_type',
                'product_type',
                'is_fixed_step',
                'incr_step',
                'incr_amount_step',
                'is_same_interest',
                'term_type',
                'min_term',
                'max_term',
                'single_interest',
                'single_fee',
                'last_operator_id',
                'weight',
                'filter_user_enable',
                'enable_mobile_black',
                'min_age',
                'max_age',
                'area_filter',
                'filter_net_time:datetime',
                'online_scenario',
                'visible',
                'visible_mobile',
                'enable_count_limit',
                'is_time_sharing:datetime',
                'limit_begin_time:datetime',
                'limit_end_time:datetime',
                'uv_day_limit',
                'is_diff_first',
                'is_diff_plat',
                'first_loan_one_push_limit',
                'first_loan_approval_limit',
                'second_loan_one_push_limit',
                'second_loan_approval_limit',
                'config_status',
                'display_status',
            ],
        ]) ?>
    </div>
</div>
