<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\HotProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hot-product-search common-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'class' => 'common-search-form'
        ]
    ]); ?>

    <?= $form->field($model, 'product_id', [
        'options' => [
            'class' => 'text-field-container'
        ]])->textInput(['class'=>'text-ipt'])->label('产品id',['class' => 'field-label']) ?>

    <?= $form->field($model, 'product_name', [
        'options' => [
            'class' => 'text-field-container'
        ]])->textInput(['class'=>'text-ipt'])->label('产品名称',['class' => 'field-label']) ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
