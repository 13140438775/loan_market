<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \common\models\ProductTag;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductTagSearch */
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
        ]])->textInput(['class'=>'text-ipt'])->label('标签id',['class' => 'field-label']) ?>

    <?= $form->field($model, 'tag_name', [
        'options' => [
            'class' => 'text-field-container'
        ]])->textInput(['class'=>'text-ipt'])->label('标签名称',['class' => 'field-label']) ?>

    <?= $form->field($model, 'is_enable', [
        'options' => [
            'class' => 'text-field-container'
        ]])->dropDownList(ProductTag::$is_enable_set,['class'=>'form-controlx'])->label('标签状态',['class' => 'field-label']) ?>

    <?php // echo $form->field($model, 'sort') ?>

    <?php // echo $form->field($model, 'is_enable') ?>

    <?php // echo $form->field($model, 'is_valid') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
