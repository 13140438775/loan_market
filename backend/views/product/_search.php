<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mk-product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'merchant_id') ?>

    <?= $form->field($model, 'show_name') ?>

    <?= $form->field($model, 'logo_url') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'sort_min_loan_time') ?>

    <?php // echo $form->field($model, 'sort_min_loan_time_type') ?>

    <?php // echo $form->field($model, 'show_min_loan_time') ?>

    <?php // echo $form->field($model, 'show_interest_desc') ?>

    <?php // echo $form->field($model, 'show_amount_range') ?>

    <?php // echo $form->field($model, 'max_amount') ?>

    <?php // echo $form->field($model, 'min_amount') ?>

    <?php // echo $form->field($model, 'interest_day') ?>

    <?php // echo $form->field($model, 'show_avg_term') ?>

    <?php // echo $form->field($model, 'interest_pay_type') ?>

    <?php // echo $form->field($model, 'interest_pay_type_desc') ?>

    <?php // echo $form->field($model, 'show_tag_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'call_type') ?>

    <?php // echo $form->field($model, 'product_type') ?>

    <?php // echo $form->field($model, 'is_fixed_step') ?>

    <?php // echo $form->field($model, 'incr_step') ?>

    <?php // echo $form->field($model, 'is_same_interest') ?>

    <?php // echo $form->field($model, 'term_type') ?>

    <?php // echo $form->field($model, 'min_term') ?>

    <?php // echo $form->field($model, 'max_term') ?>

    <?php // echo $form->field($model, 'single_interest') ?>

    <?php // echo $form->field($model, 'single_fee') ?>

    <?php // echo $form->field($model, 'last_operator_id') ?>

    <?php // echo $form->field($model, 'weight') ?>

    <?php // echo $form->field($model, 'filter_user_enable') ?>

    <?php // echo $form->field($model, 'enable_mobile_black') ?>

    <?php // echo $form->field($model, 'min_age') ?>

    <?php // echo $form->field($model, 'max_age') ?>

    <?php // echo $form->field($model, 'area_filter') ?>

    <?php // echo $form->field($model, 'filter_net_time') ?>

    <?php // echo $form->field($model, 'online_scenario') ?>

    <?php // echo $form->field($model, 'visible') ?>

    <?php // echo $form->field($model, 'visible_mobile') ?>

    <?php // echo $form->field($model, 'enable_count_limit') ?>

    <?php // echo $form->field($model, 'is_time_sharing') ?>

    <?php // echo $form->field($model, 'limit_begin_time') ?>

    <?php // echo $form->field($model, 'limit_end_time') ?>

    <?php // echo $form->field($model, 'uv_day_limit') ?>

    <?php // echo $form->field($model, 'is_diff_first') ?>

    <?php // echo $form->field($model, 'is_diff_plat') ?>

    <?php // echo $form->field($model, 'first_loan_one_push_limit') ?>

    <?php // echo $form->field($model, 'first_loan_approval_limit') ?>

    <?php // echo $form->field($model, 'second_loan_one_push_limit') ?>

    <?php // echo $form->field($model, 'second_loan_approval_limit') ?>

    <?php // echo $form->field($model, 'config_status') ?>

    <?php // echo $form->field($model, 'display_status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
