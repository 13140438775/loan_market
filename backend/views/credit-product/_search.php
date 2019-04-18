<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \common\models\CreditProduct;

/* @var $this yii\web\View */
/* @var $model backend\models\CreditProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="common-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'class' => 'common-search-form'
        ]
    ]); ?>

    <?= $form->field($model, 'id', [
        'options' => [
            'class' => 'text-field-container'
        ]])->textInput(['class'=>'text-ipt'])->label('产品id',['class' => 'field-label']) ?>

    <?= $form->field($model, 'product_name', [
        'options' => [
            'class' => 'text-field-container'
        ]])->textInput(['class'=>'text-ipt'])->label('产品名称',['class' => 'field-label']) ?>

    <?= $form->field($model, 'product_status', [
        'options' => [
            'class' => 'text-field-container'
        ]])->dropDownList(array_merge(['0' => "全部"], CreditProduct::$product_status_set),['class'=>'form-controlx'])->label('产品状态',['class' => 'field-label']) ?>


    <?php // echo $form->field($model, 'product_desc') ?>

    <?php // echo $form->field($model, 'product_type') ?>

    <?php // echo $form->field($model, 'up_time') ?>

    <?php // echo $form->field($model, 'product_status') ?>

    <?php // echo $form->field($model, 'apply_conditions') ?>

    <?php // echo $form->field($model, 'min_credit') ?>

    <?php // echo $form->field($model, 'max_credit') ?>

    <?php // echo $form->field($model, 'rate_type') ?>

    <?php // echo $form->field($model, 'rate_num') ?>

    <?php // echo $form->field($model, 'min_credit_days') ?>

    <?php // echo $form->field($model, 'max_credit_days') ?>

    <?php // echo $form->field($model, 'credit_limit_type') ?>

    <?php // echo $form->field($model, 'avg_credit_days') ?>

    <?php // echo $form->field($model, 'avg_credit_limit_type') ?>

    <?php // echo $form->field($model, 'fast_loan') ?>

    <?php // echo $form->field($model, 'fast_loan_type') ?>

    <?php // echo $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'logo_url') ?>

    <?php // echo $form->field($model, 'apply_materia') ?>

    <?php // echo $form->field($model, 'credit_base') ?>

    <?php // echo $form->field($model, 'tag_ids') ?>

    <?php // echo $form->field($model, 'tag_id') ?>

    <?php // echo $form->field($model, 'uv_limit') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <?php // echo $form->field($model, 'is_inner') ?>

    <?php // echo $form->field($model, 'is_valid') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
