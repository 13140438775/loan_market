<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;


/* @var $this yii\web\View */
/* @var $model common\models\HotProduct */
/* @var $form yii\widgets\ActiveForm */
$product_url = \yii\helpers\Url::to(['credit-product/list']);
?>

<div class="hot-product-form">

    <?php $form = ActiveForm::begin([
        'options' => [
//            'class' => 'common-form',
        ],
        'fieldConfig' => function ($model, $attr) {
            $default = [
                'class' => 'yii\widgets\ActiveField',
                'template' => "<span class='col-sm-2 text-right'>{label}</span>\n{input}\n{hint}\n{error}",
                'options' => [
                    'class' => 'form-inline form-group-sm row top5',
                ],
                'inputOptions' => [
                    'class' => 'form-control',
                    'style' => 'width:400px;'
                ],
                'labelOptions' => [
                    'class' => 'control-label form-inline  initialism '
                ],
                'errorOptions' => [
                    'class' => 'self-help-block',
                    'tag' => 'span'
                ]
            ];

            return $default;
        }]) ?>

    <?= $form->field($model, 'product_id')->label('产品')->widget(Select2::class, [
        'initValueText' => empty($model->id) ? '' : $model->product->product_name, // set the initial display text
        'options' => ['placeholder' => '选择产品'],
        'size' => Select2::MEDIUM,
        'pluginOptions' => [
            'allowClear' => true,
            'width'=>'400px',
            'minimumInputLength' => 1,
            'language' => [
                'errorLoading' => new JsExpression("function () { return '稍等'; }"),
            ],
            'ajax' => [
                'url' => $product_url,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(item) { return item.text; }'),
            'templateSelection' => new JsExpression('function (item) { return item.text; }'),
        ],
    ])->error(['error' => "请选择产品"]) ?>


    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
