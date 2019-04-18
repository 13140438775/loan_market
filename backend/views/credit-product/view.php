<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\CreditProduct */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '产品管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="credit-product-view">

    <p>
        <?= Html::a('更新产品', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除产品', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'product_name',
            'product_phone',
            'product_qq',
            'product_features',
            'product_desc',
            'product_type',
            'up_time:datetime',
            'product_status',
            'apply_conditions',
            'min_credit',
            'max_credit',
            'rate_type',
            'rate_num',
            'min_credit_days',
            'max_credit_days',
            'credit_limit_type',
            'avg_credit_days',
            'avg_credit_limit_type',
            'fast_loan',
            'fast_loan_type',
            'url:url',
            'logo_url:url',
            'apply_materia',
            'credit_base',
            'tag_id',
            'uv_limit',
            'sort',
            'is_inner',
            'is_valid',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
