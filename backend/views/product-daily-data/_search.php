<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \kartik\date\DatePicker;
use \kartik\export\ExportMenu;
/* @var $this yii\web\View */
/* @var $model backend\models\ProductDailyDataSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-daily-data-search common-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'class' => 'common-search-form'
        ]
    ]); ?>

    <?php echo $form->field($model, 'date_begin')->widget(DatePicker::class, [
        'type' => DatePicker::TYPE_INPUT,
        'language' => 'zh-CN',
        'options' => [
            'value' => '',
            'autocomplete' => 'off'
        ],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ])->textInput(['class' => 'text-ipt'])->label('开始时间', ['class' => 'field-label']);
    ?>

    <?php echo $form->field($model, 'date_end')->widget(DatePicker::class, [
        'type' => DatePicker::TYPE_INPUT,
        'language' => 'zh-CN',
        'options' => [
            'value' => '',
            'autocomplete' => 'off'
        ],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ])->textInput(['class' => 'text-ipt'])->label('结束时间', ['class' => 'field-label']);
    ?>


    <?= $form->field($model, 'product_id', [
        'options' => [
            'class' => 'text-field-container'
        ]])->textInput(['class' => 'text-ipt'])->label('产品id', ['class' => 'field-label']) ?>

    <?= $form->field($model, 'product_name', [
        'options' => [
            'class' => 'text-field-container'
        ]])->textInput(['class' => 'text-ipt'])->label('产品名称', ['class' => 'field-label']) ?>

    <?= $form->field($model, 'app_id', [
        'options' => [
            'class' => 'text-field-container'
        ]])->dropDownList(\common\models\Apps::dropDownList(), ['class' => 'form-controlx'])->label('app_name', ['class' => 'field-label']) ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
